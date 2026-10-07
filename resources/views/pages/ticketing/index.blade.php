@extends('layouts.app-layout')

@section('title', 'Pusat Bantuan & e-Ticket PuTI')
@section('subtitle', 'Sistem pelaporan kendala IT dan pusat bantuan terpadu PuTI Telkom University Surabaya')

@section('content')
<div class="space-y-6">

  <!-- Top Action Bar -->
  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm">
    <div>
      <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">Selamat Datang di Helpdesk PuTI</h2>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sampaikan keluhan kendala teknis jaringan, akun kampus, atau fasilitas IT Anda.</p>
    </div>
    <div class="flex items-center gap-3 w-full sm:w-auto">
      <a href="{{ route('ticket.luna') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-750 text-gray-800 dark:text-gray-200 text-sm font-semibold transition">
        <i data-lucide="bot" class="w-4 h-4 text-telkom-600 dark:text-telkom-400"></i>
        <span>Tanya Luna AI</span>
      </a>
      <a href="{{ route('ticket.create') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-telkom-600 hover:bg-telkom-700 text-white text-sm font-semibold shadow-lg shadow-telkom-600/20 transition">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        <span>Buat Tiket Baru</span>
      </a>
    </div>
  </div>

  <!-- KPI Stats Grid -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <!-- Total -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase">
        <span>Total Tiket</span>
        <i data-lucide="ticket" class="w-4 h-4 text-gray-400"></i>
      </div>
      <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
    </div>

    <!-- Open -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="flex items-center justify-between text-blue-600 dark:text-blue-400 text-xs font-semibold uppercase">
        <span>Diterima</span>
        <i data-lucide="inbox" class="w-4 h-4"></i>
      </div>
      <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['open'] }}</p>
    </div>

    <!-- In Progress -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="flex items-center justify-between text-purple-600 dark:text-purple-400 text-xs font-semibold uppercase">
        <span>Ditangani</span>
        <i data-lucide="clock" class="w-4 h-4"></i>
      </div>
      <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['in_progress'] }}</p>
    </div>

    <!-- Pending User -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="flex items-center justify-between text-amber-600 dark:text-amber-400 text-xs font-semibold uppercase">
        <span>Butuh Info</span>
        <i data-lucide="alert-circle" class="w-4 h-4"></i>
      </div>
      <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['pending_user'] }}</p>
    </div>

    <!-- Waiting Central -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="flex items-center justify-between text-orange-600 dark:text-orange-400 text-xs font-semibold uppercase">
        <span>Koor. Pusat</span>
        <i data-lucide="network" class="w-4 h-4"></i>
      </div>
      <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['waiting_central'] }}</p>
    </div>

    <!-- Resolved -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
      <div class="flex items-center justify-between text-emerald-600 dark:text-emerald-400 text-xs font-semibold uppercase">
        <span>Selesai</span>
        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
      </div>
      <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['resolved'] }}</p>
    </div>
  </div>

  <!-- Quick Shortcut Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="{{ route('ticket.my-tickets') }}" class="group flex items-center justify-between p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-telkom-500 dark:hover:border-telkom-500 transition shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-telkom-50 dark:bg-telkom-950/50 text-telkom-600 dark:text-telkom-400 flex items-center justify-center">
          <i data-lucide="user-check" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-telkom-600 transition">Tiket Saya</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Lihat progres dan tanggapi pesan teknisi pada tiket yang Anda ajukan.</p>
        </div>
      </div>
      <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

    @if($isStaff)
    <a href="{{ route('ticket.tasks') }}" class="group flex items-center justify-between p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-purple-500 dark:hover:border-purple-500 transition shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center">
          <i data-lucide="clipboard-list" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-purple-600 transition">NOC Command Center (Tugas Staf)</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola antrean tiket masuk, update penanganan, dan aktifkan audio alert.</p>
        </div>
      </div>
      <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform"></i>
    </a>
    @else
    <a href="{{ route('ticket.history') }}" class="group flex items-center justify-between p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 transition shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i data-lucide="history" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition">Riwayat Tiket</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Cari kembali solusi dan catatan kendala IT yang pernah diselesaikan.</p>
        </div>
      </div>
      <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform"></i>
    </a>
    @endif
  </div>

  <!-- Recent Tickets Table -->
  <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
      <h3 class="font-bold text-lg text-gray-900 dark:text-white">Laporan Tiket Terbaru</h3>
      <a href="{{ route('ticket.my-tickets') }}" class="text-xs font-semibold text-telkom-600 dark:text-telkom-400 hover:underline">Lihat Semua &rarr;</a>
    </div>

    @if($recentTickets->isEmpty())
      <div class="p-12 text-center">
        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mx-auto mb-3">
          <i data-lucide="inbox" class="w-6 h-6"></i>
        </div>
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum ada tiket bantuan yang tercatat.</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jika Anda mengalami kendala teknis, silakan buat tiket baru.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-400 text-xs font-semibold uppercase">
            <tr>
              <th class="px-6 py-3.5">No. Tiket</th>
              <th class="px-6 py-3.5">Judul Kendala</th>
              <th class="px-6 py-3.5">Kategori Layanan</th>
              <th class="px-6 py-3.5">Status</th>
              <th class="px-6 py-3.5">Teknisi</th>
              <th class="px-6 py-3.5">Waktu Lapor</th>
              <th class="px-6 py-3.5 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
            @foreach($recentTickets as $ticket)
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
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    {{ $ticket->service_item }}
                  </span>
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
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeStyle }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $ticket->user_status_label }}
                  </span>
                </td>
                <td class="px-6 py-4 text-xs">
                  {{ $ticket->assignee?->name ?? 'Menunggu Staf' }}
                </td>
                <td class="px-6 py-4 text-xs text-gray-500">
                  {{ $ticket->created_at->diffForHumans() }}
                </td>
                <td class="px-6 py-4 text-right">
                  <a href="{{ route('ticket.show', $ticket) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-telkom-600 hover:text-telkom-700 dark:text-telkom-400">
                    Buka Detail <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
@endsection
