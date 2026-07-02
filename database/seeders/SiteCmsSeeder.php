<?php

namespace Database\Seeders;

use App\Models\AiChatSetting;
use App\Models\FooterLink;
use App\Models\FooterLinkGroup;
use App\Models\HomeFeatureCard;
use App\Models\HomePricingPlan;
use App\Models\HomeQuickLink;
use App\Models\HomeStat;
use App\Models\HomeWorkflowStep;
use App\Models\LegalPage;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\SiteBranding;
use App\Models\SocialLink;
use App\Models\StorageSetting;
use Illuminate\Database\Seeder;

class SiteCmsSeeder extends Seeder
{
    public function run(): void
    {
        StorageSetting::query()->firstOrCreate(['id' => 1], ['driver' => 'local']);

        SiteBranding::query()->updateOrCreate(['id' => 1], [
            'site_name' => 'OPEN9',
            'tagline' => 'Tecnología integral para tu empresa',
            'hero_title' => 'Infraestructura, cloud y software a medida',
            'hero_subtitle' => 'Hardware para servidores, cloud AWS/Azure/Google y desarrollo web/mobile con equipos dedicados.',
            'hero_cta_primary_label' => 'Ver servicios',
            'hero_cta_primary_url' => '/servicios',
            'hero_cta_secondary_label' => 'Contactar',
            'hero_cta_secondary_url' => '/contacto',
            'background_video_url' => 'https://d2v9y0dukr6mq2.cloudfront.net/video/portfolio/clouds.mp4',
            'contact_email' => 'hola@open9.dev',
            'contact_phone' => '+51 999 000 000',
            'website_url' => 'https://open9.dev',
            'footer_description' => 'Servicios tecnológicos integrales: hardware, cloud y software a medida.',
            'copyright_text' => '© 2026 OPEN9. Todos los derechos reservados.',
            'seo_description' => 'OPEN9 — hardware, cloud AWS/Azure/Google y software web/mobile.',
        ]);

        AiChatSetting::query()->updateOrCreate(['id' => 1], [
            'is_enabled' => true,
            'fab_label' => 'Red en vivo',
            'welcome_message' => 'Hola, soy el asistente de OPEN9. Pregúntame sobre hardware, cloud, software o nuestros servicios.',
            'system_prompt' => 'Eres el asistente virtual de OPEN9, empresa de tecnología. Responde en español de forma clara y profesional sobre hardware, cloud (AWS, Azure, Google), software a medida y servicios de la empresa.',
            'model' => 'gemini-2.0-flash',
            'temperature' => 0.7,
            'max_tokens' => 1024,
        ]);

        if (HomeStat::query()->count() === 0) {
            $stats = [
                ['value' => '80', 'suffix' => '+', 'title' => 'Proyectos entregados', 'icon' => 'FolderKanban', 'sort_order' => 1],
                ['value' => '99.9', 'suffix' => '%', 'title' => 'Uptime garantizado', 'icon' => 'Activity', 'sort_order' => 2],
                ['value' => '24', 'suffix' => '/7', 'title' => 'Soporte técnico', 'icon' => 'Headphones', 'sort_order' => 3],
                ['value' => '15', 'suffix' => '+', 'title' => 'Años de experiencia', 'icon' => 'Award', 'sort_order' => 4],
            ];
            foreach ($stats as $stat) {
                HomeStat::query()->create($stat + ['status' => 'active', 'is_visible' => true]);
            }
        }

        if (HomeFeatureCard::query()->count() === 0) {
            $cards = [
                ['card_type' => 'service', 'title' => 'Hardware & Servidores', 'description' => 'Racks, virtualización y nodos optimizados.', 'icon' => 'Server', 'sort_order' => 1],
                ['card_type' => 'service', 'title' => 'Cloud Multi-Provider', 'description' => 'AWS, Azure y Google Cloud.', 'icon' => 'Cloud', 'sort_order' => 2],
                ['card_type' => 'service', 'title' => 'Software a Medida', 'description' => 'Web, mobile e integraciones.', 'icon' => 'Code2', 'sort_order' => 3],
            ];
            foreach ($cards as $card) {
                HomeFeatureCard::query()->create($card + ['status' => 'active', 'is_visible' => true]);
            }
        }

        if (HomeWorkflowStep::query()->count() === 0) {
            $steps = [
                ['step_number' => 1, 'title' => 'Diagnóstico', 'description' => 'Analizamos tu infraestructura y objetivos.', 'icon' => 'Search', 'sort_order' => 1],
                ['step_number' => 2, 'title' => 'Diseño', 'description' => 'Arquitectura y plan de implementación.', 'icon' => 'PenTool', 'sort_order' => 2],
                ['step_number' => 3, 'title' => 'Despliegue', 'description' => 'Implementación, pruebas y soporte.', 'icon' => 'Rocket', 'sort_order' => 3],
            ];
            foreach ($steps as $step) {
                HomeWorkflowStep::query()->create($step + ['status' => 'active', 'is_visible' => true]);
            }
        }

        if (HomeQuickLink::query()->count() === 0) {
            $links = [
                ['title' => 'Proyectos', 'description' => 'Casos de estudio reales.', 'link_url' => '/proyectos', 'icon' => 'FolderKanban', 'sort_order' => 1],
                ['title' => 'Servicios', 'description' => 'Consultoría e implementación.', 'link_url' => '/servicios', 'icon' => 'Wrench', 'sort_order' => 2],
                ['title' => 'Tienda', 'description' => 'Hardware y licencias.', 'link_url' => '/tienda', 'icon' => 'ShoppingBag', 'sort_order' => 3],
            ];
            foreach ($links as $link) {
                HomeQuickLink::query()->create($link + ['status' => 'active', 'is_visible' => true]);
            }
        }

        if (HomePricingPlan::query()->count() === 0) {
            HomePricingPlan::query()->create([
                'name' => 'Starter',
                'price' => '$5,500',
                'period' => 'proyecto',
                'description' => 'Ideal para MVPs y pilotos.',
                'features' => ['Consultoría inicial', 'Arquitectura básica', 'Soporte 30 días'],
                'cta_text' => 'Solicitar',
                'cta_url' => '/contacto',
                'sort_order' => 1,
                'status' => 'active',
                'is_visible' => true,
            ]);
            HomePricingPlan::query()->create([
                'name' => 'Pro',
                'price' => '$12,000',
                'period' => 'proyecto',
                'description' => 'Para empresas en crecimiento.',
                'features' => ['Todo Starter', 'Cloud multi-provider', 'Soporte 90 días'],
                'cta_text' => 'Solicitar',
                'cta_url' => '/contacto',
                'is_highlighted' => true,
                'sort_order' => 2,
                'status' => 'active',
                'is_visible' => true,
            ]);
            HomePricingPlan::query()->create([
                'name' => 'Enterprise',
                'price' => 'A medida',
                'period' => '',
                'description' => 'Infraestructura crítica y SLA dedicado.',
                'features' => ['Equipo dedicado', 'SLA 99.9%', 'Soporte 24/7'],
                'cta_text' => 'Contactar',
                'cta_url' => '/contacto',
                'sort_order' => 3,
                'status' => 'active',
                'is_visible' => true,
            ]);
        }

        if (FooterLinkGroup::query()->count() === 0) {
            $nav = FooterLinkGroup::query()->create(['title' => 'Navegación', 'sort_order' => 1]);
            foreach ([['Inicio', '/'], ['Proyectos', '/proyectos'], ['Servicios', '/servicios'], ['Blog', '/blog'], ['Tienda', '/tienda']] as $i => [$label, $url]) {
                FooterLink::query()->create(['footer_link_group_id' => $nav->id, 'label' => $label, 'url' => $url, 'sort_order' => $i + 1]);
            }
            $legal = FooterLinkGroup::query()->create(['title' => 'Legal', 'sort_order' => 2]);
            foreach ([['Privacidad', '/legal/privacidad'], ['Términos', '/legal/terminos'], ['Cookies', '/legal/cookies']] as $i => [$label, $url]) {
                FooterLink::query()->create(['footer_link_group_id' => $legal->id, 'label' => $label, 'url' => $url, 'sort_order' => $i + 1]);
            }
        }

        if (SocialLink::query()->count() === 0) {
            SocialLink::query()->create(['platform' => 'linkedin', 'url' => 'https://linkedin.com', 'sort_order' => 1]);
            SocialLink::query()->create(['platform' => 'twitter', 'url' => 'https://twitter.com', 'sort_order' => 2]);
            SocialLink::query()->create(['platform' => 'instagram', 'url' => 'https://instagram.com', 'sort_order' => 3]);
        }

        foreach (['privacidad' => 'Política de Privacidad', 'terminos' => 'Términos y Condiciones', 'cookies' => 'Política de Cookies'] as $slug => $title) {
            LegalPage::query()->updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'status' => 'published',
                'published_at' => now(),
                'blocks' => [
                    ['type' => 'heading', 'content' => $title],
                    ['type' => 'paragraph', 'content' => 'Contenido administrable desde el panel de OPEN9.'],
                ],
            ]);
        }

        if (Service::query()->count() === 0) {
            $services = [
                ['title' => 'Consultoría TI', 'slug' => 'consultoria-ti', 'description' => 'Auditoría y roadmap tecnológico.', 'icon' => 'Lightbulb', 'price_label' => 'Desde $2,500', 'features' => ['Diagnóstico', 'Roadmap', 'Workshops']],
                ['title' => 'Cloud & DevOps', 'slug' => 'cloud-devops', 'description' => 'AWS, Azure y Google Cloud.', 'icon' => 'Cloud', 'price_label' => 'Desde $4,500', 'features' => ['Migración', 'CI/CD', 'Monitoreo']],
                ['title' => 'Desarrollo Web', 'slug' => 'desarrollo-web', 'description' => 'Aplicaciones web a medida.', 'icon' => 'Globe', 'price_label' => 'Desde $5,500', 'features' => ['React/Laravel', 'APIs', 'Integraciones']],
            ];
            foreach ($services as $i => $service) {
                Service::query()->create($service + ['sort_order' => $i + 1, 'status' => 'published']);
            }
        }

        if (Product::query()->count() === 0) {
            $category = ProductCategory::query()->firstOrCreate(
                ['slug' => 'hardware'],
                ['name' => 'Hardware', 'status' => 'active'],
            );
            $products = [
                ['name' => 'Servidor Rack 2U', 'slug' => 'servidor-rack-2u', 'price' => 4500, 'description' => 'Servidor empresarial de alto rendimiento.', 'badge' => 'Nuevo', 'rating' => 4.8, 'stock' => 10],
                ['name' => 'Licencia Cloud Pro', 'slug' => 'licencia-cloud-pro', 'price' => 299, 'description' => 'Paquete de servicios cloud gestionados.', 'rating' => 4.5, 'stock' => 100],
            ];
            foreach ($products as $i => $product) {
                Product::query()->create($product + [
                    'product_category_id' => $category->id,
                    'sort_order' => $i + 1,
                    'status' => 'published',
                ]);
            }
        }
    }
}
