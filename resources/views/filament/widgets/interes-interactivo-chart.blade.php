<div
    x-data="{
        capital: 1000,
        tasa: 10,
        tiempo: 5,
        chart: null,

        renderChart() {
            const labels = Array.from({ length: this.tiempo }, (_, i) => i + 1);

            const simple = labels.map(t => this.capital * (1 + (this.tasa / 100) * t));
            const compuesto = labels.map(t => this.capital * Math.pow(1 + this.tasa / 100, t));

            if (this.chart) {
                this.chart.data.labels = labels;
                this.chart.data.datasets[0].data = simple;
                this.chart.data.datasets[1].data = compuesto;
                this.chart.update();
                return;
            }

            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Interés Simple',
                            data: simple,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Interés Compuesto',
                            data: compuesto,
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: getComputedStyle(document.documentElement)
                                    .getPropertyValue('--filament-color-gray-900') || '#000'
                            }
                        }
                    },
                    scales: {
                        x: { ticks: { color: getComputedStyle(document.documentElement)
                            .getPropertyValue('--filament-color-gray-700') || '#444' } },
                        y: { ticks: { color: getComputedStyle(document.documentElement)
                            .getPropertyValue('--filament-color-gray-700') || '#444' } },
                    }
                }
            });
        }
    }"
    x-init="renderChart()"
    class="space-y-6"
>
    {{-- Controles --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capital</label>
            <input type="number" x-model.number="capital" min="100" step="100"
                   class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tiempo (años)</label>
            <input type="number" x-model.number="tiempo" min="1" max="20"
                   class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tasa (%)</label>
            <input type="number" x-model.number="tasa" min="1" max="30" step="0.5"
                   class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200" />
        </div>
    </div>

    {{-- Botón para actualizar --}}
    <div>
        <button
            type="button"
            @click="renderChart()"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700"
        >
            Actualizar gráfica
        </button>
    </div>

    {{-- Gráfica --}}
    <div>
        <canvas x-ref="canvas" class="w-full h-64"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
