<?php

namespace App\Livewire\Admin;

use App\Enums\ContactStatus;
use App\Models\AiChatSetting;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use BackedEnum;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @return list<array{label: string, value: int|string, hint?: string}>
     */
    public function siteMetrics(): array
    {
        $chat = AiChatSetting::query()->first();

        return [
            ['label' => 'Contactos nuevos', 'value' => Contact::query()->where('status', ContactStatus::New)->count()],
            ['label' => 'Proyectos publicados', 'value' => Project::query()->where('status', 'published')->count()],
            ['label' => 'Proyectos destacados', 'value' => Project::query()->where('is_featured', true)->count()],
            ['label' => 'Servicios activos', 'value' => Service::query()->where('status', 'published')->count()],
            ['label' => 'Artículos publicados', 'value' => BlogPost::query()->where('status', 'published')->count()],
            ['label' => 'Productos en tienda', 'value' => Product::query()->where('status', 'active')->count()],
            [
                'label' => 'Chat IA',
                'value' => $chat?->is_enabled ? 'Activo' : 'Inactivo',
                'hint' => $chat?->provider ? strtoupper((string) $chat->provider) : 'Sin proveedor',
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
     * @return list<array{label: string, route: string|null, icon: string}>
     */
    public function quickLinks(): array
    {
        $links = [
            ['label' => 'Identidad y marca', 'route' => 'admin.site-branding.index', 'icon' => 'sparkles', 'permission' => 'site-branding.view'],
            ['label' => 'Hero — card principal', 'route' => 'admin.home-hero-panel.index', 'icon' => 'presentation-chart-line', 'permission' => 'home-hero-panel.view'],
            ['label' => 'Encabezados de secciones', 'route' => 'admin.home-section-headers.index', 'icon' => 'bars-3-bottom-left', 'permission' => 'home-section-headers.view'],
            ['label' => 'Chat IA', 'route' => 'admin.ai-chat.index', 'icon' => 'chat-bubble-left-right', 'permission' => 'ai-chat.view'],
            ['label' => 'Mensajes de contacto', 'route' => 'admin.contacts.index', 'icon' => 'envelope', 'permission' => 'contacts.view'],
            ['label' => 'Proyectos', 'route' => 'admin.projects.index', 'icon' => 'folder-git-2', 'permission' => 'projects.view'],
            ['label' => 'Blog', 'route' => 'admin.blog.index', 'icon' => 'newspaper', 'permission' => 'blog.view'],
        ];

        return collect($links)
            ->filter(fn (array $link): bool => auth()->user()?->can($link['permission']) ?? false)
            ->filter(fn (array $link): bool => $link['route'] !== null && Route::has($link['route']))
            ->map(fn (array $link): array => [
                'label' => $link['label'],
                'route' => $link['route'],
                'icon' => $link['icon'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, Contact>
     */
    public function latestContacts(): Collection
    {
        return Contact::query()->latest()->limit(5)->get();
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function latestPosts(): Collection
    {
        return BlogPost::query()->latest('published_at')->limit(5)->get();
    }

    /**
     * @return Collection<int, Order>
     */
    public function latestOrders(): Collection
    {
        return Order::query()->latest()->limit(5)->get();
    }

    /**
     * @return array{labels: list<string>, contacts: list<int>, posts: list<int>, orders: list<int>, max: float}
     */
    public function monthlySeries(): array
    {
        $months = collect(range(5, 0))
            ->map(fn (int $monthsAgo): CarbonImmutable => now()->toImmutable()->subMonths($monthsAgo)->startOfMonth());

        $start = $months->first();
        $end = now()->endOfMonth();

        $contacts = Contact::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at'])
            ->groupBy(fn (Contact $contact): string => $contact->created_at->format('Y-m'));

        $posts = BlogPost::query()
            ->whereBetween('published_at', [$start, $end])
            ->get(['published_at'])
            ->groupBy(fn (BlogPost $post): string => $post->published_at?->format('Y-m') ?? '');

        $orders = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at'])
            ->groupBy(fn (Order $order): string => $order->created_at->format('Y-m'));

        $labels = [];
        $contactCounts = [];
        $postCounts = [];
        $orderCounts = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->format('M');
            $contactCounts[] = $contacts->get($key, collect())->count();
            $postCounts[] = $posts->get($key, collect())->count();
            $orderCounts[] = $orders->get($key, collect())->count();
        }

        return [
            'labels' => $labels,
            'contacts' => $contactCounts,
            'posts' => $postCounts,
            'orders' => $orderCounts,
            'max' => max(1, ...$contactCounts, ...$postCounts, ...$orderCounts),
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
