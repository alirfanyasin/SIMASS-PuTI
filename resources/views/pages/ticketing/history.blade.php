@extends('layouts.app-layout')

@section('title', 'Riwayat & Arsip Tiket')
@section('subtitle', 'Pencarian dan penelusuran histori penyelesaian tiket kendala IT PuTI Surabaya')

@section('content')
<div class="space-y-6">

  <!-- Search & Filter Card -->
  <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
    <form action="{{ route('ticket.history') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      
      <!-- Search keyword -->
      <div>
        <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Cari Kata Kunci</label>
        <div class="relative">
          <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="No. tiket, judul, lokasi..."
            class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs focus:ring-2 focus:ring-telkom-500">
          <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
        </div>
      </div>

      <!-- Filter Kategori -->
      <div>
        <label for="category" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Kategori Layanan</label>
        <select name="category" id="category" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs">
          <option value="">-- Semua Kategori --</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>

      <!-- Filter Status -->
      <div>
        <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Status Tiket</label>
        <select name="status" id="status" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs">
          <option value="">-- Semua Status --</option>
          <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai Ditangani (Resolved)</option>
          <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Tiket Ditutup (Closed)</option>
          <option value="waiting_central" {{ request('status') === 'waiting_central' ? 'selected' : '' }}>Koordinasi Pusat (Waiting Central)</option>
          <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Ditangani (In Progress)</option>
          <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Laporan Diterima (Open)</option>
        </select>
      </div>

      <!-- Actions -->
      <div class="flex items-end gap-2">
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white font-semibold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
          <i data-lucide="filter" class="w-3.5 h-3.5"></i>
          <span>Terapkan Filter</span>
        </button>
        <a href="{{ route('ticket.history') }}" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 text-xs font-semibold hover:bg-gray-50">
          Reset
        </a>
      </div>

    </form>
  </div>

  <!-- Table List -->
  <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
    @if($tickets->isEmpty())
      <div class="p-12 text-center">
        <div class="w-14 h-14 rounded-3xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mx-auto mb-3">
          <i data-lucide="search-x" class="w-7 h-7"></i>
        </div>
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Tidak ada riwayat ditemukan</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Coba sesuaikan kata kunci atau filter pencarian Anda.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-400 text-xs font-semibold uppercase">
            <tr>
              <th class="px-6 py-3.5">No. Tiket</th>
              <th class="px-6 py-3.5">Judul & Lokasi</th>
              <th class="px-6 py-3.5">Layanan</th>
              <th class="px-6 py-3.5">Status</th>
              <th class="px-6 py-3.5">Pelapor</th>
              <th class="px-6 py-3.5">Selesai / Update</th>
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
                <td class="px-6 py-4 text-xs">
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
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeStyle }}">
                    {{ $ticket->user_status_label }}
                  </span>
                </td>
                <td class="px-6 py-4 text-xs">
                  {{ $ticket->user->name }}
                </td>
                <td class="px-6 py-4 text-xs text-gray-500">
                  {{ $ticket->resolved_at ? $ticket->resolved_at->format('d M Y') : $ticket->updated_at->diffForHumans() }}
                </td>
                <td class="px-6 py-4 text-right">
                  <a href="{{ route('ticket.show', $ticket) }}" class="inline-flex items-center gap-1 text-xs font-bold text-telkom-600 hover:text-telkom-700 dark:text-telkom-400">
                    Buka <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
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
