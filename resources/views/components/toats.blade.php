{{--
  ╔══════════════════════════════════════════════════════════╗
  ║  Toast Component — Reusable                              ║
  ║  Usage  : showToast('Pesan', 'success')                  ║
  ║  Types  : success | error | info | warning               ║
  ╚══════════════════════════════════════════════════════════╝
--}}

{{-- Toast Container: fixed kanan atas --}}
<div id="toastContainer" class="fixed top-4 right-4 z-[9999] flex flex-col gap-2.5 pointer-events-none" aria-live="polite"
  aria-atomic="false">
</div>

<style>
  @keyframes toastIn {
    from {
      opacity: 0;
      transform: translateX(110%);
    }

    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes toastOut {
    from {
      opacity: 1;
      transform: translateX(0);
    }

    to {
      opacity: 0;
      transform: translateX(110%);
    }
  }

  .toast-item {
    animation: toastIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    pointer-events: all;
  }

  .toast-item.toast-hiding {
    animation: toastOut 0.3s ease-in forwards;
  }
</style>

<script>
  /**
   * showToast — Tampilkan toast notification
   * @param {string} msg      - Pesan yang ditampilkan
   * @param {string} type     - 'success' | 'error' | 'info' | 'warning'
   * @param {number} duration - Durasi tampil dalam ms (default: 3000)
   */
  window.showToast = function(msg, type = 'success', duration = 3000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const config = {
      success: {
        bg: 'bg-emerald-500',
        border: 'border-emerald-600',
        icon: `<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>`,
      },
      error: {
        bg: 'bg-red-500',
        border: 'border-red-600',
        icon: `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`,
      },
      info: {
        bg: 'bg-blue-500',
        border: 'border-blue-600',
        icon: `<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>`,
      },
      warning: {
        bg: 'bg-amber-500',
        border: 'border-amber-600',
        icon: `<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>`,
      },
    };

    const c = config[type] || config.info;

    const toast = document.createElement('div');
    toast.className = [
      'toast-item',
      c.bg, 'border', c.border,
      'text-white',
      'px-4 py-3',
      'rounded-2xl',
      'shadow-xl shadow-black/20',
      'flex items-center gap-3',
      'text-sm font-semibold',
      'min-w-[220px] max-w-xs',
    ].join(' ');
    toast.setAttribute('role', 'alert');

    toast.innerHTML = `
      <svg class="w-5 h-5 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        ${c.icon}
      </svg>
      <span class="flex-1 leading-snug">${msg}</span>
      <button
        class="shrink-0 p-0.5 opacity-70 hover:opacity-100 transition rounded-lg"
        onclick="_dismissToast(this.closest('.toast-item'))"
        aria-label="Tutup">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    `;

    container.appendChild(toast);

    // Auto dismiss
    let timer = setTimeout(() => _dismissToast(toast), duration);

    // Pause on hover
    toast.addEventListener('mouseenter', () => clearTimeout(timer));
    toast.addEventListener('mouseleave', () => {
      timer = setTimeout(() => _dismissToast(toast), 1200);
    });
  };

  window._dismissToast = function(el) {
    if (!el || el.classList.contains('toast-hiding')) return;
    el.classList.add('toast-hiding');
    el.addEventListener('animationend', () => el.remove(), {
      once: true
    });
  };
</script>
