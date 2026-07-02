<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Database\Seeders\Concerns\SeedsReferenceImages;
use Illuminate\Database\Seeder;

class ServicesCatalogSeeder extends Seeder
{
    use SeedsReferenceImages;

    public function run(): void
    {
        $categories = ServiceCategory::query()->get()->keyBy('slug');

        $services = [
            [
                'title' => 'Consultoría TI',
                'slug' => 'consultoria-ti',
                'category' => 'consultoria',
                'description' => 'Auditoría de infraestructura, roadmap tecnológico y workshops con stakeholders.',
                'icon' => 'Lightbulb',
                'image' => 'service-consulting',
                'price_label' => 'Desde $2,500',
                'features' => ['Diagnóstico de madurez digital', 'Roadmap 12-24 meses', 'Workshops ejecutivos', 'Informe de hallazgos'],
            ],
            [
                'title' => 'Cloud & DevOps',
                'slug' => 'cloud-devops',
                'category' => 'cloud-devops',
                'description' => 'Migración, arquitectura y operación en AWS, Azure y Google Cloud.',
                'icon' => 'Cloud',
                'image' => 'service-cloud',
                'price_label' => 'Desde $4,500',
                'features' => ['Migración lift-and-shift', 'CI/CD con GitHub Actions', 'Infraestructura como código', 'Monitoreo 24/7'],
            ],
            [
                'title' => 'Desarrollo Web',
                'slug' => 'desarrollo-web',
                'category' => 'desarrollo',
                'description' => 'Aplicaciones web, portales corporativos y APIs REST/GraphQL a medida.',
                'icon' => 'Globe',
                'image' => 'service-web',
                'price_label' => 'Desde $5,500',
                'features' => ['Laravel + React', 'Diseño responsive', 'Integraciones ERP/CRM', 'Soporte post-lanzamiento'],
            ],
            [
                'title' => 'Infraestructura & Hardware',
                'slug' => 'infraestructura-hardware',
                'category' => 'infraestructura',
                'description' => 'Servidores, racks, redes y data centers con instalación y mantenimiento.',
                'icon' => 'Server',
                'image' => 'service-hardware',
                'price_label' => 'Desde $3,200',
                'features' => ['Diseño de rack y cableado', 'Virtualización VMware/Proxmox', 'UPS y redundancia', 'Soporte on-site'],
            ],
            [
                'title' => 'Ciberseguridad',
                'slug' => 'ciberseguridad',
                'category' => 'seguridad',
                'description' => 'Hardening, pentesting, cumplimiento normativo y respuesta a incidentes.',
                'icon' => 'Shield',
                'image' => 'service-security',
                'price_label' => 'Desde $3,800',
                'features' => ['Auditoría de vulnerabilidades', 'Firewall y segmentación', 'Políticas de acceso', 'Plan de respuesta'],
            ],
            [
                'title' => 'Apps Móviles',
                'slug' => 'apps-moviles',
                'category' => 'desarrollo',
                'description' => 'Aplicaciones iOS y Android nativas o cross-platform para operaciones de campo.',
                'icon' => 'Smartphone',
                'image' => 'service-mobile',
                'price_label' => 'Desde $6,500',
                'features' => ['React Native / Flutter', 'GPS y notificaciones push', 'Modo offline', 'Publicación en stores'],
            ],
            [
                'title' => 'DevOps & Automatización',
                'slug' => 'devops-automatizacion',
                'category' => 'cloud-devops',
                'description' => 'Pipelines, contenedores, orquestación Kubernetes y automatización de despliegues.',
                'icon' => 'Workflow',
                'image' => 'service-devops',
                'price_label' => 'Desde $4,200',
                'features' => ['Docker y Kubernetes', 'Terraform / Pulumi', 'GitOps', 'Observabilidad con Grafana'],
            ],
            [
                'title' => 'Soporte Técnico 24/7',
                'slug' => 'soporte-24-7',
                'category' => 'infraestructura',
                'description' => 'Mesa de ayuda, monitoreo proactivo y SLA garantizado para infraestructura crítica.',
                'icon' => 'Headphones',
                'image' => 'service-support',
                'price_label' => 'Desde $1,200/mes',
                'features' => ['SLA 99.9% uptime', 'Mesa de ayuda L1-L3', 'Monitoreo proactivo', 'Escalamiento dedicado'],
            ],
        ];

        foreach ($services as $index => $data) {
            $category = $categories->get($data['category']);

            Service::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'service_category_id' => $category?->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'main_image' => $this->referenceImage($data['image'], 960, 540),
                    'price_label' => $data['price_label'],
                    'features' => $data['features'],
                    'sort_order' => $index + 1,
                    'status' => 'published',
                ],
            );
        }
    }
}
