<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Database\Seeders\Concerns\SeedsReferenceImages;
use Illuminate\Database\Seeder;

class StoreCatalogSeeder extends Seeder
{
    use SeedsReferenceImages;

    public function run(): void
    {
        $categories = $this->seedCategories();
        $this->seedProducts($categories);
    }

    /**
     * @return array<string, ProductCategory>
     */
    private function seedCategories(): array
    {
        $definitions = [
            ['name' => 'Hardware', 'slug' => 'hardware', 'description' => 'Servidores, racks y equipos de red.'],
            ['name' => 'Software', 'slug' => 'software', 'description' => 'Licencias y soluciones empresariales.'],
            ['name' => 'Cloud', 'slug' => 'cloud', 'description' => 'Paquetes gestionados AWS, Azure y Google Cloud.'],
            ['name' => 'Accesorios', 'slug' => 'accesorios', 'description' => 'Periféricos y componentes de infraestructura.'],
        ];

        $categories = [];
        foreach ($definitions as $index => $data) {
            $categories[$data['slug']] = ProductCategory::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['sort_order' => $index + 1, 'status' => 'active'],
            );
        }

        return $categories;
    }

    /**
     * @param  array<string, ProductCategory>  $categories
     */
    private function seedProducts(array $categories): void
    {
        $products = [
            [
                'name' => 'Servidor Rack 2U',
                'slug' => 'servidor-rack-2u',
                'category' => 'hardware',
                'price' => 4500,
                'description' => 'Servidor empresarial de alto rendimiento con redundancia de fuentes.',
                'badge' => 'Nuevo',
                'rating' => 4.8,
                'stock' => 10,
                'seed' => 'open9-product-rack',
            ],
            [
                'name' => 'Licencia Cloud Pro',
                'slug' => 'licencia-cloud-pro',
                'category' => 'cloud',
                'price' => 299,
                'description' => 'Paquete mensual de servicios cloud gestionados con soporte 24/7.',
                'badge' => 'Popular',
                'rating' => 4.5,
                'stock' => 100,
                'seed' => 'open9-product-cloud-pro',
            ],
            [
                'name' => 'Switch Managed 48 puertos',
                'slug' => 'switch-managed-48',
                'category' => 'hardware',
                'price' => 1890,
                'description' => 'Switch capa 3 con PoE+ para centros de datos y oficinas.',
                'badge' => null,
                'rating' => 4.6,
                'stock' => 25,
                'seed' => 'open9-product-switch',
            ],
            [
                'name' => 'NAS Empresarial 8 bahías',
                'slug' => 'nas-empresarial-8',
                'category' => 'hardware',
                'price' => 3200,
                'description' => 'Almacenamiento en red con RAID y replicación off-site.',
                'badge' => 'Oferta',
                'rating' => 4.7,
                'stock' => 8,
                'seed' => 'open9-product-nas',
            ],
            [
                'name' => 'Suite DevOps Anual',
                'slug' => 'suite-devops-anual',
                'category' => 'software',
                'price' => 1200,
                'description' => 'Licencia anual: CI/CD, monitoreo y gestión de contenedores.',
                'badge' => null,
                'rating' => 4.4,
                'stock' => 50,
                'seed' => 'open9-product-devops',
            ],
            [
                'name' => 'Firewall UTM Pro',
                'slug' => 'firewall-utm-pro',
                'category' => 'software',
                'price' => 890,
                'description' => 'Protección perimetral con IPS, VPN y filtrado web.',
                'badge' => 'Seguridad',
                'rating' => 4.9,
                'stock' => 30,
                'seed' => 'open9-product-firewall',
            ],
            [
                'name' => 'Kit Cableado Cat6A',
                'slug' => 'kit-cableado-cat6a',
                'category' => 'accesorios',
                'price' => 450,
                'description' => '100m de cable, conectores y patch panel para instalaciones.',
                'badge' => null,
                'rating' => 4.3,
                'stock' => 60,
                'seed' => 'open9-product-cables',
            ],
            [
                'name' => 'UPS Online 3KVA',
                'slug' => 'ups-online-3kva',
                'category' => 'accesorios',
                'price' => 980,
                'description' => 'Sistema de respaldo de energía para servidores críticos.',
                'badge' => 'Esencial',
                'rating' => 4.6,
                'stock' => 15,
                'seed' => 'open9-product-ups',
            ],
            [
                'name' => 'Créditos AWS Starter',
                'slug' => 'creditos-aws-starter',
                'category' => 'cloud',
                'price' => 500,
                'description' => 'Paquete inicial de créditos AWS con onboarding guiado.',
                'badge' => 'Cloud',
                'rating' => 4.5,
                'stock' => 200,
                'seed' => 'open9-product-aws',
            ],
            [
                'name' => 'Workstation Dev Pro',
                'slug' => 'workstation-dev-pro',
                'category' => 'hardware',
                'price' => 2100,
                'description' => 'Estación de trabajo para desarrollo con 32GB RAM y SSD NVMe.',
                'badge' => null,
                'rating' => 4.7,
                'stock' => 12,
                'seed' => 'open9-product-workstation',
            ],
        ];

        foreach ($products as $index => $data) {
            $category = $categories[$data['category']];

            Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'product_category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'currency' => 'USD',
                    'stock' => $data['stock'],
                    'badge' => $data['badge'],
                    'rating' => $data['rating'],
                    'main_image' => $this->referenceImage($data['seed'], 640, 480),
                    'gallery' => [
                        $this->referenceImage($data['seed'].'-alt', 640, 480),
                    ],
                    'sort_order' => $index + 1,
                    'status' => 'published',
                ],
            );
        }
    }
}
