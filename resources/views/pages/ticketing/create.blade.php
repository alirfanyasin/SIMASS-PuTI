@extends('layouts.app-layout')

@section('title', 'Buat Tiket Bantuan Baru')
@section('subtitle', 'Laporkan kendala teknis atau permohonan bantuan IT ke tim PuTI Surabaya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Back Link -->
  <div>
    <a href="{{ route('ticket.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 dark:hover:text-white transition">
      <i data-lucide="arrow-left" class="w-4 h-4"></i>
      <span>Kembali ke Pusat Bantuan</span>
    </a>
  </div>

  <!-- Form Container -->
  <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8 shadow-sm">
    <div class="border-b border-gray-100 dark:border-gray-800 pb-6 mb-6">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Formulir Pengajuan Tiket</h2>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi formulir berikut secara ringkas dan jelas agar teknisi kami dapat segera menindaklanjuti.</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-400 text-sm">
        <p class="font-bold mb-1 flex items-center gap-1.5">
          <i data-lucide="alert-triangle" class="w-4 h-4"></i> Mohon periksa kembali isian form Anda:
        </p>
        <ul class="list-disc list-inside space-y-0.5 text-xs">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Luna Live Recommender Card (Dynamic) -->
    <div id="lunaSuggestionCard" class="hidden mb-6 p-4 rounded-2xl bg-telkom-50/70 dark:bg-telkom-950/30 border border-telkom-200 dark:border-telkom-900/50 flex items-start gap-3 transition">
      <div class="w-9 h-9 rounded-xl bg-telkom-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
        <i data-lucide="bot" class="w-5 h-5"></i>
      </div>
      <div class="flex-1 text-xs">
        <p class="font-bold text-telkom-900 dark:text-telkom-200 flex items-center gap-1.5">
          <span>Saran Asisten Luna AI</span>
          <span class="text-[10px] px-2 py-0.5 rounded-full bg-telkom-100 dark:bg-telkom-900 text-telkom-700 dark:text-telkom-300 font-semibold">Rekomendasi</span>
        </p>
        <p class="text-gray-600 dark:text-gray-300 mt-0.5" id="lunaSuggestionText"></p>
        <button type="button" id="btnApplySuggestion" class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white font-semibold shadow-sm transition">
          <i data-lucide="check" class="w-3.5 h-3.5"></i> Terapkan Kategori Ini
        </button>
      </div>
    </div>

    <form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <!-- 1. Judul Kendala -->
      <div>
        <label for="title" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
          Judul Kendala / Permohonan <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" id="title" required value="{{ old('title') }}"
          placeholder="Contoh: Wi-Fi putus-putus di Ruang Lab A201"
          class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition">
        <p class="text-xs text-gray-500 mt-1">Tulis ringkasan singkat kendala yang Anda alami.</p>
      </div>

      <!-- 2. Kategori & Layanan Dropdown -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="category" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
            Kategori Kendala <span class="text-red-500">*</span>
          </label>
          <select name="category" id="category" required
            class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition">
            <option value="">-- Pilih Kategori --</option>
            @foreach($services as $catName => $items)
              <option value="{{ $catName }}" {{ old('category') === $catName ? 'selected' : '' }}>
                {{ $catName }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="service_item" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
            Layanan Spesifik <span class="text-red-500">*</span>
          </label>
          <select name="service_item" id="service_item" required
            class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition">
            <option value="">-- Pilih Layanan Spesifik --</option>
          </select>
        </div>
      </div>

      <!-- 3. Lokasi & Prioritas -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="location" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
            Lokasi Gedung / Ruangan
          </label>
          <input type="text" name="location" id="location" value="{{ old('location') }}"
            placeholder="Contoh: Gedung A Lantai 2, R.204"
            class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition">
          <p class="text-xs text-gray-500 mt-1">Sangat dianjurkan untuk kendala Wi-Fi, kabel LAN, atau perangkat fisik kelas.</p>
        </div>

        <div>
          <label for="priority" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
            Tingkat Urgensi <span class="text-red-500">*</span>
          </label>
          <select name="priority" id="priority" required
            class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition">
            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah (Low — Penanganan 48 Jam)</option>
            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Biasa (Medium — Penanganan 24 Jam)</option>
            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi (High — Penanganan 12 Jam)</option>
            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Mendesak / Kritis (Urgent — Penanganan 4 Jam)</option>
          </select>
        </div>
      </div>

      <!-- 4. Deskripsi Kendala -->
      <div>
        <label for="description" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
          Deskripsi Lengkap Kendala <span class="text-red-500">*</span>
        </label>
        <textarea name="description" id="description" rows="5" required
          placeholder="Ceritakan detail kendala yang dialami, pesan error yang muncul, atau langkah apa yang sudah Anda coba..."
          class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500 transition leading-relaxed">{{ old('description') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Semakin lengkap informasi yang Anda berikan, semakin cepat teknisi dapat menyelesaikan kendala tanpa perlu bolak-balik bertanya.</p>
      </div>

      <!-- 5. Lampiran Bukti / Screenshot -->
      <div>
        <label for="attachment" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-1.5">
          Lampiran Foto / Tangkapan Layar (Opsional)
        </label>
        <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.webp,.pdf"
          class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-800 dark:file:text-gray-300 transition">
        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP, atau PDF. Maksimal ukuran 5 MB.</p>
      </div>

      <!-- Submit Button -->
      <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
        <a href="{{ route('ticket.index') }}" class="px-5 py-2.5 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition">
          Batal
        </a>
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-telkom-600 hover:bg-telkom-700 text-white text-sm font-semibold shadow-lg shadow-telkom-600/25 transition">
          <i data-lucide="send" class="w-4 h-4"></i>
          <span>Kirim Tiket Bantuan</span>
        </button>
      </div>

    </form>
  </div>

</div>

@push('scripts')
<script>
  const servicesCatalog = @json($services);
  const categorySelect = document.getElementById('category');
  const serviceItemSelect = document.getElementById('service_item');
  const titleInput = document.getElementById('title');
  const lunaCard = document.getElementById('lunaSuggestionCard');
  const lunaText = document.getElementById('lunaSuggestionText');
  const btnApply = document.getElementById('btnApplySuggestion');

  let currentSuggested = null;

  function populateServices(category, preselected = '') {
    serviceItemSelect.innerHTML = '<option value="">-- Pilih Layanan Spesifik --</option>';
    if (category && servicesCatalog[category]) {
      Object.keys(servicesCatalog[category]).forEach(item => {
        const opt = document.createElement('option');
        opt.value = item;
        opt.textContent = item;
        if (item === preselected) opt.selected = true;
        serviceItemSelect.appendChild(opt);
      });
    }
  }

  categorySelect.addEventListener('change', function() {
    populateServices(this.value);
  });

  // Prepopulate on initial load if old value exists
  const oldCategory = @json(old('category'));
  const oldService = @json(old('service_item'));
  if (oldCategory) {
    populateServices(oldCategory, oldService);
  }

  // Live Luna AI Suggestion on Typing Title
  let debounceTimeout = null;
  titleInput.addEventListener('input', function() {
    clearTimeout(debounceTimeout);
    const text = this.value.trim();
    if (text.length < 4) {
      lunaCard.classList.add('hidden');
      return;
    }

    debounceTimeout = setTimeout(() => {
      fetch('{{ route('ticket.luna-suggest') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ text })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success && data.suggestion) {
          currentSuggested = data.suggestion;
          lunaText.textContent = `Berdasarkan judul kendala Anda, sepertinya ini terkait kategori "${currentSuggested.category}" pada layanan "${currentSuggested.service_item}".`;
          lunaCard.classList.remove('hidden');
          if (typeof lucide !== 'undefined') lucide.createIcons();
        } else {
          lunaCard.classList.add('hidden');
        }
      })
      .catch(() => {});
    }, 400);
  });

  btnApply.addEventListener('click', function() {
    if (currentSuggested) {
      categorySelect.value = currentSuggested.category;
      populateServices(currentSuggested.category, currentSuggested.service_item);
      lunaCard.classList.add('hidden');
    }
  });
</script>
@endpush
@endsection
