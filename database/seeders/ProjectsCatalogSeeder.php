<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use Database\Seeders\Concerns\SeedsReferenceImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectsCatalogSeeder extends Seeder
{
    use SeedsReferenceImages;

    public function run(): void
    {
        $categories = ProjectCategory::query()->get()->keyBy('slug');

        $projects = [
            [
                'title' => 'ERP Académico Open9',
                'slug' => 'erp-academico-open9',
                'category' => 'software',
                'client' => 'Open9 Labs',
                'short' => 'Plataforma integral de gestión académica con inscripciones, pagos y certificados digitales.',
                'description' => $this->paragraphs(
                    'Desarrollamos un ERP académico que centraliza matrículas, pagos, asistencia y emisión de certificados verificables.',
                    'La arquitectura combina Laravel 12, Livewire y PostgreSQL con colas Redis para procesos asíncronos.',
                    'El panel administrativo permite a coordinadores gestionar cohortes, instructores y reportes en tiempo real.',
                ),
                'stack' => ['Laravel', 'Livewire', 'PostgreSQL', 'Redis', 'Tailwind CSS'],
                'image' => 'erp-dashboard',
                'featured' => true,
                'months_ago_start' => 14,
                'months_ago_end' => 10,
            ],
            [
                'title' => 'E-commerce B2B Andes Tech',
                'slug' => 'ecommerce-b2b-andes-tech',
                'category' => 'ecommerce',
                'client' => 'Andes Tech S.A.C.',
                'short' => 'Catálogo mayorista con precios por volumen, cotizaciones y integración SAP.',
                'description' => $this->paragraphs(
                    'Portal B2B con catálogo de más de 2.400 SKUs, listas de precios por cliente y flujo de cotización aprobada.',
                    'Integración bidireccional con SAP mediante API REST y sincronización nocturna de inventario.',
                    'Checkout corporativo con líneas de crédito, facturación electrónica SUNAT y seguimiento de pedidos.',
                ),
                'stack' => ['Laravel', 'React', 'PostgreSQL', 'SAP B1', 'AWS'],
                'image' => 'ecommerce',
                'featured' => true,
                'months_ago_start' => 11,
                'months_ago_end' => 7,
            ],
            [
                'title' => 'Portal de Analítica Cloud',
                'slug' => 'portal-analitica-cloud',
                'category' => 'cloud-devops',
                'client' => 'Data Norte',
                'short' => 'Dashboards ejecutivos con datos en tiempo real desde múltiples fuentes cloud.',
                'description' => $this->paragraphs(
                    'Consolidamos métricas de ventas, operaciones y logística en un portal unificado con actualización cada 5 minutos.',
                    'Pipeline ETL en AWS Glue alimenta un data warehouse en Redshift consumido por Grafana y vistas custom.',
                    'Autenticación SSO con Azure AD y permisos granulares por área y región.',
                ),
                'stack' => ['AWS', 'Redshift', 'Grafana', 'Laravel', 'React'],
                'image' => 'analytics',
                'featured' => true,
                'months_ago_start' => 9,
                'months_ago_end' => 5,
            ],
            [
                'title' => 'Automatización de Ventas',
                'slug' => 'automatizacion-ventas',
                'category' => 'automatizacion',
                'client' => 'Ventas Pro',
                'short' => 'Flujos automatizados de leads, CRM y notificaciones multicanal.',
                'description' => $this->paragraphs(
                    'Automatizamos el ciclo comercial desde la captura del lead hasta el cierre con reglas de asignación por territorio.',
                    'Integración con WhatsApp Business API, correo transaccional y webhooks hacia el CRM existente.',
                    'Reducción del 40% en tiempo de respuesta inicial y trazabilidad completa de cada interacción.',
                ),
                'stack' => ['Laravel', 'n8n', 'PostgreSQL', 'WhatsApp API', 'SendGrid'],
                'image' => 'automation',
                'featured' => false,
                'months_ago_start' => 8,
                'months_ago_end' => 4,
            ],
            [
                'title' => 'Migración AWS RetailMax',
                'slug' => 'migracion-aws-retailmax',
                'category' => 'cloud-devops',
                'client' => 'RetailMax Perú',
                'short' => 'Lift-and-shift controlado de 12 servicios on-premise a AWS con alta disponibilidad.',
                'description' => $this->paragraphs(
                    'Plan de migración en tres fases: assessment, piloto y cutover con rollback documentado.',
                    'Arquitectura multi-AZ con ALB, ECS Fargate, RDS PostgreSQL y CloudFront para assets estáticos.',
                    'Costos de infraestructura reducidos un 28% frente al data center heredado.',
                ),
                'stack' => ['AWS', 'ECS', 'RDS', 'Terraform', 'CloudWatch'],
                'image' => 'cloud-dashboard',
                'featured' => true,
                'months_ago_start' => 10,
                'months_ago_end' => 6,
            ],
            [
                'title' => 'Red Corporativa Clínica San Martín',
                'slug' => 'red-corporativa-clinica-san-martin',
                'category' => 'infraestructura',
                'client' => 'Clínica San Martín',
                'short' => 'Diseño e implementación de red segmentada para entorno hospitalario.',
                'description' => $this->paragraphs(
                    'Segmentación VLAN por área clínica, administrativa y invitados con políticas de firewall zero-trust.',
                    'Despliegue de switches managed PoE+, UPS online y monitoreo SNMP centralizado.',
                    'Cumplimiento de requisitos de continuidad operativa y respaldo de enlaces críticos.',
                ),
                'stack' => ['Cisco', 'Fortinet', 'SNMP', 'VLAN', 'UPS'],
                'image' => 'network-switch',
                'featured' => false,
                'months_ago_start' => 7,
                'months_ago_end' => 5,
            ],
            [
                'title' => 'App Logística Pacífico Express',
                'slug' => 'app-logistica-pacifico-express',
                'category' => 'software',
                'client' => 'Pacífico Express',
                'short' => 'App móvil para conductores con tracking GPS y firma digital de entregas.',
                'description' => $this->paragraphs(
                    'Aplicación React Native para conductores con rutas optimizadas, escaneo de guías y evidencia fotográfica.',
                    'Backend Laravel expone API REST con geolocalización en tiempo real y notificaciones push.',
                    'Panel de torre de control para despacho con mapa en vivo y alertas de desvío de ruta.',
                ),
                'stack' => ['React Native', 'Laravel', 'PostgreSQL', 'Firebase', 'Google Maps'],
                'image' => 'logistics',
                'featured' => false,
                'months_ago_start' => 6,
                'months_ago_end' => 2,
            ],
            [
                'title' => 'Plataforma Fintech PayAndes',
                'slug' => 'plataforma-fintech-payandes',
                'category' => 'software',
                'client' => 'PayAndes',
                'short' => 'Pasarela de pagos y conciliación bancaria para comercios minoristas.',
                'description' => $this->paragraphs(
                    'Plataforma de pagos con soporte Yape, Plin, tarjetas y transferencias con conciliación automática.',
                    'Cumplimiento PCI-DSS nivel 2, tokenización de tarjetas y auditoría de transacciones.',
                    'Dashboard de comercios con reportes diarios, liquidaciones y API para integraciones POS.',
                ),
                'stack' => ['Laravel', 'React', 'PostgreSQL', 'Redis', 'Stripe'],
                'image' => 'fintech',
                'featured' => true,
                'months_ago_start' => 12,
                'months_ago_end' => 8,
            ],
        ];

        foreach ($projects as $index => $data) {
            $category = $categories->get($data['category']) ?? $categories->first();

            Project::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'project_category_id' => $category->id,
                    'title' => $data['title'],
                    'short_description' => $data['short'],
                    'description' => $data['description'],
                    'client_name' => $data['client'],
                    'technology_stack' => $data['stack'],
                    'main_image' => $this->referenceImage($data['image'], 1200, 675),
                    'gallery' => [
                        $this->referenceImage($data['image'], 800, 500),
                        $this->referenceImage('team-dev', 800, 500),
                    ],
                    'project_url' => 'https://open9.dev/proyectos/'.$data['slug'],
                    'start_date' => now()->subMonths($data['months_ago_start']),
                    'end_date' => now()->subMonths($data['months_ago_end']),
                    'status' => 'published',
                    'is_featured' => $data['featured'],
                    'views_count' => 180 + ($index * 42),
                    'seo_title' => $data['title'].' | OPEN9',
                    'seo_description' => $data['short'],
                    'seo_keywords' => implode(', ', array_slice($data['stack'], 0, 4)).', open9',
                    'published_at' => now()->subMonths($data['months_ago_end'])->subWeeks(2),
                ],
            );
        }

        $validSlugs = collect($projects)->pluck('slug')->all();
        Project::query()
            ->whereNotIn('slug', $validSlugs)
            ->update(['status' => 'draft', 'is_featured' => false]);
    }
}
