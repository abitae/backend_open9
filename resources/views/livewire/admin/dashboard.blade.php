<section class="space-y-8">
    @php($series = $this->monthlySeries())

    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <flux:heading size="xl">Panel del sitio</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500">
                Resumen operativo del frontend OPEN9: tienda, clientes, contenido CMS, contacto y chat IA.
            </flux:text>
        </div>
        <flux:button tag="a" href="{{ config('app.frontend_url', '/') }}" target="_blank" variant="primary" size="sm" icon="arrow-top-right-on-square">
            Ver sitio público
        </flux:button>
    </div>

    <div>
        <flux:heading size="sm" class="mb-3">Tienda y clientes</flux:heading>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($this->storeMetrics() as $metric)
                <x-admin.metric-card
                    :label="$metric['label']"
                    :value="$metric['value']"
                    :hint="$metric['hint'] ?? null"
                />
            @endforeach
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-3">Contenido del sitio</flux:heading>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($this->contentMetrics() as $metric)
                <x-admin.metric-card
                    :label="$metric['label']"
                    :value="$metric['value']"
                    :hint="$metric['hint'] ?? null"
                />
            @endforeach
        </div>
    </div>

    @foreach ($this->quickLinkGroups() as $group)
        <div>
            <flux:heading size="sm" class="mb-3">{{ $group['heading'] }}</flux:heading>
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
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Contactos recibidos</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="contacts-chart" aria-label="Gráfico de contactos mensuales"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">Clientes registrados</flux:heading>
                <span class="text-xs text-zinc-500">6 meses</span>
            </div>
            <div class="mt-3 h-44">
                <canvas id="clients-chart" aria-label="Gráfico de clientes registrados"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
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
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-2">
                <flux:heading size="sm">Últimos contactos</flux:heading>
                @can('contacts.view')
                    <flux:button tag="a" :href="route('admin.contacts.index')" wire:navigate variant="ghost" size="xs">Ver todos</flux:button>
                @endcan
            </div>
            <div class="mt-2 divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                @forelse ($this->latestContacts() as $contact)
                    <div class="flex items-center justify-between gap-2 py-2.5">
                        <span class="truncate font-medium">{{ $contact->name }}</span>
                        <flux:badge size="sm" color="zinc">{{ $contact->status?->value ?? $contact->status }}</flux:badge>
                    </div>
                @empty
                    <div class="py-6 text-center text-sm text-zinc-500">Sin mensajes.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-2">
                <flux:heading size="sm">Últimos pedidos</flux:heading>
                @can('orders.view')
                    <flux:button tag="a" :href="route('admin.orders.index')" wire:navigate variant="ghost" size="xs">Ver todos</flux:button>
                @endcan
            </div>
            <div class="mt-2 divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                @forelse ($this->latestOrders() as $order)
                    <div class="flex items-center justify-between gap-2 py-2.5">
                        <span class="truncate font-medium">{{ $order->order_code ?? ('#'.$order->id) }}</span>
                        <flux:badge size="sm" color="zinc">{{ $order->payment_status ?? '—' }}</flux:badge>
                    </div>
                @empty
                    <div class="py-6 text-center text-sm text-zinc-500">Sin pedidos.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-2">
                <flux:heading size="sm">Últimos clientes</flux:heading>
                @can('clients.view')
                    <flux:button tag="a" :href="route('admin.clients.index')" wire:navigate variant="ghost" size="xs">Ver todos</flux:button>
                @endcan
            </div>
            <div class="mt-2 divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                @forelse ($this->latestClients() as $client)
                    <div class="flex items-center justify-between gap-2 py-2.5">
                        <span class="truncate font-medium">{{ $client->name }}</span>
                        <span class="max-w-[50%] shrink-0 truncate text-xs text-zinc-500">{{ $client->email }}</span>
                    </div>
                @empty
                    <div class="py-6 text-center text-sm text-zinc-500">Sin clientes registrados.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-3">Academia</flux:heading>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($this->academyMetrics() as $metric)
                <x-admin.metric-card
                    :label="$metric['label']"
                    :value="$metric['value']"
                />
            @endforeach
        </div>
    </div>

    @script
        <script>
            window.renderOpen9DashboardCharts(@json($series));
        </script>
    @endscript
</section>
