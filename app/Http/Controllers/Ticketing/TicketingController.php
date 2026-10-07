<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketSlaLog;
use App\Services\LunaAiService;
use App\Services\TicketAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketingController extends Controller
{
    public function __construct(
        public LunaAiService $lunaAi,
        public TicketAssignmentService $assignmentService
    ) {}

    /**
     * Overview & Key Stats
     */
    public function index(): View
    {
        $userId = Auth::id();
        $isStaff = Auth::user()->hasAnyRole(['super-admin', 'staff', 'student-staff']) || Auth::user()->type === 'Staf';

        $baseQuery = $isStaff ? Ticket::query() : Ticket::where('user_id', $userId);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'open' => (clone $baseQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'pending_user' => (clone $baseQuery)->where('status', 'pending_user')->count(),
            'waiting_central' => (clone $baseQuery)->where('status', 'waiting_central')->count(),
            'resolved' => (clone $baseQuery)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        $recentTickets = (clone $baseQuery)
            ->with(['user', 'assignee'])
            ->latest()
            ->take(6)
            ->get();

        return view('pages.ticketing.index', compact('stats', 'recentTickets', 'isStaff'));
    }

    /**
     * Create Ticket Form
     */
    public function create(): View
    {
        $services = Ticket::SERVICES;

        return view('pages.ticketing.create', compact('services'));
    }

    /**
     * Store New Ticket
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'service_item' => 'required|string|max:150',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/attachments', 'public');
        }

        // 1. Determine Authority from Service Catalog
        $authority = Ticket::SERVICES[$validated['category']][$validated['service_item']]['authority'] ?? 'local';

        // 2. Evaluate Ambiguity with Luna AI
        $ambiguityMsg = $this->lunaAi->evaluateAmbiguity(
            $validated['description'],
            $validated['location'] ?? null,
            $authority
        );

        // 3. Routing & Status determination
        $now = now();
        $assignedTo = null;
        $slaDueAt = null;
        $slaPausedAt = null;

        if ($authority === 'central') {
            $scope = 'escalated_central';
            $status = 'waiting_central';
            $slaPausedAt = $now;
        } elseif ($ambiguityMsg !== null) {
            $scope = 'needs_clarification';
            $status = 'pending_user';
            $slaPausedAt = $now;
        } else {
            $scope = 'internal_surabaya';
            $status = 'open';
            $assignedTo = $this->assignmentService->assignTechnician()?->id;

            // SLA Hours based on priority
            $hours = match ($validated['priority']) {
                'urgent' => 4,
                'high' => 12,
                'medium' => 24,
                default => 48,
            };
            $slaDueAt = $now->copy()->addHours($hours);
        }

        // 4. Create Ticket Record
        $ticket = Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'user_id' => Auth::id(),
            'assigned_to' => $assignedTo,
            'category' => $validated['category'],
            'service_item' => $validated['service_item'],
            'authority' => $authority,
            'scope' => $scope,
            'status' => $status,
            'priority' => $validated['priority'],
            'location' => $validated['location'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'attachment' => $attachmentPath,
            'sla_due_at' => $slaDueAt,
            'sla_paused_at' => $slaPausedAt,
        ]);

        // 5. Initial User Message
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'sender_type' => 'user',
            'message' => $validated['description'],
            'attachment' => $attachmentPath,
        ]);

        // 6. Automated Luna Responses & SLA Logs
        if ($authority === 'central') {
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'sender_type' => 'ai_luna',
                'message' => "Halo! Saya **Luna AI** dari PuTI Surabaya. Laporan Anda telah kami terima dan tercatat dengan nomor **{$ticket->ticket_number}**.\n\nKarena layanan **{$ticket->service_item}** memerlukan sinkronisasi pada sistem terpusat, tim PuTI Surabaya sedang mengawal dan mengoordinasikan laporan Anda bersama tim sistem terpusat Telkom University. Perkembangan penanganan akan kami informasikan langsung di thread ini. 😊",
            ]);

            TicketSlaLog::create([
                'ticket_id' => $ticket->id,
                'paused_at' => $now,
                'reason' => 'Koordinasi Sistem Terpusat',
            ]);
        } elseif ($ambiguityMsg !== null) {
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'sender_type' => 'ai_luna',
                'message' => $ambiguityMsg,
            ]);

            TicketSlaLog::create([
                'ticket_id' => $ticket->id,
                'paused_at' => $now,
                'reason' => 'Menunggu Tanggapan Pelapor (Klarifikasi Informasi Kendala)',
            ]);
        }

        return redirect()->route('ticket.show', $ticket)
            ->with('success', "Tiket {$ticket->ticket_number} berhasil dibuat!");
    }

    /**
     * User's Tickets List
     */
    public function myTickets(Request $request): View
    {
        $query = Ticket::where('user_id', Auth::id())
            ->with(['assignee', 'messages'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10)->withQueryString();

        return view('pages.ticketing.my-tickets', compact('tickets'));
    }

    /**
     * Ticket Detail & Conversation Thread
     */
    public function show(Ticket $ticket): View
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['super-admin', 'staff', 'student-staff']) || $user->type === 'Staf';

        // Authorization check: User can only view their own ticket, unless staff
        if (! $isStaff && $ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $ticket->load(['user', 'assignee', 'messages.user', 'slaLogs.creator']);

        return view('pages.ticketing.show', compact('ticket', 'isStaff'));
    }

    /**
     * Reply to Ticket Thread
     */
    public function reply(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['super-admin', 'staff', 'student-staff']) || $user->type === 'Staf';

        if (! $isStaff && $ticket->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:2',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'is_internal' => 'nullable|boolean',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/attachments', 'public');
        }

        $senderType = $isStaff ? 'staff' : 'user';
        $isInternal = $isStaff && $request->boolean('is_internal');

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
            'is_internal' => $isInternal,
        ]);

        // If user replies while ticket was waiting for user, resume SLA and activate ticket
        if (! $isStaff && $ticket->status === 'pending_user') {
            $now = now();
            $pausedMinutes = 0;

            if ($ticket->sla_paused_at) {
                $pausedMinutes = (int) $ticket->sla_paused_at->diffInMinutes($now);
            }

            // Close active SLA pause log
            $activeLog = $ticket->slaLogs()->whereNull('resumed_at')->latest()->first();
            if ($activeLog) {
                $activeLog->update([
                    'resumed_at' => $now,
                    'duration_minutes' => $pausedMinutes,
                ]);
            }

            // Push SLA due date forward
            $newDueAt = $ticket->sla_due_at ? $ticket->sla_due_at->copy()->addMinutes($pausedMinutes) : $now->copy()->addHours(24);

            // Auto-assign if not assigned
            $assigneeId = $ticket->assigned_to ?: $this->assignmentService->assignTechnician()?->id;

            $ticket->update([
                'status' => 'open',
                'scope' => 'internal_surabaya',
                'assigned_to' => $assigneeId,
                'sla_due_at' => $newDueAt,
                'sla_paused_at' => null,
            ]);

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'sender_type' => 'system',
                'message' => 'ℹ️ **Sistem:** Pelapor telah memberikan informasi tambahan. Tiket aktif kembali dan SLA dilanjutkan.',
            ]);
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Update Ticket Status (Technician only)
     */
    public function updateStatus(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['super-admin', 'staff', 'student-staff']) || $user->type === 'Staf';

        if (! $isStaff) {
            abort(403, 'Hanya staf yang dapat mengubah status tiket.');
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,pending_user,waiting_central,resolved,closed',
            'central_ticket_ref' => 'nullable|string|max:100',
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $validated['status'];
        $now = now();

        $updateData = ['status' => $newStatus];

        if (! empty($validated['central_ticket_ref'])) {
            $updateData['central_ticket_ref'] = $validated['central_ticket_ref'];
        }

        // SLA Handling based on transition
        if (in_array($newStatus, ['pending_user', 'waiting_central']) && ! in_array($oldStatus, ['pending_user', 'waiting_central'])) {
            // Pausing SLA
            $updateData['sla_paused_at'] = $now;
            $reason = $newStatus === 'pending_user' ? 'Menunggu Tanggapan Pelapor' : 'Koordinasi Sistem Terpusat';

            TicketSlaLog::create([
                'ticket_id' => $ticket->id,
                'paused_at' => $now,
                'reason' => $reason,
                'created_by' => $user->id,
            ]);
        } elseif (in_array($oldStatus, ['pending_user', 'waiting_central']) && ! in_array($newStatus, ['pending_user', 'waiting_central'])) {
            // Resuming SLA
            if ($ticket->sla_paused_at) {
                $duration = (int) $ticket->sla_paused_at->diffInMinutes($now);
                if ($ticket->sla_due_at) {
                    $updateData['sla_due_at'] = $ticket->sla_due_at->copy()->addMinutes($duration);
                }

                $activeLog = $ticket->slaLogs()->whereNull('resumed_at')->latest()->first();
                if ($activeLog) {
                    $activeLog->update([
                        'resumed_at' => $now,
                        'duration_minutes' => $duration,
                    ]);
                }
            }
            $updateData['sla_paused_at'] = null;
        }

        if (in_array($newStatus, ['resolved', 'closed']) && ! $ticket->resolved_at) {
            $updateData['resolved_at'] = $now;
        }

        // Auto claim ticket if staff moves it to in_progress and not assigned
        if ($newStatus === 'in_progress' && ! $ticket->assigned_to) {
            $updateData['assigned_to'] = $user->id;
        }

        $ticket->update($updateData);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'sender_type' => 'system',
            'message' => "ℹ️ **Status Diperbarui:** Status tiket diubah menjadi **{$ticket->user_status_label}** oleh **{$user->name}**.",
        ]);

        return back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    /**
     * Manual Override Scope (Technician only)
     */
    public function overrideScope(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['super-admin', 'staff', 'student-staff']) || $user->type === 'Staf';

        if (! $isStaff) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'scope' => 'required|in:internal_surabaya,escalated_central',
            'override_notes' => 'required|string|min:5',
            'central_ticket_ref' => 'nullable|string|max:100',
        ]);

        $now = now();
        $newScope = $validated['scope'];

        if ($newScope === 'escalated_central') {
            $ticket->update([
                'scope' => 'escalated_central',
                'status' => 'waiting_central',
                'authority' => 'central',
                'override_notes' => $validated['override_notes'],
                'central_ticket_ref' => $validated['central_ticket_ref'] ?? $ticket->central_ticket_ref,
                'sla_paused_at' => $now,
            ]);

            TicketSlaLog::create([
                'ticket_id' => $ticket->id,
                'paused_at' => $now,
                'reason' => 'Manual Override: Dialihkan ke Koordinasi Sistem Terpusat ('.$validated['override_notes'].')',
                'created_by' => $user->id,
            ]);

            $refText = $ticket->central_ticket_ref ? " (No. Rujukan Pusat: #{$ticket->central_ticket_ref})" : '';
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'sender_type' => 'system',
                'message' => "🔄 **Pengalihan Wewenang:** Tiket dialihkan ke **Koordinasi Sistem Terpusat**{$refText}.\n\n*Catatan teknisi:* {$validated['override_notes']}",
            ]);
        } else {
            // Revert back to local Surabaya
            if ($ticket->sla_paused_at) {
                $duration = (int) $ticket->sla_paused_at->diffInMinutes($now);
                if ($ticket->sla_due_at) {
                    $ticket->sla_due_at = $ticket->sla_due_at->copy()->addMinutes($duration);
                }
                $activeLog = $ticket->slaLogs()->whereNull('resumed_at')->latest()->first();
                if ($activeLog) {
                    $activeLog->update([
                        'resumed_at' => $now,
                        'duration_minutes' => $duration,
                    ]);
                }
            }

            $ticket->update([
                'scope' => 'internal_surabaya',
                'status' => 'in_progress',
                'authority' => 'local',
                'override_notes' => $validated['override_notes'],
                'sla_paused_at' => null,
                'assigned_to' => $ticket->assigned_to ?: $user->id,
            ]);

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'sender_type' => 'system',
                'message' => "🔄 **Pengalihan Wewenang:** Tiket ditarik kembali untuk **ditangani secara lokal** oleh teknisi PuTI Surabaya.\n\n*Catatan teknisi:* {$validated['override_notes']}",
            ]);
        }

        return back()->with('success', 'Wewenang tiket berhasil dialihkan.');
    }

    /**
     * NOC Tasks Board for Technicians
     */
    public function tasks(Request $request): View
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['super-admin', 'staff', 'student-staff']) || $user->type === 'Staf';

        if (! $isStaff) {
            abort(403, 'Halaman ini khusus untuk staf dan teknisi PuTI.');
        }

        $tab = $request->query('tab', 'all');

        $query = Ticket::with(['user', 'assignee'])->latest();

        match ($tab) {
            'unassigned' => $query->whereNull('assigned_to')->where('status', '!=', 'resolved')->where('status', '!=', 'closed'),
            'pending_user' => $query->where('status', 'pending_user'),
            'in_progress' => $query->where('status', 'in_progress'),
            'waiting_central' => $query->where('status', 'waiting_central'),
            'resolved' => $query->whereIn('status', ['resolved', 'closed']),
            default => $query->whereNotIn('status', ['closed']),
        };

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Ticket::whereNotIn('status', ['closed'])->count(),
            'unassigned' => Ticket::whereNull('assigned_to')->whereNotIn('status', ['resolved', 'closed'])->count(),
            'pending_user' => Ticket::where('status', 'pending_user')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'waiting_central' => Ticket::where('status', 'waiting_central')->count(),
            'resolved' => Ticket::whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('pages.ticketing.tasks', compact('tickets', 'tab', 'counts'));
    }

    /**
     * Poll endpoint for NOC audio chime & live counter
     */
    public function tasksPoll(): JsonResponse
    {
        $latestTicket = Ticket::latest()->first();
        $activeCount = Ticket::whereNotIn('status', ['resolved', 'closed'])->count();

        return response()->json([
            'latest_id' => $latestTicket?->id ?? 0,
            'latest_number' => $latestTicket?->ticket_number ?? '',
            'latest_title' => $latestTicket?->title ?? '',
            'latest_cat' => $latestTicket?->category ?? '',
            'active_count' => $activeCount,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Ticket History
     */
    public function history(Request $request): View
    {
        $user = Auth::user();
        $isStaff = $user->hasAnyRole(['super-admin', 'staff', 'student-staff']) || $user->type === 'Staf';

        $query = Ticket::with(['user', 'assignee'])->latest();

        if (! $isStaff) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_number', 'like', "%{$s}%")
                    ->orWhere('title', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(15)->withQueryString();
        $categories = array_keys(Ticket::SERVICES);

        return view('pages.ticketing.history', compact('tickets', 'categories', 'isStaff'));
    }

    /**
     * Interactive Luna AI Page
     */
    public function luna(): View
    {
        return view('pages.ticketing.luna');
    }

    /**
     * Luna AJAX Suggestion for Form
     */
    public function lunaSuggest(Request $request): JsonResponse
    {
        $text = $request->input('text', '');
        $suggestion = $this->lunaAi->suggestService($text);

        return response()->json([
            'success' => $suggestion !== null,
            'suggestion' => $suggestion,
        ]);
    }

    /**
     * Luna AJAX Interactive Chat
     */
    public function lunaChat(Request $request): JsonResponse
    {
        $message = $request->input('message', '');
        if (empty(trim($message))) {
            return response()->json(['reply' => 'Ada yang bisa saya bantu terkait layanan IT PuTI Surabaya? 😊']);
        }

        $reply = $this->lunaAi->chat($message);

        return response()->json([
            'reply' => $reply,
            'timestamp' => now()->format('H:i'),
        ]);
    }
}
