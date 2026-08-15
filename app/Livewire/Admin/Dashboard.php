<?php

namespace App\Livewire\Admin;

use App\Enums\ContactStatus;
use App\Enums\PublishStatus;
use App\Enums\RecordStatus;
use App\Models\AiChatSetting;
use App\Models\BlogPost;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentSetting;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLoginSetting;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @return list<array{label: string, value: int|string, hint?: string}>
     */
    public function storeMetrics(): array
    {
        $payments = PaymentSetting::current();
        $social = SocialLoginSetting::current();

        $paidOrders = Order::query()->where('payment_status', 'paid');
        $storeRevenue = (float) (clone $paidOrders)->sum('total');

        return [
            ['label' => 'Productos publicados', 'value' => Product::query()->where('status', PublishStatus::Published)->count()],
            ['label' => 'Pedidos totales', 'value' => Order::query()->count()],
            ['label' => 'Pedidos pendientes de pago', 'value' => Order::query()->where('payment_status', 'unpaid')->count()],
            ['label' => 'Pedidos pagados', 'value' => (clone $paidOrders)->count()],
            [
                'label' => 'Ingresos tienda',
                'value' => 'PEN '.number_format($storeRevenue, 2),
                'hint' => 'Suma de pedidos con pago confirmado',
            ],
            ['label' => 'Clientes registrados', 'value' => Client::query()->where('status', RecordStatus::Active)->count()],
            [
                'label' => 'Pasarela MercadoPago',
                'value' => $payments->is_enabled ? 'Activa' : 'Inactiva',
                'hint' => $payments->is_enabled
                    ? strtoupper((string) $payments->mode).' · Checkout Bricks'
                    : 'Cobros deshabilitados en la tienda',
            ],
            [
                'label' => 'Login con Google',
                'value' => $social->googleEnabled() ? 'Activo' : 'Inactivo',
                'hint' => $social->googleEnabled() ? 'Disponible en /ingresar' : 'Sin credenciales configuradas',
            ],
        ];
    }

    /**
     * @return list<array{label: string, value: int|string, hint?: string}>
     */
    public function contentMetrics(): array
    {
        $chat = AiChatSetting::query()->first();

        return [
            ['label' => 'Proyectos publicados', 'value' => Project::query()->where('status', 'published')->count()],
            ['label' => 'Servicios activos', 'value' => Service::query()->where('status', 'published')->count()],
            ['label' => 'Artículos publicados', 'value' => BlogPost::query()->where('status', 'published')->count()],
            ['label' => 'Contactos nuevos', 'value' => Contact::query()->where('status', ContactStatus::New)->count()],
            [
                'label' => 'Chat IA',
                'value' => $chat?->is_enabled ? 'Activo' : 'Inactivo',
                'hint' => $chat?->is_enabled
                    ? strtoupper((string) ($chat->provider ?? 'gemini')).' · FAB en el sitio'
                    : 'Asistente oculto en el frontend',
            ],
        ];
    }

    /**
     * @return list<array{label: string, value: int|string}>
     */
    public function academyMetrics(): array
    {
        return [
            ['label' => 'Cursos publicados', 'value' => Course::query()->where('status', 'published')->count()],
            ['label' => 'Inscripciones', 'value' => CourseEnrollment::query()->count()],
            ['label' => 'Pagos aprobados', 'value' => Payment::query()->where('status', 'approved')->count()],
            [
                'label' => 'Ingresos academia',
                'value' => 'PEN '.number_format((float) Payment::query()->where('status', 'approved')->sum('amount'), 2),
            ],
        ];
    }

    /**
     * @return list<array{heading: string, links: list<array{label: string, route: string, icon: string}>}>
     */
    public function quickLinkGroups(): array
    {
        $groups = [
            [
                'heading' => 'Tienda y clientes',
                'links' => [
                    ['label' => 'Productos', 'route' => 'admin.products.index', 'icon' => 'shopping-bag', 'permission' => 'products.view'],
                    ['label' => 'Pedidos', 'route' => 'admin.orders.index', 'icon' => 'receipt-percent', 'permission' => 'orders.view'],
                    ['label' => 'Clientes', 'route' => 'admin.clients.index', 'icon' => 'users', 'permission' => 'clients.view'],
                    ['label' => 'Pasarela de pagos', 'route' => 'admin.payment-settings.index', 'icon' => 'credit-card', 'permission' => 'payment-settings.view'],
                    ['label' => 'Login con Google', 'route' => 'admin.social-login.index', 'icon' => 'globe-alt', 'permission' => 'social-login.view'],
                ],
            ],
            [
                'heading' => 'Contenido del sitio',
                'links' => [
                    ['label' => 'Identidad y marca', 'route' => 'admin.site-branding.index', 'icon' => 'sparkles', 'permission' => 'site-branding.view'],
                    ['label' => 'Hero — card principal', 'route' => 'admin.home-hero-panel.index', 'icon' => 'presentation-chart-line', 'permission' => 'home-hero-panel.view'],
                    ['label' => 'Encabezados de secciones', 'route' => 'admin.home-section-headers.index', 'icon' => 'bars-3-bottom-left', 'permission' => 'home-section-headers.view'],
                    ['label' => 'Proyectos', 'route' => 'admin.projects.index', 'icon' => 'folder-git-2', 'permission' => 'projects.view'],
                    ['label' => 'Servicios', 'route' => 'admin.services.index', 'icon' => 'wrench-screwdriver', 'permission' => 'services.view'],
                    ['label' => 'Blog', 'route' => 'admin.blog.index', 'icon' => 'newspaper', 'permission' => 'blog.view'],
                ],
            ],
            [
                'heading' => 'Engagement',
                'links' => [
                    ['label' => 'Mensajes de contacto', 'route' => 'admin.contacts.index', 'icon' => 'envelope', 'permission' => 'contacts.view'],
                    ['label' => 'Chat IA', 'route' => 'admin.ai-chat.index', 'icon' => 'chat-bubble-left-right', 'permission' => 'ai-chat.view'],
                ],
            ],
        ];

        /** @var list<array{heading: string, links: list<array{label: string, route: string, icon: string}>}> $filtered */
        $filtered = collect($groups)
            ->map(function (array $group): array {
                $links = collect($group['links'])
                    ->filter(fn (array $link): bool => auth()->user()?->can($link['permission']) ?? false)
                    ->filter(fn (array $link): bool => Route::has($link['route']))
                    ->map(fn (array $link): array => [
                        'label' => $link['label'],
                        'route' => $link['route'],
                        'icon' => $link['icon'],
                    ])
                    ->values()
                    ->all();

                return [
                    'heading' => $group['heading'],
                    'links' => $links,
                ];
            })
            ->filter(fn (array $group): bool => count($group['links']) > 0)
            ->values()
            ->all();

        return $filtered;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function latestContacts(): Collection
    {
        return Contact::query()->latest()->limit(5)->get();
    }

    /**
     * @return Collection<int, Order>
     */
    public function latestOrders(): Collection
    {
        return Order::query()->latest()->limit(5)->get();
    }

    /**
     * @return Collection<int, Client>
     */
    public function latestClients(): Collection
    {
        return Client::query()->latest()->limit(5)->get();
    }

    /**
     * @return array{labels: list<string>, contacts: list<int>, clients: list<int>, orders: list<int>, max: float}
     */
    public function monthlySeries(): array
    {
        $months = collect(range(5, 0))
            ->map(fn (int $monthsAgo): CarbonImmutable => now()->toImmutable()->subMonths($monthsAgo)->startOfMonth());

        $start = $months->first();
        $end = now()->endOfMonth();
        $monthExpression = $this->monthKeyExpression();

        $contacts = $this->monthlyCounts(Contact::query(), $start, $end, $monthExpression);
        $clients = $this->monthlyCounts(Client::query(), $start, $end, $monthExpression);
        $orders = $this->monthlyCounts(Order::query(), $start, $end, $monthExpression);

        $labels = [];
        $contactCounts = [];
        $clientCounts = [];
        $orderCounts = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->format('M');
            $contactCounts[] = $contacts[$key] ?? 0;
            $clientCounts[] = $clients[$key] ?? 0;
            $orderCounts[] = $orders[$key] ?? 0;
        }

        return [
            'labels' => $labels,
            'contacts' => $contactCounts,
            'clients' => $clientCounts,
            'orders' => $orderCounts,
            'max' => max(1, ...$contactCounts, ...$clientCounts, ...$orderCounts),
        ];
    }

    /**
     * @param  Builder<Model>  $query
     * @return array<string, int>
     */
    private function monthlyCounts(Builder $query, CarbonImmutable $start, Carbon $end, string $monthExpression): array
    {
        return $query
            ->whereBetween('created_at', [$start, $end])
            ->toBase()
            ->selectRaw($monthExpression.' as month_key')
            ->selectRaw('count(*) as aggregate')
            ->groupByRaw($monthExpression)
            ->pluck('aggregate', 'month_key')
            ->map(fn (mixed $count): int => (int) $count)
            ->all();
    }

    private function monthKeyExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', created_at)",
            'pgsql' => "to_char(created_at, 'YYYY-MM')",
            default => "DATE_FORMAT(created_at, '%Y-%m')",
        };
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
