<x-modal id="globalConfirmDeleteModal" size="sm" title="Konfirmasi Penghapusan">
  <div class="space-y-4 text-center pb-2">
    <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center mb-4">
      <i data-lucide="trash-2" class="w-8 h-8"></i>
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
        <button type="submit" class="w-full px-4 py-2 gradient-telkom text-white rounded-xl font-semibold hover:opacity-90 transition shadow-md shadow-telkom-600/20">Ya, Hapus</button>
      </form>
    </div>
  </x-slot>
</x-modal>

@pushonce('scripts')
<script>
  window.confirmDelete = function(urlOrId, itemName = 'data ini') {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Konfirmasi Penghapusan',
        text: `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
          popup: 'rounded-3xl dark:bg-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-800 shadow-2xl p-6',
          confirmButton: 'px-4 py-2.5 rounded-xl text-sm font-semibold bg-telkom-600 hover:bg-telkom-700 text-white transition shadow-sm',
          cancelButton: 'px-4 py-2.5 rounded-xl text-sm font-semibold bg-gray-150 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-750 text-gray-700 dark:text-gray-300 mr-2 transition'
        },
        buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById('globalConfirmDeleteForm');
          if (typeof urlOrId === 'string' && (urlOrId.startsWith('http') || urlOrId.includes('/'))) {
            if (form) {
              form.action = urlOrId;
              form.submit();
            }
          } else if (document.getElementById('delete-form-' + urlOrId)) {
            document.getElementById('delete-form-' + urlOrId).submit();
          } else if (document.getElementById('deleteForm')) {
            const df = document.getElementById('deleteForm');
            df.action = `/presence/${urlOrId}`;
            df.submit();
          } else if (form) {
            form.action = urlOrId;
            form.submit();
          }
        }
      });
      return;
    }

    const form = document.getElementById('globalConfirmDeleteForm');
    const nameEl = document.getElementById('confirmDeleteName');
    
    if (form && nameEl) {
      form.action = urlOrId;
      nameEl.innerText = itemName;
      openModal('globalConfirmDeleteModal');
    }
  };
</script>
@endpushonce
