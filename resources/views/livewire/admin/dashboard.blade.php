<section class="space-y-4">
    @php($series = $this->monthlySeries())
    <div>
        <flux:heading size="xl">Panel</flux:heading>
        <flux:text class="text-xs">Resumen operativo de Open9.</flux:text>
    </div>

    <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-6">
        @foreach ($this->metrics() as $label => $value)
            <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <div class="text-xs text-zinc-500">{{ $label }}</div>
                <div class="mt-1 truncate text-lg font-semibold">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-3 xl:grid-cols-3">
        <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Inscripciones mensuales</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="monthly-enrollments-chart" aria-label="Gráfico de inscripciones mensuales"></canvas>
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Pagos aprobados</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="approved-payments-chart" aria-label="Gráfico de pagos aprobados"></canvas>
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Ingresos</flux:heading>
                <span class="text-xs text-zinc-500">PEN</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="revenue-chart" aria-label="Gráfico de ingresos"></canvas>
            </div>
        </div>
    </div>

    <div class="grid gap-3 lg:grid-cols-3">
        @foreach (['Últimas inscripciones' => $this->latestEnrollments(), 'Pagos pendientes' => $this->pendingPayments(), 'Posts populares' => $this->popularPosts()] as $title => $records)
            <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <flux:heading size="sm">{{ $title }}</flux:heading>
                <div class="mt-2 divide-y divide-zinc-200 text-xs dark:divide-zinc-700">
                    @forelse ($records as $record)
                        <div class="flex justify-between gap-2 py-2">
                            <span class="truncate">{{ $record->full_name ?? $record->payment_code ?? $record->title }}</span>
                            <span class="text-zinc-500">{{ $record->status?->value ?? $record->views_count ?? '' }}</span>
                        </div>
                    @empty
                        <div class="py-4 text-zinc-500">Sin registros.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    @script
        <script>
            window.renderOpen9DashboardCharts(@json($series));
        </script>
    @endscript
</section>
