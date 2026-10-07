<?php

namespace App\Services;

use App\Models\Presence;
use App\Models\User;

class TicketAssignmentService
{
    /**
     * Assign a ticket to a present technician with the least active workload.
     */
    public function assignTechnician(): ?User
    {
        $today = now()->format('Y-m-d');

        // 1. Get user IDs who clocked in today (presence record exists with jam_masuk)
        $presentUserIds = Presence::where('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->pluck('user_id')
            ->toArray();

        if (empty($presentUserIds)) {
            return null;
        }

        // 2. Query eligible technicians present today
        // Eligible if they have role 'staff', 'student-staff', 'super-admin' or type is 'Staf'
        $query = User::whereIn('id', $presentUserIds)
            ->where(function ($q) {
                if (method_exists(User::class, 'role')) {
                    $q->whereHas('roles', function ($rq) {
                        $rq->whereIn('name', ['staff', 'student-staff', 'super-admin']);
                    })->orWhere('type', 'Staf');
                } else {
                    $q->where('type', 'Staf');
                }
            })
            ->withCount(['assignedTickets as active_tickets_count' => function ($q) {
                $q->whereIn('status', ['open', 'in_progress']);
            }])
            ->orderBy('active_tickets_count', 'asc')
            ->orderBy('id', 'asc');

        return $query->first();
    }
}
