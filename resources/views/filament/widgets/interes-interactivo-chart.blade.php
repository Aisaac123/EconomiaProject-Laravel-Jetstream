<div
    class="space-y-6"
    x-data="{
        capital: 1000,
        tasa: 10,
        tiempo: 5,
        chartMain: null,
        chartDiff: null,

        // --- Colores fijos ---
        colors: { simple: '#10B981', compuesto: '#14B8A6', diferencial: '#0F9C95' },

        // --- Cálculos ---
        get simpleTotal() {
            const val = this.capital * (1 + (this.tasa / 100) * this.tiempo);
            return this.limitValue(val);
        },
        get compuestoTotal() {
            const val = this.capital * Math.pow(1 + this.tasa / 100, this.tiempo);
            return this.limitValue(val);
        },
        get simpleGanancia() {
            return this.simpleTotal === null ? '∞' : this.roundVal(this.simpleTotal - this.capital);
        },
        get compuestoGanancia() {
            return this.compuestoTotal === null ? '∞' : this.roundVal(this.compuestoTotal - this.capital);
        },
        get diferencia() {
            if (this.simpleTotal === null && this.compuestoTotal === null) return '∞';
            if (this.simpleTotal === null) return '∞';
            if (this.compuestoTotal === null) return '∞';
            return this.roundVal(this.compuestoTotal - this.simpleTotal);
        },

        // --- Funciones auxiliares ---
        roundVal(val) {
            return Math.round(val * 100) / 100;
        },
        formatCurrency(value) {
            if (value === null) return '∞';
            if (value === '<∞') return '<∞';
            return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(value);
        },
        limitValue(val) {
            if (!isFinite(val) || val >= 1e15) return null;
        if (val <= 1e-15) return 0; // evitar problemas con flotantes muy pequeños
        return val;
        },checkOverflow(val,compVal,type){ const decimals = val.toString().split('.')[1]||''; if(decimals.length>=15){ if(type==='simple'){ if(compVal&&val===compVal) return '∞'; return '<∞'; } return '∞'; } return this.roundVal(val); },

        // --- Inicialización Charts ---
        initMainChart(){
            const data = [];
            const maxPoints = 100;
            const step = this.tiempo>maxPoints?Math.ceil(this.tiempo/maxPoints):1;
            for(let i=1;i<=this.tiempo;i+=step){
                const simple=this.capital*(1+(this.tasa/100)*i);
                const compuesto=this.capital*Math.pow(1+this.tasa/100,i);
                data.push({year:`Año ${i}`,simple:this.roundVal(simple),compuesto:this.roundVal(compuesto)});
            }

            const showLabels = data.length <= 10; // <--- solo mostrar labels si hay <=10 puntos
            const textColor = document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151';
            const gridColor = document.documentElement.classList.contains('dark') ? '#4B5563' : '#E5E7EB';
            const isDark = document.documentElement.classList.contains('dark');

            const options = {
                chart:{type:'area',height:400,toolbar:{show:false},animations:{enabled:true,easing:'easeinout',speed:800}},
                series:[
                    {name:'Interés Simple',data:data.map(d=>d.simple)},
                    {name:'Interés Compuesto',data:data.map(d=>d.compuesto)}
                ],
                colors:[this.colors.simple,this.colors.compuesto],
                stroke:{curve:'straight',width:3},
                fill:{type:'gradient',gradient:{shade:'light',type:'vertical',shadeIntensity:0.3,gradientToColors:[this.colors.simple,this.colors.compuesto],opacityFrom:0.7,opacityTo:0.2,stops:[0,100]}},
                markers:{size:showLabels?5:0,hover:{size:7},colors:[this.colors.simple,this.colors.compuesto]},
                xaxis:{categories:data.map(d=>d.year),labels:{show:showLabels,style:{colors:textColor,fontWeight:'500'}}},
                yaxis:{labels:{show:showLabels,formatter:val=>this.formatCurrency(val),style:{colors:textColor}},min:0},
                tooltip:{theme:isDark?'dark':'light',y:{formatter:val=>this.formatCurrency(val)}},
                grid:{borderColor:gridColor,strokeDashArray:4},
                legend:{labels:{colors:textColor}},
                dataLabels:{
                    enabled: showLabels,
                    style:{colors:[this.colors.simple,this.colors.compuesto]}, // <-- colores de texto según serie
                    background: {
                        enabled: true,
                        foreColor: '#ffffff',
                        borderRadius: 6,
                        padding: 4,
                        opacity: 0.8
                    }
                }
};

            if(this.chartMain) this.chartMain.destroy();
            this.chartMain = new ApexCharts(this.$refs.chartMain,options);
            this.chartMain.render();
        },

        initDiffChart() {
            const data = [];
            const maxPoints = 50;
            const step = this.tiempo > maxPoints ? Math.ceil(this.tiempo / maxPoints) : 1;
            for (let i = 1; i <= this.tiempo; i += step) {
                const simple = this.capital * (1 + (this.tasa / 100) * i);
                const compuesto = this.capital * Math.pow(1 + this.tasa / 100, i);
                data.push({ year: `Año ${i}`, diff: this.roundVal(compuesto - simple) });
            }

            const showLabels = data.length <= 10; // <--- solo mostrar labels si hay <=10 puntos
            const textColor = document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151';
            const gridColor = document.documentElement.classList.contains('dark') ? '#4B5563' : '#E5E7EB';
            const isDark = document.documentElement.classList.contains('dark');

            const options = {
                chart: { type: 'bar', height: 350, toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 800 } },
                series: [{ name: 'Diferencial Compuesto-Simple', data: data.map(d => d.diff) }],
                colors: [this.colors.diferencial],
                plotOptions: { bar: { borderRadius: 10, columnWidth: '50%', distributed: true, dataLabels: { position: 'top' } } },
                dataLabels: {
                    enabled: showLabels,
                    formatter: val => this.formatCurrency(val),
                    style: { colors: [this.colors.diferencial], fontSize: '13px', fontWeight: 'bold' },
                    background: {
                        enabled: true,
                        foreColor: '#ffffff',
                        borderRadius: 6,
                        padding: 4,
                        opacity: 0.8
                    }
                },
                xaxis: { categories: data.map(d => d.year), labels: { show: true, style: { colors: textColor } } },
                yaxis: { labels: { formatter: val => this.formatCurrency(val), style: { colors: [textColor] } }, min: 0 },
                tooltip: { theme: isDark?'dark':'light', y: { formatter: val => this.formatCurrency(val) } },
                fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', gradientToColors: [this.colors.compuesto], opacityFrom: 0.7, opacityTo: 0.2, stops: [0, 100] } },
                grid: { borderColor: gridColor, strokeDashArray: 4 },
                legend: { show: false }
            };

            if (this.chartDiff) this.chartDiff.destroy();
            this.chartDiff = new ApexCharts(this.$refs.chartDiff, options);
            this.chartDiff.render();
        },

        // --- Actualización Charts con modo oscuro ---
        updateCharts(){
            this.initMainChart();
            this.initDiffChart();
            window.myCharts=[this.chartMain,this.chartDiff];

            const observer = new MutationObserver(()=>{
                const textColor = document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151';
                const gridColor = document.documentElement.classList.contains('dark') ? '#4B5563' : '#E5E7EB';

                window.myCharts.forEach(chart=>{
                    chart.updateOptions({
                        xaxis:{labels:{style:{colors:textColor}}},
                        yaxis:{labels:{style:{colors:textColor}}},
                        dataLabels:{style:{colors:[textColor]}},
                        grid:{borderColor:gridColor},
                        tooltip:{theme:document.documentElement.classList.contains('dark')?'dark':'light'},
                        legend:{labels:{colors:textColor}}
                    });
                });
            });
            observer.observe(document.documentElement,{attributes:true,attributeFilter:['class']});
        }
    }"
    x-init="updateCharts()"
    x-effect="updateCharts()"
>
    <style>
        /* Sliders personalizados */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 12px;
            border-radius: 10px;
            background: linear-gradient(90deg, #10B981 0%, #14B8A6 50%, #0F9C95 100%);
            outline: none;
            transition: 0.3s;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #10B981;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        input[type=range]:hover::-webkit-slider-thumb {
            transform: scale(1.2);
        }

        input[type=range]::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #10B981;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        /* Animaciones sofisticadas */
        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>

    <!-- Header -->
    <div class="text-center mb-10 animate-fade-in">
        <h1 class=" gap-3 text-2xl md:text-4xl font-extrabold mb-2 text-primary-500 dark:text-primary-400">
            Comparativa de Interés Simple vs Interés Compuesto
        </h1>
        <p class="text-lg md:text-lg text-gray-700 dark:text-gray-300 max-w-4xl mx-auto">
            💡 Observa cómo el capital crece con <strong>interés simple</strong> y <strong>compuesto</strong> de manera interactiva.
        </p>

    </div>
    <div class="flex animate-fade-in">
        <x-heroicon-o-information-circle class="size-6 my-auto font-bold text-primary-500 dark:text-primary-400"/>
        <p class="text-base text-gray-700 dark:text-gray-300 max-w-4xl pl-2">
            Ingresa o modifica algunos valores para ver como el interés compuesto se impone sobre el interés simple.
        </p>
    </div>


    <!-- Inputs Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div
            class="bg-gray-100 dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-300 dark:border-gray-700 hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <label class="block font-semibold mb-2 text-emerald-700 dark:text-emerald-300">Capital Inicial</label>
            <x-filament::input.wrapper suffix-icon="heroicon-o-currency-dollar" class="mb-3">
                <x-filament::input type="number"  x-model.number="capital" min="1000" step="1000" />
            </x-filament::input.wrapper>
            <input type="range" x-model.number="capital" min="0" max="50000" step="1000">
        </div>

        <div
            class="bg-gray-100 dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-300 dark:border-gray-700 hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <label class="block font-semibold mb-2 text-teal-700 dark:text-teal-300">Tasa de Interés (%)</label>
            <x-filament::input.wrapper suffix-icon="heroicon-o-percent-badge" class="mb-3">
                <x-filament::input type="number" x-model.number="tasa" min="0.1" max="30" step="0.5" />
            </x-filament::input.wrapper>
            <input type="range" x-model.number="tasa" min="0" max="40" step="0.5">
        </div>

        <div
            class="bg-gray-100 dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-300 dark:border-gray-700 hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <label class="block font-semibold mb-2 text-primary-700 dark:text-primary-300">Período (años)</label>
            <x-filament::input.wrapper class="mb-3" suffix-icon="heroicon-o-clock">
                <x-filament::input type="number" x-model.number="tiempo" min="1" max="30"/>
            </x-filament::input.wrapper>
            <input type="range" x-model.number="tiempo" min="1" max="30">
        </div>
    </div>

    <!-- Result Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div
            class="bg-emerald-50 dark:bg-emerald-900/30 rounded-3xl p-6 border border-emerald-400 dark:border-emerald-600 shadow-lg hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <h3 class="text-xl font-bold mb-2 text-emerald-700 dark:text-emerald-300">Interés Simple</h3>
            <div class="text-3xl font-extrabold mb-1 text-emerald-600 dark:text-emerald-400"
                 x-text="formatCurrency(simpleTotal)"></div>
            <p class="text-sm text-emerald-500 dark:text-emerald-300">Ganancia: <span
                    x-text="formatCurrency(simpleGanancia)"></span></p>
        </div>
        <div
            class="bg-teal-50 dark:bg-teal-900/30 rounded-3xl p-6 border border-teal-400 dark:border-teal-600 shadow-lg hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <h3 class="text-xl font-bold mb-2 text-teal-700 dark:text-teal-300">Interés Compuesto</h3>
            <div class="text-3xl font-extrabold mb-1 text-teal-600 dark:text-teal-400"
                 x-text="formatCurrency(compuestoTotal)"></div>
            <p class="text-sm text-teal-500 dark:text-teal-300">Ganancia: <span
                    x-text="formatCurrency(compuestoGanancia)"></span></p>
        </div>
        <div
            class="bg-amber-50 dark:bg-amber-900/30 rounded-3xl p-6 border border-amber-400 dark:border-amber-600 shadow-lg hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <h3 class="text-xl font-bold mb-2 text-amber-700 dark:text-amber-300">Diferencial</h3>
            <div class="text-3xl font-extrabold mb-1 text-amber-600 dark:text-amber-400"
                 x-text="formatCurrency(diferencia)"></div>
            <p class="text-sm text-amber-500 dark:text-amber-300">Ventaja: <span
                    x-text="((diferencia / simpleGanancia) * 100).toFixed(1)"></span>%</p>
        </div>
    </div>

        <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div
            class="bg-gray-100 dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-300 dark:border-gray-700 p-6 hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-200 mb-4">Interés Simple vs Compuesto</h3>
            <div x-ref="chartMain" class="h-96"></div>
        </div>
        <div
            class="bg-gray-100 dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-300 dark:border-gray-700 p-6 hover:scale-105 transform transition-transform duration-500 animate-slide-up">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-200 mb-4">Evolución del Diferencial</h3>
            <div x-ref="chartDiff" class="h-96"></div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</div>
