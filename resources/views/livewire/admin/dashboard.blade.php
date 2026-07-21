<section class="space-y-6">
    @php($series = $this->monthlySeries())

    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <flux:heading size="xl">Panel del sitio</flux:heading>
            <flux:text class="text-xs">
                Resumen operativo del frontend OPEN9: tienda, clientes, contenido CMS, contacto y chat IA.
            </flux:text>
        </div>
        <flux:button tag="a" href="{{ config('app.frontend_url', '/') }}" target="_blank" variant="ghost" size="sm" icon="arrow-top-right-on-square">
            Ver sitio público
        </flux:button>
    </div>

    <div>
        <flux:heading size="sm" class="mb-2">Tienda y clientes</flux:heading>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($this->storeMetrics() as $metric)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="text-xs text-zinc-500">{{ $metric['label'] }}</div>
                    <div class="mt-1 text-xl font-semibold">{{ $metric['value'] }}</div>
                    @if (! empty($metric['hint']))
                        <div class="mt-1 text-xs text-zinc-400">{{ $metric['hint'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-2">Contenido del sitio</flux:heading>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($this->contentMetrics() as $metric)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="text-xs text-zinc-500">{{ $metric['label'] }}</div>
                    <div class="mt-1 text-xl font-semibold">{{ $metric['value'] }}</div>
                    @if (! empty($metric['hint']))
                        <div class="mt-1 text-xs text-zinc-400">{{ $metric['hint'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @foreach ($this->quickLinkGroups() as $group)
        <div>
            <flux:heading size="sm" class="mb-2">{{ $group['heading'] }}</flux:heading>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                @foreach ($group['links'] as $link)
                    <flux:button
                        tag="a"
                        :href="route($link['route'])"
                        wire:navigate
                        variant="subtle"
                        class="justify-start"
                        :icon="$link['icon']"
                    >
                        {{ $link['label'] }}
                    </flux:button>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="grid gap-3 xl:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Contactos recibidos</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="contacts-chart" aria-label="Gráfico de contactos mensuales"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Clientes registrados</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="clients-chart" aria-label="Gráfico de clientes registrados"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Pedidos de tienda</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="orders-chart" aria-label="Gráfico de pedidos"></canvas>
            </div>
        </div>
    </div>

    <div class="grid gap-3 lg:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="sm">Últimos contactos</flux:heading>
            <div class="mt-2 divide-y divide-zinc-200 text-xs dark:divide-zinc-700">
                @forelse ($this->latestContacts() as $contact)
                    <div class="flex justify-between gap-2 py-2">
                        <span class="truncate">{{ $contact->name }}</span>
                        <span class="shrink-0 text-zinc-500">{{ $contact->status?->value ?? $contact->status }}</span>
                    </div>
                @empty
                    <div class="py-4 text-zinc-500">Sin mensajes.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="sm">Últimos pedidos</flux:heading>
            <div class="mt-2 divide-y divide-zinc-200 text-xs dark:divide-zinc-700">
                @forelse ($this->latestOrders() as $order)
                    <div class="flex justify-between gap-2 py-2">
                        <span class="truncate">{{ $order->order_code ?? ('#'.$order->id) }}</span>
                        <span class="shrink-0 text-zinc-500">{{ $order->payment_status ?? '—' }}</span>
                    </div>
                @empty
                    <div class="py-4 text-zinc-500">Sin pedidos.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="sm">Últimos clientes</flux:heading>
            <div class="mt-2 divide-y divide-zinc-200 text-xs dark:divide-zinc-700">
                @forelse ($this->latestClients() as $client)
                    <div class="flex justify-between gap-2 py-2">
                        <span class="truncate">{{ $client->name }}</span>
                        <span class="shrink-0 text-zinc-500">{{ $client->email }}</span>
                    </div>
                @empty
                    <div class="py-4 text-zinc-500">Sin clientes registrados.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-2">Academia</flux:heading>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($this->academyMetrics() as $metric)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="text-xs text-zinc-500">{{ $metric['label'] }}</div>
                    <div class="mt-1 text-lg font-semibold">{{ $metric['value'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    @script
        <script>
            window.renderOpen9DashboardCharts(@json($series));
        </script>
    @endscript
</section>
