@props([
    'id',
    'title' => null,
    'subtitle' => null,
    'size' => 'lg',
    'showCloseButton' => true,
    'closeOnBackdrop' => true,
    'bodyClass' => '',
    'headerClass' => '',
    'footerClass' => 'mt-8 flex items-center gap-3',
])

@php
  $sizeClasses = [
      'sm' => 'max-w-md',
      'md' => 'max-w-lg',
      'lg' => 'max-w-2xl',
      'xl' => 'max-w-4xl',
  ];

  $sizeClass = $sizeClasses[$size] ?? 'max-w-lg';
@endphp

<div id="{{ $id }}"
  class="fixed inset-0 z-[100] hidden items-center justify-center opacity-0 transition-opacity duration-300"
  {{ $attributes }}>
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    @if ($closeOnBackdrop) onclick="closeModal('{{ $id }}')" @endif></div>

  <div id="{{ $id }}Content"
    class="relative bg-white dark:bg-gray-900 rounded-2xl w-full {{ $sizeClass }} mx-4 overflow-hidden transform scale-95 transition-transform duration-300">
    <div class="p-5 sm:p-6 flex flex-col h-full max-h-[90vh] overflow-y-auto">
      @if (isset($header) || !empty($title) || $showCloseButton)
        <div class="flex items-center justify-between mb-6">
          <div class="flex-1 {{ $headerClass }}">
            @if (isset($header))
              {{ $header }}
            @elseif(!empty($title))
              <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
              @if(!empty($subtitle))
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $subtitle }}</p>
              @endif
            @endif
          </div>

          @if ($showCloseButton)
            <button type="button" onclick="closeModal('{{ $id }}')"
              class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          @endif
        </div>
      @endif

      <div class="{{ $bodyClass }}">
        {{ $slot }}
      </div>

      @if (isset($footer))
        <div class="{{ $footerClass }}">
          {{ $footer }}
        </div>
      @endif
    </div>
  </div>
</div>

@pushonce('scripts')
<script>
  window.openModal = function(id) {
    const modal = document.getElementById(id);
    const content = document.getElementById(`${id}Content`);

    if (!modal || !content) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
      modal.classList.remove('opacity-0');
      content.classList.remove('scale-95');
    }, 10);
  }

  window.closeModal = function(id) {
    const modal = document.getElementById(id);
    const content = document.getElementById(`${id}Content`);

    if (!modal || !content) return;

    modal.classList.add('opacity-0');
    content.classList.add('scale-95');

    setTimeout(() => {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }, 300);
  }
</script>
@endpushonce
