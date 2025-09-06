<div>
    @if(isset($title))
        <h3 class="font-medium dark:text-white mb-2 text-primary-700">
            {{ $title }}
        </h3>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
