@props([
    'title' => 'Ejemplos prácticos',
    'advice' => null,
    'titleClass' => 'text-lg font-semibold',
    'bodyClass' => 'text-gray-700 dark:text-gray-300 pt-2 text-sm',
    'collapsed' => true
])
<div class="space-y-2" x-data="{ open: {{ $collapsed ? 'false' : 'true' }} }">
    <div
        type="button"
        @click="open = !open"
        class="p-2 transition-colors duration-200 cursor-pointer text-gray-700 dark:text-gray-300"
        :class="{
                'text-primary-700 dark:text-primary-300': open,
                'hover:text-primary-700 dark:hover:text-primary-300': !open,
                'hover:text-gray-500 dark:hover:text-gray-400': open
            }"
        :aria-expanded="open"
    >
        <div class="flex items-center justify-between">
            <h3 class="{{ $titleClass }}">{{ $title }}</h3>
            <svg
                class="w-5 h-5 transition-transform duration-200"
                :class="{ 'rotate-180': open }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"></path>
            </svg>
        </div>
    </div>

    <div
        x-show="open"
        x-collapse
        class="{{ $bodyClass }}"
    >
        <div class="bg-gray-100 dark:bg-gray-800 border-r-4 border-primary-700 dark:border-primary-400 rounded-lg p-4 space-y-2">
            {{ $slot }}
        </div>
        @if($advice)
            <p class="text-gray-700 dark:text-gray-300 mt-2">
                💡 Consejo: {{ $advice }}
            </p>
        @endif
    </div>
</div>
