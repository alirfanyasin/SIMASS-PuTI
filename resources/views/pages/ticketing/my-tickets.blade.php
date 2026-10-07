@extends('layouts.app-layout')

@section('title', 'Tiket Saya')
@section('subtitle', 'Daftar seluruh laporan bantuan yang telah Anda ajukan ke PuTI Surabaya')

@section('content')
<div class="space-y-6">

  <!-- Header Bar -->
  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm">
    <div>
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Pengajuan Bantuan</h2>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau progres pengerjaan kendala dan tanggapi pesan teknisi di sini.</p>
    </div>
    <a href="{{ route('ticket.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-telkom-600 hover:bg-telkom-700 text-white text-sm font-semibold shadow-lg shadow-telkom-600/20 transition shrink-0">
      <i data-lucide="plus-circle" class="w-4 h-4"></i>
      <span>Buat Tiket Baru</span>
    </a>
  </div>

  <!-- Status Filter Tabs -->
  <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold">
    @php
      $currentStatus = request()->query('status', '');
      $tabs = [
        ''                => 'Semua Tiket',
        'open'            => 'Laporan Diterima',
        'pending_user'    => 'Menunggu Tanggapan Anda',
        'in_progress'     => 'Sedang Ditangani',
        'waiting_central' => 'Koordinasi Pusat',
        'resolved'        => 'Selesai',
      ];
    @endphp

    @foreach($tabs as $val => $label)
      <a href="{{ route('ticket.my-tickets', array_filter(['status' => $val])) }}"
        class="px-4 py-2 rounded-xl whitespace-nowrap transition {{ $currentStatus === $val ? 'bg-telkom-600 text-white shadow-sm' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>

  <!-- Tickets List -->
  <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
    @if($tickets->isEmpty())
      <div class="p-12 text-center">
        <div class="w-14 h-14 rounded-3xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mx-auto mb-3">
          <i data-lucide="inbox" class="w-7 h-7"></i>
        </div>
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Tidak ada tiket ditemukan</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
          @if($currentStatus)
            Tidak ada tiket dengan status terpilih. Coba ganti filter status di atas.
          @else
            Anda belum pernah mengajukan tiket bantuan. Klik tombol "Buat Tiket Baru" jika Anda memerlukan bantuan IT.
          @endif
        </p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-400 text-xs font-semibold uppercase">
            <tr>
              <th class="px-6 py-3.5">No. Tiket</th>
              <th class="px-6 py-3.5">Judul Kendala</th>
              <th class="px-6 py-3.5">Layanan</th>
              <th class="px-6 py-3.5">Status Penanganan</th>
              <th class="px-6 py-3.5">Teknisi Bertugas</th>
              <th class="px-6 py-3.5">Tanggal Lapor</th>
              <th class="px-6 py-3.5 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
            @foreach($tickets as $ticket)
              <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-850/50 transition">
                <td class="px-6 py-4 font-mono font-bold text-xs text-telkom-600 dark:text-telkom-400">
                  {{ $ticket->ticket_number }}
                </td>
                <td class="px-6 py-4">
                  <p class="font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $ticket->title }}</p>
                  @if($ticket->location)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                      <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $ticket->location }}
                    </p>
                  @endif
                </td>
                <td class="px-6 py-4 text-xs font-medium">
                  {{ $ticket->service_item }}
                </td>
                <td class="px-6 py-4">
                  @php
                    $badgeStyles = [
                      'blue'    => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900',
                      'amber'   => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900',
                      'purple'  => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-400 dark:border-purple-900',
                      'orange'  => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900',
                      'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900',
                      'gray'    => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
                    ];
                    $badgeStyle = $badgeStyles[$ticket->user_status_color] ?? $badgeStyles['gray'];
                  @endphp
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeStyle }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $ticket->user_status_label }}
                  </span>
                </td>
                <td class="px-6 py-4 text-xs">
                  {{ $ticket->assignee?->name ?? 'Menunggu Penugasan' }}
                </td>
                <td class="px-6 py-4 text-xs text-gray-500">
                  {{ $ticket->created_at->format('d M Y, H:i') }}
                </td>
                <td class="px-6 py-4 text-right">
                  <a href="{{ route('ticket.show', $ticket) }}" class="inline-flex items-center gap-1 text-xs font-bold text-telkom-600 hover:text-telkom-700 dark:text-telkom-400">
                    Buka Percakapan <i data-lucide="chevron-right" class="w-4 h-4"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800">
        {{ $tickets->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
