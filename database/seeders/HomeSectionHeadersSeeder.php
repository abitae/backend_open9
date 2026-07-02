<?php

namespace Database\Seeders;

use App\Models\HomeSectionSetting;
use Illuminate\Database\Seeder;

class HomeSectionHeadersSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'section_key' => 'stats',
                'label' => 'Métricas',
                'title' => 'Resultados que respaldan nuestra experiencia',
                'description' => null,
                'sort_order' => 1,
            ],
            [
                'section_key' => 'platform_services',
                'label' => 'Servicios',
                'title' => 'Todo lo que necesitas para',
                'title_highlight' => 'escalar',
                'description' => 'Hardware, cloud y software integrados en una sola plataforma de servicios.',
                'sort_order' => 2,
            ],
            [
                'section_key' => 'platform_solutions',
                'label' => 'Soluciones',
                'title' => 'Por tipo de cliente',
                'description' => 'Adaptamos la infraestructura y el software a cada etapa de tu negocio.',
                'sort_order' => 3,
            ],
            [
                'section_key' => 'services_catalog',
                'label' => 'Servicios',
                'title' => 'Soluciones',
                'title_highlight' => 'end-to-end',
                'description' => 'Consultoría, cloud y desarrollo con equipos dedicados OPEN9.',
                'sort_order' => 4,
            ],
            [
                'section_key' => 'workflow',
                'label' => 'Metodología',
                'title' => 'De la idea al',
                'title_highlight' => 'despliegue',
                'description' => 'Fases claras para llevar tu proyecto tecnológico desde la consultoría inicial hasta infraestructura y software en producción.',
                'cta_label' => 'Hablar con OPEN9',
                'cta_url' => '/contacto',
                'sort_order' => 5,
            ],
            [
                'section_key' => 'projects_preview',
                'label' => 'Portafolio',
                'title' => 'Proyectos que',
                'title_highlight' => 'transforman',
                'description' => 'Casos reales de web, cloud e infraestructura implementados por OPEN9.',
                'sort_order' => 6,
            ],
            [
                'section_key' => 'quick_links',
                'label' => 'Explorar',
                'title' => 'Descubre todo lo que',
                'title_highlight' => 'OPEN9',
                'description' => 'Accesos directos a proyectos, servicios y tienda.',
                'sort_order' => 7,
            ],
            [
                'section_key' => 'testimonials',
                'label' => 'Testimonios',
                'title' => 'Empresas que confían en',
                'title_highlight' => 'OPEN9',
                'description' => null,
                'sort_order' => 8,
            ],
            [
                'section_key' => 'pricing',
                'label' => 'Planes',
                'title' => 'Opciones flexibles para',
                'title_highlight' => 'cada etapa',
                'description' => 'Desde consultoría inicial hasta infraestructura enterprise con hardware, cloud y software integrados.',
                'sort_order' => 9,
            ],
            [
                'section_key' => 'cta_contact',
                'label' => 'Hablemos de tu proyecto',
                'title' => 'Impulsa tu',
                'title_highlight' => 'infraestructura tech',
                'description' => 'Cuéntanos sobre tu proyecto de hardware, cloud o software. Te respondemos en menos de 24 horas.',
                'sort_order' => 10,
            ],
        ];

        foreach ($sections as $section) {
            HomeSectionSetting::query()->updateOrCreate(
                ['section_key' => $section['section_key']],
                $section + ['is_visible' => true],
            );
        }
    }
}
