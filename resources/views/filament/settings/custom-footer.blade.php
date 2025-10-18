<footer class="text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-950 py-4 mt-2 w-full mx-auto items-center text-center">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-xl font-semibold tracking-wide text-gray-800 dark:text-gray-200">{{ config('app.name') }}</h2>
        <p class="text-sm mt-2 opacity-90 text-gray-700 dark:text-gray-400">
            Tu calculadora financiera inteligente — precisa, rápida y hecha para ti.
        </p>
        <div class="mt-4 flex justify-center space-x-6 text-sm">
            <a href="#" class="text-slate-700 dark:text-slate-300 hover:underline hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Inicio</a>
            <a href="#" class="text-slate-700 dark:text-slate-300 hover:underline hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Acerca de</a>
            <a href="#" class="text-slate-700 dark:text-slate-300 hover:underline hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Contacto</a>
        </div>
        <p class="mt-5 text-xs opacity-75 text-slate-600 dark:text-slate-400">
            © {{ date('Y') }} SmartFinance. Todos los derechos reservados.
        </p>
    </div>
</footer>
