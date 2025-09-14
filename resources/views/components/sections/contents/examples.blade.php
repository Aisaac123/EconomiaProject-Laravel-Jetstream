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
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                class="w-6 h-6 text-primary transform transition-all duration-500 ease-out"
                :class="{
        'rotate-180 scale-110': open,
        'hover:scale-105': !open
    }"
                role="img"
                aria-hidden="true"
            >
                <title>Toggle chevron</title>

                <defs>
                    <!-- Gradiente usando colores primary/teal -->
                    <linearGradient id="primaryGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" class="text-primary-500" style="stop-color:rgb(20, 184, 166)">
                            <animate attributeName="stop-color"
                                     values="rgb(20, 184, 166);rgb(13, 148, 136);rgb(15, 118, 110);rgb(20, 184, 166)"
                                     dur="2s"
                                     repeatCount="indefinite"/>
                        </stop>
                        <stop offset="100%" class="text-primary-600" style="stop-color:rgb(13, 148, 136)">
                            <animate attributeName="stop-color"
                                     values="rgb(13, 148, 136);rgb(15, 118, 110);rgb(20, 184, 166);rgb(13, 148, 136)"
                                     dur="2s"
                                     repeatCount="indefinite"/>
                        </stop>
                    </linearGradient>

                    <!-- Filtro de resplandor -->
                    <filter id="primaryGlow" x="-50%" y="-50%" width="200%" height="200%">
                        <feDropShadow dx="0" dy="0" stdDeviation="2" flood-color="rgb(20, 184, 166)" flood-opacity="0.4"/>
                    </filter>
                </defs>

                <!-- Círculo de fondo que se expande al abrir -->
                <circle cx="12" cy="12" r="0"
                        fill="none"
                        stroke="url(#primaryGradient)"
                        stroke-width="1"
                        opacity="0"
                        class="transition-all duration-500 ease-out"
                        :class="{ 'animate-pulse': open }">
                    <animate attributeName="r"
                             values="0;11;0"
                             dur="0.6s"
                             begin="click"
                             fill="freeze"/>
                    <animate attributeName="opacity"
                             values="0;0.3;0"
                             dur="0.6s"
                             begin="click"
                             fill="freeze"/>
                </circle>

                <!-- Chevron principal con animación fluida -->
                <path d="M19 9l-7 7-7-7"
                      stroke="url(#primaryGradient)"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2.5"
                      filter="url(#primaryGlow)"
                      class="transition-all duration-300 ease-out">

                    <!-- Animación de pulsación cuando está cerrado -->
                    <animate attributeName="stroke-width"
                             values="2.5;3.5;2.5"
                             dur="2s"
                             repeatCount="indefinite"
                             :class="{ 'paused': open }"/>

                    <!-- Efecto de "morphing" en el path -->
                    <animateTransform
                        attributeName="transform"
                        attributeType="XML"
                        type="scale"
                        values="1;1.1;1"
                        dur="0.3s"
                        begin="click"
                        fill="freeze"/>
                </path>

                <!-- Líneas decorativas que aparecen al expandir -->
                <g opacity="0" class="transition-opacity duration-500" :class="{ 'opacity-60': open }">
                    <!-- Línea superior -->
                    <line x1="6" y1="6" x2="18" y2="6"
                          stroke="url(#primaryGradient)"
                          stroke-width="1"
                          stroke-linecap="round">
                        <animate attributeName="x2"
                                 values="6;18"
                                 dur="0.4s"
                                 begin="0s"
                                 fill="freeze"/>
                    </line>

                    <!-- Línea inferior -->
                    <line x1="6" y1="18" x2="18" y2="18"
                          stroke="url(#primaryGradient)"
                          stroke-width="1"
                          stroke-linecap="round">
                        <animate attributeName="x2"
                                 values="6;18"
                                 dur="0.4s"
                                 begin="0.2s"
                                 fill="freeze"/>
                    </line>
                </g>

                <!-- Efecto de ondas al hacer clic -->
                <circle cx="12" cy="12" r="0"
                        fill="none"
                        stroke="rgb(20, 184, 166)"
                        stroke-width="2"
                        opacity="0">
                    <animate attributeName="r"
                             values="0;15;20"
                             dur="0.5s"
                             begin="click"
                             fill="freeze"/>
                    <animate attributeName="opacity"
                             values="0.6;0.2;0"
                             dur="0.5s"
                             begin="click"
                             fill="freeze"/>
                    <animate attributeName="stroke-width"
                             values="2;1;0"
                             dur="0.5s"
                             begin="click"
                             fill="freeze"/>
                </circle>
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
