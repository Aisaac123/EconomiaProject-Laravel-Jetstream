@props([
    'title' => 'Fórmula principal',
    'titleClass' => 'text-lg font-semibold text-primary-700 dark:text-primary-300',
    'bodyClass' => 'text-gray-700 dark:text-gray-300 pt-2 text-sm'
])
<div class="space-y-2">
    <h3 class="{{ $titleClass }}">{{ $title }}</h3>
    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
