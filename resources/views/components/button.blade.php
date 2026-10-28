@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $baseClass = 'inline-flex items-center justify-center gap-1.5 transition select-none disabled:opacity-50 disabled:pointer-events-none';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs font-semibold rounded-xl',
        'table' => 'px-3 py-1.5 text-xs font-semibold rounded-xl',
        'md' => 'px-4 py-2.5 text-xs font-bold rounded-xl',
        'filter' => 'px-5 py-2.5 text-xs font-semibold rounded-xl',
        'lg' => 'px-6 py-3 text-sm font-bold rounded-xl',
    ];

    $variantClasses = [
        'primary' => 'gradient-telkom text-white shadow-md hover:opacity-95',
        'secondary' => 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300',
        'ghost' => 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-xs',
        'table' => 'bg-gray-100 hover:bg-telkom-600 hover:text-white dark:bg-gray-800 dark:hover:bg-telkom-600 text-gray-700 dark:text-gray-300 shadow-xs',
        'table-danger' => 'bg-red-50 hover:bg-red-600 text-red-600 hover:text-white dark:bg-red-950/40 dark:hover:bg-red-600 dark:text-red-400 shadow-xs',
        'table-primary' => 'bg-telkom-50 hover:bg-telkom-600 text-telkom-700 hover:text-white dark:bg-telkom-950/40 dark:hover:bg-telkom-600 dark:text-telkom-400 shadow-xs',
        'table-amber' => 'bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white dark:bg-amber-950/40 dark:hover:bg-amber-600 dark:text-amber-400 shadow-xs',
        'table-blue' => 'bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white dark:bg-blue-950/40 dark:hover:bg-blue-600 dark:text-blue-400 shadow-xs',
    ];

    $iconSizes = [
        'sm' => 'w-3.5 h-3.5 shrink-0',
        'table' => 'w-3.5 h-3.5 shrink-0',
        'md' => 'w-4 h-4 shrink-0',
        'filter' => 'w-4 h-4 shrink-0',
        'lg' => 'w-4 h-4 shrink-0',
    ];

    $resolvedSize = ($variant === 'table' || str_starts_with($variant, 'table-')) && $size === 'md' ? 'table' : $size;
    $sClass = $sizeClasses[$resolvedSize] ?? $sizeClasses['md'];
    $vClass = $variantClasses[$variant] ?? $variantClasses['primary'];
    $iClass = $iconSizes[$resolvedSize] ?? $iconSizes['md'];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClass $sClass $vClass"]) }}>
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="{{ $iClass }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClass $sClass $vClass"]) }}>
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="{{ $iClass }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif
