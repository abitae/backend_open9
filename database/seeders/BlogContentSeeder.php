<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\Concerns\SeedsReferenceImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BlogContentSeeder extends Seeder
{
    use SeedsReferenceImages;

    public function run(): void
    {
        $editor = User::query()->where('email', 'editor@open9.dev')->first()
            ?? User::query()->where('email', 'admin@open9.dev')->firstOrFail();

        $categories = BlogCategory::query()->get()->keyBy('slug');
        $defaultCategory = $categories->first();

        $this->seedTags();
        $this->enrichExistingPosts($editor, $categories, $defaultCategory);
        $this->seedAdditionalPosts($editor, $categories, $defaultCategory);
        $this->enrichProjects();
    }

    private function seedTags(): void
    {
        foreach (['AWS', 'Azure', 'Docker', 'Kubernetes', 'React', 'Seguridad', 'IA', 'PostgreSQL', 'DevOps', 'Frontend', 'Cloud'] as $name) {
            BlogTag::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }

    /**
     * @param  Collection<string, BlogCategory>  $categories
     */
    private function enrichExistingPosts(User $editor, $categories, BlogCategory $defaultCategory): void
    {
        $existing = [
            'como-construir-dashboards-administrativos-compactos' => ['category' => 'laravel', 'seed' => 'open9-blog-dashboard', 'tags' => ['Laravel', 'Livewire']],
            'buenas-practicas-con-livewire-y-fluxui' => ['category' => 'frontend', 'seed' => 'open9-blog-livewire', 'tags' => ['Livewire', 'React']],
            'postgresql-para-plataformas-educativas' => ['category' => 'devops', 'seed' => 'open9-blog-postgres', 'tags' => ['PostgreSQL', 'Cloud']],
            'automatizacion-de-pagos-y-certificados' => ['category' => 'laravel', 'seed' => 'open9-blog-payments', 'tags' => ['Laravel', 'Seguridad']],
            'arquitectura-modular-en-laravel' => ['category' => 'laravel', 'seed' => 'open9-blog-architecture', 'tags' => ['Laravel', 'Docker']],
        ];

        foreach ($existing as $slug => $meta) {
            $post = BlogPost::query()->where('slug', $slug)->first();
            if ($post === null) {
                continue;
            }

            $category = $categories->get($meta['category']) ?? $defaultCategory;

            $post->update([
                'blog_category_id' => $category->id,
                'main_image' => $this->referenceImage($meta['seed']),
                'excerpt' => $post->excerpt ?: 'Artículo demo del blog OPEN9 con enfoque práctico para equipos tecnológicos.',
                'content' => $this->paragraphs(
                    'En OPEN9 aplicamos este enfoque en proyectos reales de infraestructura y software.',
                    'La clave está en combinar buenas prácticas de arquitectura con entregas iterativas.',
                    'Compartimos lecciones aprendidas en despliegues cloud y plataformas administrativas.',
                ),
            ]);

            $tagIds = BlogTag::query()->whereIn('name', $meta['tags'])->pluck('id');
            if ($tagIds->isNotEmpty()) {
                $post->tags()->syncWithoutDetaching($tagIds->all());
            }
        }
    }

    /**
     * @param  Collection<string, BlogCategory>  $categories
     */
    private function seedAdditionalPosts(User $editor, $categories, BlogCategory $defaultCategory): void
    {
        $posts = [
            [
                'title' => 'Migración a AWS: lecciones de un proyecto real',
                'category' => 'devops',
                'seed' => 'open9-blog-aws',
                'tags' => ['AWS', 'Cloud', 'Docker'],
                'featured' => true,
                'weeks_ago' => 1,
            ],
            [
                'title' => 'React 19 y Vite: stack moderno para el frontend OPEN9',
                'category' => 'frontend',
                'seed' => 'open9-blog-react',
                'tags' => ['React', 'Frontend'],
                'featured' => true,
                'weeks_ago' => 2,
            ],
            [
                'title' => 'Kubernetes en producción: checklist para equipos pequeños',
                'category' => 'devops',
                'seed' => 'open9-blog-k8s',
                'tags' => ['Kubernetes', 'DevOps', 'Azure'],
                'featured' => false,
                'weeks_ago' => 3,
            ],
            [
                'title' => 'IA generativa en soporte técnico: casos de uso',
                'category' => 'carrera-tech',
                'seed' => 'open9-blog-ai',
                'tags' => ['IA', 'Carrera Tech'],
                'featured' => false,
                'weeks_ago' => 4,
            ],
            [
                'title' => 'Seguridad en APIs Laravel: autenticación y rate limiting',
                'category' => 'laravel',
                'seed' => 'open9-blog-security',
                'tags' => ['Laravel', 'Seguridad'],
                'featured' => false,
                'weeks_ago' => 5,
            ],
            [
                'title' => 'De monolito a microservicios sin perder el control',
                'category' => 'devops',
                'seed' => 'open9-blog-microservices',
                'tags' => ['Docker', 'Cloud', 'PostgreSQL'],
                'featured' => false,
                'weeks_ago' => 6,
            ],
            [
                'title' => 'Cómo preparar tu carrera tech en 2026',
                'category' => 'carrera-tech',
                'seed' => 'open9-blog-career',
                'tags' => ['Carrera Tech', 'IA'],
                'featured' => false,
                'weeks_ago' => 7,
            ],
            [
                'title' => 'Monitoreo con Grafana y Prometheus en cloud híbrido',
                'category' => 'devops',
                'seed' => 'open9-blog-monitoring',
                'tags' => ['AWS', 'Azure', 'DevOps'],
                'featured' => false,
                'weeks_ago' => 8,
            ],
        ];

        foreach ($posts as $index => $data) {
            $slug = Str::slug($data['title']);
            $category = $categories->get($data['category']) ?? $defaultCategory;

            $post = BlogPost::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $editor->id,
                    'blog_category_id' => $category->id,
                    'title' => $data['title'],
                    'excerpt' => 'Guía práctica del equipo OPEN9 sobre '.$category->name.'.',
                    'content' => $this->paragraphs(
                        'Este artículo resume experiencias reales de proyectos de infraestructura y software.',
                        'Incluye recomendaciones aplicables a equipos que escalan servicios en la nube.',
                        'OPEN9 acompaña a empresas en hardware, cloud multi-provider y desarrollo a medida.',
                    ),
                    'main_image' => $this->referenceImage($data['seed']),
                    'gallery' => [
                        $this->referenceImage($data['seed'].'-a', 600, 400),
                        $this->referenceImage($data['seed'].'-b', 600, 400),
                    ],
                    'status' => 'published',
                    'is_featured' => $data['featured'],
                    'views_count' => 320 + ($index * 45),
                    'reading_time' => 5 + ($index % 4),
                    'seo_title' => $data['title'],
                    'seo_description' => 'Artículo OPEN9 sobre tecnología, cloud y mejores prácticas.',
                    'seo_keywords' => 'open9, cloud, tecnologia',
                    'published_at' => now()->subWeeks($data['weeks_ago']),
                ],
            );

            $tagIds = BlogTag::query()->whereIn('name', $data['tags'])->pluck('id');
            if ($tagIds->isNotEmpty()) {
                $post->tags()->sync($tagIds->all());
            }
        }
    }

    private function enrichProjects(): void
    {
        $projects = [
            'erp-academico-open9' => 'open9-project-erp',
            'ecommerce-b2b-laravel' => 'open9-project-ecommerce',
            'portal-de-analitica-cloud' => 'open9-project-analytics',
            'automatizacion-de-ventas' => 'open9-project-sales',
        ];

        foreach ($projects as $slug => $seed) {
            Project::query()->where('slug', $slug)->update([
                'main_image' => $this->referenceImage($seed, 1200, 675),
                'gallery' => [
                    $this->referenceImage($seed.'-1', 800, 500),
                    $this->referenceImage($seed.'-2', 800, 500),
                ],
            ]);
        }
    }

    /**
     * @param  string  ...$paragraphs
     */
    private function paragraphs(...$paragraphs): string
    {
        return implode("\n\n", $paragraphs);
    }
}
