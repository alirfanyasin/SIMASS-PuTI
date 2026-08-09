<x-modal id="globalConfirmDeleteModal" size="sm" title="Konfirmasi Penghapusan">
  <div class="space-y-4 text-center pb-2">
    <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center mb-4">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M3 6h18"/>
        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
        <line x1="10" y1="11" x2="10" y2="17"/>
        <line x1="14" y1="11" x2="14" y2="17"/>
      </svg>
    </div>
    <p class="text-gray-600 dark:text-gray-400 text-sm">
      Apakah Anda yakin ingin menghapus <strong id="confirmDeleteName" class="text-gray-900 dark:text-white"></strong>?<br>Tindakan ini tidak dapat dibatalkan.
    </p>
  </div>
  
  <x-slot name="footer">
    <div class="flex items-center gap-3 w-full">
      <button type="button" onclick="closeModal('globalConfirmDeleteModal')" class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</button>
      <form id="globalConfirmDeleteForm" method="POST" class="flex-1">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition">Ya, Hapus</button>
      </form>
    </div>
  </x-slot>
</x-modal>

@pushonce('scripts')
<script>
  window.confirmDelete = function(url, itemName = 'data ini') {
    const form = document.getElementById('globalConfirmDeleteForm');
    const nameEl = document.getElementById('confirmDeleteName');
    
    if (form && nameEl) {
      form.action = url;
      nameEl.innerText = itemName;
      openModal('globalConfirmDeleteModal');
    }
  };
</script>
@endpushonce
