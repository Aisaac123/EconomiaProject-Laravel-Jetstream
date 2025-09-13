<div>
    <h3 class="font-medium dark:text-primary-300 mb-2 text-primary-700">
        {{ $formula_title ?? 'Fórmulas de apoyo' }}
    </h3>

    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
        {{ $formula_slot }}
    </div>
    <h3 class="font-medium dark:text-primary-300 my-2 text-primary-700">
        {{ $var_title ?? 'Variables' }}
    </h3>

    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
        {{ $var_slot }}
    </div>
</div>
