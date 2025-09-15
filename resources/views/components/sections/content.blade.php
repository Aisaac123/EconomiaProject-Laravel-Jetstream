@props(['class' => 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 space-y-6',
        'collapsed' => false,
        'isCollapsible' => true
])

<div class="space-y-2" x-data="{ open: {{ $collapsed ? 'false' : 'true' }} }">
    @if($isCollapsible)
        <div
            type="button"
            @click="open = !open"
            class="p-2 transition-colors duration-200 cursor-pointer text-gray-700 dark:text-gray-300"
            :class="{
                'hover:text-primary-700 dark:hover:text-primary-300': !open,
                'text-primary-700 dark:text-primary-300': open,
                'hover:text-gray-500 dark:hover:text-gray-400': open
            }"
            :aria-expanded="open"
        >
            <div class="flex items-center justify-between">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    class="size-7 mx-auto text-primary transform transition-all duration-500 ease-out"
                    :class="{
        'rotate-180 scale-110': !open,
        'hover:scale-105': open
    }"
                    role="img"
                    aria-hidden="true"
                >
                    <title>Toggle chevron</title>

                    <defs>
                        <!-- Gradiente solo primary/teal -->
                        <linearGradient id="primaryOnly" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:rgb(20, 184, 166);stop-opacity:1">
                                <animate attributeName="stop-color"
                                         values="rgb(20, 184, 166);rgb(13, 148, 136);rgb(15, 118, 110);rgb(20, 184, 166)"
                                         dur="4s"
                                         repeatCount="indefinite"/>
                            </stop>
                            <stop offset="100%" style="stop-color:rgb(13, 148, 136);stop-opacity:1">
                                <animate attributeName="stop-color"
                                         values="rgb(13, 148, 136);rgb(15, 118, 110);rgb(20, 184, 166);rgb(13, 148, 136)"
                                         dur="4s"
                                         repeatCount="indefinite"/>
                            </stop>
                        </linearGradient>

                        <!-- Filtro de brillo -->
                        <filter id="softGlow" x="-50%" y="-50%" width="200%" height="200%">
                            <feDropShadow dx="0" dy="0" stdDeviation="2" flood-color="rgb(20, 184, 166)" flood-opacity="0.4"/>
                        </filter>

                        <!-- Patrón de ondas -->
                        <pattern id="wavePattern" x="0" y="0" width="8" height="4" patternUnits="userSpaceOnUse">
                            <path d="M0,2 Q2,0 4,2 T8,2" stroke="rgb(20, 184, 166)" stroke-width="0.5" fill="none" opacity="0.2">
                                <animate attributeName="opacity" values="0.1;0.3;0.1" dur="3s" repeatCount="indefinite"/>
                            </path>
                        </pattern>
                    </defs>

                    <!-- Fondo con ondas -->
                    <rect x="2" y="2" width="20" height="20" rx="10"
                          fill="url(#wavePattern)"
                          opacity="0.08">
                        <animate attributeName="opacity"
                                 values="0.05;0.15;0.05"
                                 dur="3s"
                                 repeatCount="indefinite"/>
                        <animateTransform
                            attributeName="transform"
                            type="rotate"
                            values="0 12 12;360 12 12"
                            dur="12s"
                            repeatCount="indefinite"/>
                    </rect>

                    <!-- FLECHA HACIA ARRIBA - Elemento principal -->
                    <path d="M7 14l5-5 5 5"
                          stroke="url(#primaryOnly)"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="3"
                          filter="url(#softGlow)"
                          class="transition-all duration-300 ease-out">

                        <!-- Morphing sutil de la flecha -->
                        <animate attributeName="d"
                                 values="M7 14l5-5 5 5;M6.5 14.5l5.5-5.5 5.5 5.5;M7 14l5-5 5 5"
                                 dur="3s"
                                 repeatCount="indefinite"/>

                    </path>

                    <!-- Ondas sutiles que solo aparecen cuando está rotado (expandido) -->
                    <g opacity="0" class="transition-opacity duration-700" :class="{ 'opacity-100': !open }">
                        <!-- Primera onda que se expande desde el centro -->
                        <circle cx="12" cy="12" r="0"
                                fill="none"
                                stroke="rgb(20, 184, 166)"
                                stroke-width="1"
                                opacity="0">
                            <animate attributeName="r"
                                     values="0;12;12"
                                     dur="5s"
                                     repeatCount="indefinite"/>
                            <animate attributeName="opacity"
                                     values="0.6;0.2;0"
                                     dur="5s"
                                     repeatCount="indefinite"/>
                            <animate attributeName="stroke-width"
                                     values="1;0.3;0.3"
                                     dur="5s"
                                     repeatCount="indefinite"/>
                        </circle>

                        <!-- Segunda onda con delay de 1s -->
                        <circle cx="12" cy="12" r="0"
                                fill="none"
                                stroke="rgb(13, 148, 136)"
                                stroke-width="1"
                                opacity="0">
                            <animate attributeName="r"
                                     values="0;12;12"
                                     dur="5s"
                                     repeatCount="indefinite"
                                     begin="1s"/>
                            <animate attributeName="opacity"
                                     values="0.6;0.2;0"
                                     dur="5s"
                                     repeatCount="indefinite"
                                     begin="1s"/>
                            <animate attributeName="stroke-width"
                                     values="1;0.3;0.3"
                                     dur="5s"
                                     repeatCount="indefinite"
                                     begin="1s"/>
                        </circle>

                        <!-- Tercera onda con delay de 2s -->
                        <circle cx="12" cy="12" r="0"
                                fill="none"
                                stroke="rgb(15, 118, 110)"
                                stroke-width="1"
                                opacity="0">
                            <animate attributeName="r"
                                     values="0;12;12"
                                     dur="5s"
                                     repeatCount="indefinite"
                                     begin="2s"/>
                            <animate attributeName="opacity"
                                     values="0.6;0.2;0"
                                     dur="5s"
                                     repeatCount="indefinite"
                                     begin="2s"/>
                            <animate attributeName="stroke-width"
                                     values="1;0.3;0.3"
                                     dur="5s"
                                     repeatCount="indefinite"
                                     begin="2s"/>
                        </circle>
                    </g>
                </svg>
            </div>
        </div>
    @endif

    <div
        x-show="open"
        x-collapse
    >
        <div {{ $attributes->merge(['class' => $class]) }}>
            {{ $slot }}
        </div>
    </div>
</div>


