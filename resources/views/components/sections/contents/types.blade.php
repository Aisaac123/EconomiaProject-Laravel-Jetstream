@props([
    'title' => 'Tipos',
    'titleClass' => 'text-lg font-semibold text-primary-700 dark:text-primary-300',
    'bodyClass' => 'text-gray-700 dark:text-gray-300'
])
<div class="space-y-2">
    <h3 class="{{ $titleClass }}">{{ $title }}</h3>
    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
