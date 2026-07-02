<?php

namespace App\Services;

use App\Enums\PublishStatus;
use App\Enums\RecordStatus;
use App\Models\AiChatSetting;
use App\Models\BlogPost;
use App\Models\FooterLinkGroup;
use App\Models\HomeFeatureCard;
use App\Models\HomePricingPlan;
use App\Models\HomeQuickLink;
use App\Models\HomeStat;
use App\Models\HomeWorkflowStep;
use App\Models\LegalPage;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteBranding;
use App\Models\SocialLink;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class SiteConfigService
{
    public function __construct(
        private readonly MediaStorageService $media,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function sitePayload(): array
    {
        return Cache::remember('api.site', 600, function (): array {
            $branding = SiteBranding::query()->firstOrCreate(['id' => 1], [
                'site_name' => 'Open9',
                'tagline' => 'Tecnología, cursos y proyectos',
            ]);

            $chat = AiChatSetting::query()->firstOrCreate(['id' => 1], [
                'is_enabled' => true,
                'fab_label' => 'Red en vivo',
                'welcome_message' => 'Hola, soy el asistente de OPEN9.',
                'model' => 'gemini-2.0-flash',
            ]);

            return [
                'branding' => [
                    'site_name' => $branding->site_name,
                    'tagline' => $branding->tagline,
                    'logo_url' => $this->media->url($branding->logo_path),
                    'logo_dark_url' => $this->media->url($branding->logo_dark_path),
                    'favicon_url' => $this->media->url($branding->favicon_path),
                    'hero' => [
                        'title' => $branding->hero_title,
                        'subtitle' => $branding->hero_subtitle,
                        'cta_primary' => [
                            'label' => $branding->hero_cta_primary_label,
                            'url' => $branding->hero_cta_primary_url,
                        ],
                        'cta_secondary' => [
                            'label' => $branding->hero_cta_secondary_label,
                            'url' => $branding->hero_cta_secondary_url,
                        ],
                    ],
                    'background_video_url' => $branding->background_video_url,
                    'footer_description' => $branding->footer_description,
                    'copyright_text' => $branding->copyright_text,
                    'seo_description' => $branding->seo_description,
                ],
                'contact' => [
                    'email' => $branding->contact_email,
                    'phone' => $branding->contact_phone,
                    'address' => $branding->contact_address,
                    'website_url' => $branding->website_url,
                ],
                'footer_groups' => FooterLinkGroup::query()
                    ->where('is_visible', true)
                    ->orderBy('sort_order')
                    ->with(['links' => fn ($q) => $q->orderBy('sort_order')])
                    ->get()
                    ->map(fn (FooterLinkGroup $group): array => [
                        'title' => $group->title,
                        'links' => $group->links->map(fn ($link): array => [
                            'label' => $link->label,
                            'url' => $link->url,
                            'is_external' => $link->is_external,
                        ])->all(),
                    ])->all(),
                'social_links' => SocialLink::query()
                    ->where('is_visible', true)
                    ->orderBy('sort_order')
                    ->get(['platform', 'url'])
                    ->all(),
                'chat' => [
                    'is_enabled' => $chat->is_enabled,
                    'fab_label' => $chat->fab_label,
                    'welcome_message' => $chat->welcome_message,
                ],
            ];
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function homePayload(): array
    {
        return Cache::remember('api.home', 600, function (): array {
            return [
                'stats' => HomeStat::query()
                    ->where('is_visible', true)
                    ->where('status', RecordStatus::Active)
                    ->orderBy('sort_order')
                    ->get(['value', 'suffix', 'title', 'icon'])
                    ->all(),
                'feature_cards' => HomeFeatureCard::query()
                    ->where('is_visible', true)
                    ->where('status', RecordStatus::Active)
                    ->orderBy('sort_order')
                    ->get(['card_type', 'client_type', 'title', 'description', 'icon'])
                    ->all(),
                'workflow_steps' => HomeWorkflowStep::query()
                    ->where('is_visible', true)
                    ->where('status', RecordStatus::Active)
                    ->orderBy('sort_order')
                    ->get(['step_number', 'title', 'description', 'icon'])
                    ->all(),
                'quick_links' => HomeQuickLink::query()
                    ->where('is_visible', true)
                    ->where('status', RecordStatus::Active)
                    ->orderBy('sort_order')
                    ->get(['title', 'description', 'link_url', 'icon'])
                    ->all(),
                'pricing_plans' => HomePricingPlan::query()
                    ->where('is_visible', true)
                    ->where('status', RecordStatus::Active)
                    ->orderBy('sort_order')
                    ->get(['name', 'price', 'period', 'description', 'features', 'cta_text', 'cta_url', 'is_highlighted'])
                    ->all(),
                'testimonials' => Testimonial::query()
                    ->where('status', RecordStatus::Active)
                    ->orderByDesc('id')
                    ->limit(6)
                    ->get(['name', 'profession', 'company', 'content', 'rating', 'photo'])
                    ->map(fn (Testimonial $t): array => [
                        'quote' => $t->content,
                        'author' => $t->name,
                        'role' => trim(($t->profession ?? '').($t->company ? ', '.$t->company : '')),
                        'photo_url' => $this->media->url($t->photo),
                    ])->all(),
            ];
        });
    }

    /**
     * @return array<string, mixed>|null
     */
    public function legalPage(string $slug): ?array
    {
        $page = LegalPage::query()
            ->where('slug', $slug)
            ->where('status', PublishStatus::Published)
            ->first();

        if ($page === null) {
            return null;
        }

        return [
            'slug' => $page->slug,
            'title' => $page->title,
            'blocks' => $page->blocks ?? [],
        ];
    }

    public function clearCache(): void
    {
        Cache::forget('api.site');
        Cache::forget('api.home');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function blogPosts(): array
    {
        return BlogPost::query()
            ->where('status', PublishStatus::Published)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->get()
            ->map(fn (BlogPost $post): array => $this->formatBlogPost($post))
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function blogPost(string $slug): ?array
    {
        $post = BlogPost::query()
            ->where('slug', $slug)
            ->where('status', PublishStatus::Published)
            ->with(['category', 'author', 'tags'])
            ->first();

        return $post ? $this->formatBlogPost($post, detailed: true) : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function projects(): array
    {
        return Project::query()
            ->where('status', PublishStatus::Published)
            ->with('category')
            ->orderByDesc('published_at')
            ->get()
            ->map(fn (Project $project): array => $this->formatProject($project))
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function project(string $slug): ?array
    {
        $project = Project::query()
            ->where('slug', $slug)
            ->where('status', PublishStatus::Published)
            ->with('category')
            ->first();

        return $project ? $this->formatProject($project, detailed: true) : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function services(): array
    {
        return Service::query()
            ->where('status', PublishStatus::Published)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Service $service): array => [
                'id' => (string) $service->id,
                'slug' => $service->slug,
                'title' => $service->title,
                'description' => $service->description,
                'icon' => $service->icon,
                'price' => $service->price_label,
                'features' => $service->features ?? [],
            ])->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function products(): array
    {
        return Product::query()
            ->where('status', PublishStatus::Published)
            ->with('category')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Product $product): array => [
                'id' => (string) $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'category' => $product->category?->name,
                'price' => (float) $product->price,
                'currency' => $product->currency,
                'description' => $product->description,
                'rating' => (float) $product->rating,
                'badge' => $product->badge,
                'stock' => $product->stock,
                'image_url' => $this->media->url($product->main_image),
            ])->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function formatBlogPost(BlogPost $post, bool $detailed = false): array
    {
        $data = [
            'id' => (string) $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'category' => $post->category?->name ?? '',
            'tags' => $post->relationLoaded('tags') ? $post->tags->pluck('name')->all() : [],
            'date' => $post->published_at?->format('d M Y') ?? '',
            'readTime' => ($post->reading_time ?? 5).' min',
            'author' => $post->author?->name ?? 'Open9',
            'image_url' => $this->media->url($post->main_image),
        ];

        if ($detailed) {
            $data['content'] = $post->content
                ? array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $post->content)))
                : [];
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProject(Project $project, bool $detailed = false): array
    {
        $data = [
            'id' => (string) $project->id,
            'slug' => $project->slug,
            'title' => $project->title,
            'category' => $project->category?->name ?? '',
            'year' => $project->published_at?->format('Y') ?? '',
            'description' => $project->short_description ?? $project->description,
            'tags' => $project->technology_stack ?? [],
            'featured' => (bool) $project->is_featured,
            'image_url' => $this->media->url($project->main_image),
        ];

        if ($detailed) {
            $data['description_full'] = $project->description;
            $data['gallery'] = collect($project->gallery ?? [])
                ->map(fn (string $path): ?string => $this->media->url($path))
                ->filter()
                ->values()
                ->all();
        }

        return $data;
    }
}
