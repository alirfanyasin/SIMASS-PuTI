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
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const config = {
      success: {
        bg: 'bg-emerald-50/90 dark:bg-emerald-950/80',
        border: 'border-emerald-200 dark:border-emerald-800/50',
        text: 'text-emerald-800 dark:text-emerald-200',
        iconBg: 'bg-emerald-500',
        progressBg: 'bg-emerald-500',
        icon: `<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>`,
      },
      error: {
        bg: 'bg-red-50/90 dark:bg-red-950/80',
        border: 'border-red-200 dark:border-red-800/50',
        text: 'text-red-800 dark:text-red-200',
        iconBg: 'bg-red-500',
        progressBg: 'bg-red-500',
        icon: `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`,
      },
      info: {
        bg: 'bg-blue-50/90 dark:bg-blue-950/80',
        border: 'border-blue-200 dark:border-blue-800/50',
        text: 'text-blue-800 dark:text-blue-200',
        iconBg: 'bg-blue-500',
        progressBg: 'bg-blue-500',
        icon: `<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>`,
      },
      warning: {
        bg: 'bg-amber-50/90 dark:bg-amber-950/80',
        border: 'border-amber-200 dark:border-amber-800/50',
        text: 'text-amber-800 dark:text-amber-200',
        iconBg: 'bg-amber-500',
        progressBg: 'bg-amber-500',
        icon: `<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>`,
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
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          ${c.icon}
        </svg>
      </div>
      <div class="flex-1 min-w-0 pr-4">
        <p class="${c.text} text-sm font-bold tracking-tight mb-0.5 capitalize">${type}</p>
        <p class="${c.text} text-sm font-medium leading-snug opacity-90">${msg}</p>
      </div>
      <button class="absolute top-4 right-4 p-1 rounded-lg ${c.text} opacity-50 hover:opacity-100 hover:bg-black/5 dark:hover:bg-white/10 transition-colors" onclick="window._dismissToast(this.closest('.toast-item'))">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <div class="absolute bottom-0 left-0 h-1 ${c.progressBg} opacity-20 w-full"></div>
      <div class="absolute bottom-0 left-0 h-1 ${c.progressBg} toast-progress" style="animation-duration: ${duration}ms;"></div>
    `;

    container.appendChild(toast);

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

  document.addEventListener('DOMContentLoaded', () => {
    @if(session('success')) showToast("{!! addslashes(session('success')) !!}", 'success'); @endif
    @if(session('status')) showToast("{!! addslashes(session('status')) !!}", 'success'); @endif
    @if(session('error')) showToast("{!! addslashes(session('error')) !!}", 'error'); @endif
    @if(session('info')) showToast("{!! addslashes(session('info')) !!}", 'info'); @endif
    @if(session('warning')) showToast("{!! addslashes(session('warning')) !!}", 'warning'); @endif
  });
</script>
