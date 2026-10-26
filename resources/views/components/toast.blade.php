{{--
  ╔══════════════════════════════════════════════════════════╗
  ║  Toast Component — Reusable                              ║
  ║  Usage  : showToast('Pesan', 'success')                  ║
  ║  Types  : success | error | info | warning               ║
  ╚══════════════════════════════════════════════════════════╝
--}}

<div id="toastContainer" class="fixed top-5 right-5 z-[10000] flex flex-col gap-3 pointer-events-none" aria-live="polite" aria-atomic="false"></div>

<style>
  @keyframes toastSlideIn {
    0% { opacity: 0; transform: translateX(100%) scale(0.9); }
    100% { opacity: 1; transform: translateX(0) scale(1); }
  }

  @keyframes toastSlideOut {
    0% { opacity: 1; transform: translateX(0) scale(1); }
    100% { opacity: 0; transform: translateX(110%) scale(0.9); }
  }

  @keyframes toastProgress {
    0% { width: 100%; }
    100% { width: 0%; }
  }

  .toast-item {
    animation: toastSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    pointer-events: all;
  }

  .toast-item.toast-hiding {
    animation: toastSlideOut 0.3s ease-in forwards;
  }

  .toast-progress {
    animation: toastProgress linear forwards;
  }
</style>

<script>
  window.showToast = function(msg, type = 'success', duration = 3500) {
    if (typeof Swal !== 'undefined') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: duration,
        timerProgressBar: true,
        customClass: {
          popup: 'rounded-2xl border border-gray-200 dark:border-gray-800 shadow-xl dark:bg-gray-900 dark:text-gray-100 text-xs font-semibold'
        },
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
      });
      return Toast.fire({
        icon: type,
        title: msg
      });
    }

    const container = document.getElementById('toastContainer');
    if (!container) return;

    const config = {
      success: {
        bg: 'bg-emerald-50/90 dark:bg-emerald-950/80',
        border: 'border-emerald-200 dark:border-emerald-800/50',
        text: 'text-emerald-800 dark:text-emerald-200',
        iconBg: 'bg-emerald-500',
        progressBg: 'bg-emerald-500',
        iconName: 'check-circle-2',
      },
      error: {
        bg: 'bg-red-50/90 dark:bg-red-950/80',
        border: 'border-red-200 dark:border-red-800/50',
        text: 'text-red-800 dark:text-red-200',
        iconBg: 'bg-red-500',
        progressBg: 'bg-red-500',
        iconName: 'x-circle',
      },
      info: {
        bg: 'bg-blue-50/90 dark:bg-blue-950/80',
        border: 'border-blue-200 dark:border-blue-800/50',
        text: 'text-blue-800 dark:text-blue-200',
        iconBg: 'bg-blue-500',
        progressBg: 'bg-blue-500',
        iconName: 'info',
      },
      warning: {
        bg: 'bg-amber-50/90 dark:bg-amber-950/80',
        border: 'border-amber-200 dark:border-amber-800/50',
        text: 'text-amber-800 dark:text-amber-200',
        iconBg: 'bg-amber-500',
        progressBg: 'bg-amber-500',
        iconName: 'alert-triangle',
      },
    };

    const c = config[type] || config.info;
    const toast = document.createElement('div');
    
    toast.className = [
      'toast-item relative overflow-hidden',
      c.bg, 'border', c.border, 'backdrop-blur-md',
      'rounded-2xl shadow-lg shadow-black/5 dark:shadow-black/20',
      'flex items-start gap-3 p-4',
      'min-w-[280px] max-w-sm'
    ].join(' ');
    toast.setAttribute('role', 'alert');

    toast.innerHTML = `
      <div class="${c.iconBg} shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white shadow-sm mt-0.5">
        <i data-lucide="${c.iconName}" class="w-4 h-4"></i>
      </div>
      <div class="flex-1 min-w-0 pr-4">
        <p class="${c.text} text-sm font-bold tracking-tight mb-0.5 capitalize">${type}</p>
        <p class="${c.text} text-sm font-medium leading-snug opacity-90">${msg}</p>
      </div>
      <button class="absolute top-4 right-4 p-1 rounded-lg ${c.text} opacity-50 hover:opacity-100 hover:bg-black/5 dark:hover:bg-white/10 transition-colors" onclick="window._dismissToast(this.closest('.toast-item'))">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
      <div class="absolute bottom-0 left-0 h-1 ${c.progressBg} opacity-20 w-full"></div>
      <div class="absolute bottom-0 left-0 h-1 ${c.progressBg} toast-progress" style="animation-duration: ${duration}ms;"></div>
    `;

    container.appendChild(toast);
    if (window.createIcons) window.createIcons();

    let timer = setTimeout(() => window._dismissToast(toast), duration);

    toast.addEventListener('mouseenter', () => {
      clearTimeout(timer);
      const progress = toast.querySelector('.toast-progress');
      if (progress) progress.style.animationPlayState = 'paused';
    });
    
    toast.addEventListener('mouseleave', () => {
      timer = setTimeout(() => window._dismissToast(toast), 1000);
      const progress = toast.querySelector('.toast-progress');
      if (progress) {
        progress.style.animationDuration = '1000ms';
        progress.style.animationPlayState = 'running';
      }
    });
  };

  window._dismissToast = function(el) {
    if (!el || el.classList.contains('toast-hiding')) return;
    el.classList.add('toast-hiding');
    el.addEventListener('animationend', () => el.remove(), { once: true });
  };

  // Global window.alert override with SweetAlert2
  window.alert = function(msg) {
    if (typeof Swal !== 'undefined') {
      let icon = 'info';
      let title = 'Informasi';
      const lower = String(msg).toLowerCase();
      if (lower.includes('error') || lower.includes('gagal') || lower.includes('ditolak') || lower.includes('tidak aman')) {
        icon = 'error';
        title = 'Terjadi Kesalahan';
      } else if (lower.includes('peringatan') || lower.includes('perhatian') || lower.includes('kedaluwarsa') || lower.includes('pilih')) {
        icon = 'warning';
        title = 'Perhatian';
      } else if (lower.includes('berhasil') || lower.includes('sukses')) {
        icon = 'success';
        title = 'Berhasil';
      }

      return Swal.fire({
        icon: icon,
        title: title,
        text: msg,
        confirmButtonText: 'Mengerti',
        customClass: {
          popup: 'rounded-3xl dark:bg-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-800 shadow-2xl p-6',
          confirmButton: 'px-5 py-2.5 rounded-xl text-sm font-semibold bg-telkom-600 hover:bg-telkom-700 text-white transition shadow-sm'
        },
        buttonsStyling: false
      });
    }
  };

  // Intercept forms using onsubmit confirm(...)
  document.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form || form.dataset.swalBypassed) return;
    const onsubmitAttr = form.getAttribute('onsubmit');
    if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
      e.preventDefault();
      e.stopImmediatePropagation();
      const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
      const text = match ? match[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Konfirmasi',
          text: text,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Lanjutkan',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            popup: 'rounded-3xl dark:bg-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-800 shadow-2xl p-6',
            confirmButton: 'px-4 py-2.5 rounded-xl text-sm font-semibold bg-telkom-600 hover:bg-telkom-700 text-white transition shadow-sm',
            cancelButton: 'px-4 py-2.5 rounded-xl text-sm font-semibold bg-gray-150 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-750 text-gray-700 dark:text-gray-300 mr-2 transition'
          },
          buttonsStyling: false
        }).then((res) => {
          if (res.isConfirmed) {
            form.dataset.swalBypassed = 'true';
            form.submit();
          }
        });
      } else {
        if (window.confirm(text)) {
          form.dataset.swalBypassed = 'true';
          form.submit();
        }
      }
    }
  }, true);

  document.addEventListener('DOMContentLoaded', () => {
    @if(session('success')) showToast("{!! addslashes(session('success')) !!}", 'success'); @endif
    @if(session('status')) showToast("{!! addslashes(session('status')) !!}", 'success'); @endif
    @if(session('error')) showToast("{!! addslashes(session('error')) !!}", 'error'); @endif
    @if(session('info')) showToast("{!! addslashes(session('info')) !!}", 'info'); @endif
    @if(session('warning')) showToast("{!! addslashes(session('warning')) !!}", 'warning'); @endif
  });
</script>
