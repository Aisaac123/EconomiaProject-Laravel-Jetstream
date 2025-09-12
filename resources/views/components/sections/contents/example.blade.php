@props([
    'title' => 'Ejemplo',
    'description' => '',
    'solution' => ''
])

<div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow">
    <p class="text-black dark:text-white font-semibold mb-2">
        {{ $title }}
    </p>
    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
        {{ $description }}
        <br>
        <span class="block mt-2 font-mono text-primary-600 dark:text-primary-300 text-lg">
            {{ $solution }}
        </span>
    </p>
</div>
