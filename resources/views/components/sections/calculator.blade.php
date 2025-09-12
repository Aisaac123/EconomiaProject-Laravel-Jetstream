<section id="{{ $id ?? 'calculadora' }}">
    <div class="grid grid-cols-12 gap-x-4 mt-4">
        {{-- Formulario --}}
        <div class="space-y-6 lg:col-span-9 col-span-12 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            {{ $form ?? '' }}
        </div>

        {{-- Explicación --}}
        <div class="self-start hidden lg:block bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 col-span-3">
            {{ $explanation ?? '' }}
        </div>
    </div>
</section>
