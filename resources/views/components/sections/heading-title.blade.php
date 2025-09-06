@props([
    'icon',
    'title',
    'quote',
    'buttonText' => 'Comenzar',
    'href' => '#'
])

<div class="relative bg-gradient-to-r from-primary-500 via-primary-800 to-primary-800 dark:from-primary-700 dark:via-primary-800 dark:to-primary-900 rounded-2xl p-8 mb-10 text-center shadow-xl overflow-hidden">
    <div class="absolute inset-0 bg-grid-white/10 bg-[size:40px_40px]"></div>
    <div class="relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6">
            {{ $icon ?? '' }}
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            {{ $title }}
        </h1>

        <p class="text-xl text-white/90 max-w-3xl mx-auto">
            {{ $quote }}
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="{{ $href }}">
                <button class="bg-white text-primary-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg">
                    {{ $buttonText }}
                </button>
            </a>
        </div>

        {{-- Slot opcional para contenido extra abajo --}}
        @isset($footer)
            <div class="mt-6">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
