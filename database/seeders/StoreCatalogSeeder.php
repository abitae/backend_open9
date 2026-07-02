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
            ['name' => 'Hardware', 'slug' => 'hardware', 'description' => 'Servidores, racks, switches y equipos de red empresarial.'],
            ['name' => 'Software', 'slug' => 'software', 'description' => 'Licencias, suites DevOps y soluciones de seguridad.'],
            ['name' => 'Cloud', 'slug' => 'cloud', 'description' => 'Paquetes gestionados AWS, Azure y Google Cloud.'],
            ['name' => 'Accesorios', 'slug' => 'accesorios', 'description' => 'Cableado, UPS y componentes de infraestructura.'],
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
                'name' => 'Servidor Rack 2U Xeon',
                'slug' => 'servidor-rack-2u',
                'category' => 'hardware',
                'price' => 4500,
                'description' => 'Servidor 2U con dual Xeon, 128 GB RAM, 4× NVMe 1.92 TB y iDRAC. Ideal para virtualización y bases de datos.',
                'badge' => 'Nuevo',
                'rating' => 4.8,
                'stock' => 10,
                'image' => 'product-rack',
            ],
            [
                'name' => 'Licencia Cloud Pro',
                'slug' => 'licencia-cloud-pro',
                'category' => 'cloud',
                'price' => 299,
                'description' => 'Paquete mensual: monitoreo 24/7, backups automatizados y soporte L2 en AWS, Azure o GCP.',
                'badge' => 'Popular',
                'rating' => 4.5,
                'stock' => 100,
                'image' => 'product-cloud-pro',
            ],
            [
                'name' => 'Switch Managed 48 puertos PoE+',
                'slug' => 'switch-managed-48',
                'category' => 'hardware',
                'price' => 1890,
                'description' => 'Switch capa 3, 48 puertos Gigabit PoE+, 4× SFP+ 10G. Gestión web, CLI y SNMP.',
                'badge' => null,
                'rating' => 4.6,
                'stock' => 25,
                'image' => 'product-switch',
            ],
            [
                'name' => 'NAS Empresarial 8 bahías',
                'slug' => 'nas-empresarial-8',
                'category' => 'hardware',
                'price' => 3200,
                'description' => 'Almacenamiento en red con RAID 6, replicación snapshot y acceso SMB/NFS/iSCSI.',
                'badge' => 'Oferta',
                'rating' => 4.7,
                'stock' => 8,
                'image' => 'product-nas',
            ],
            [
                'name' => 'Suite DevOps Anual',
                'slug' => 'suite-devops-anual',
                'category' => 'software',
                'price' => 1200,
                'description' => 'Licencia anual: CI/CD, registry de contenedores, monitoreo y gestión de secrets para hasta 25 desarrolladores.',
                'badge' => null,
                'rating' => 4.4,
                'stock' => 50,
                'image' => 'product-devops',
            ],
            [
                'name' => 'Firewall UTM Pro',
                'slug' => 'firewall-utm-pro',
                'category' => 'software',
                'price' => 890,
                'description' => 'Appliance virtual o físico con IPS, VPN site-to-site, filtrado web y sandboxing de archivos.',
                'badge' => 'Seguridad',
                'rating' => 4.9,
                'stock' => 30,
                'image' => 'product-firewall',
            ],
            [
                'name' => 'Kit Cableado Cat6A 100m',
                'slug' => 'kit-cableado-cat6a',
                'category' => 'accesorios',
                'price' => 450,
                'description' => '100 m cable Cat6A, 48 conectores RJ45, patch panel 24 puertos y organizadores de rack.',
                'badge' => null,
                'rating' => 4.3,
                'stock' => 60,
                'image' => 'product-cables',
            ],
            [
                'name' => 'UPS Online 3KVA',
                'slug' => 'ups-online-3kva',
                'category' => 'accesorios',
                'price' => 980,
                'description' => 'Sistema de doble conversión online, 3 KVA/2700 W, baterías hot-swap y puerto SNMP.',
                'badge' => 'Esencial',
                'rating' => 4.6,
                'stock' => 15,
                'image' => 'product-ups',
            ],
            [
                'name' => 'Créditos AWS Starter',
                'slug' => 'creditos-aws-starter',
                'category' => 'cloud',
                'price' => 500,
                'description' => 'USD 500 en créditos AWS + 8 horas de onboarding: VPC, IAM, RDS y despliegue inicial.',
                'badge' => 'Cloud',
                'rating' => 4.5,
                'stock' => 200,
                'image' => 'product-aws',
            ],
            [
                'name' => 'Workstation Dev Pro',
                'slug' => 'workstation-dev-pro',
                'category' => 'hardware',
                'price' => 2100,
                'description' => 'Ryzen 9, 32 GB RAM, SSD NVMe 1 TB, GPU dedicada. Preconfigurada para Docker y desarrollo full-stack.',
                'badge' => null,
                'rating' => 4.7,
                'stock' => 12,
                'image' => 'product-workstation',
            ],
            [
                'name' => 'Cluster Kubernetes Gestionado',
                'slug' => 'cluster-kubernetes-gestionado',
                'category' => 'cloud',
                'price' => 850,
                'description' => 'Cluster managed 3 nodos con ingress, cert-manager, backups y actualizaciones mensuales incluidas.',
                'badge' => 'Nuevo',
                'rating' => 4.6,
                'stock' => 40,
                'image' => 'kubernetes',
            ],
            [
                'name' => 'Licencia Endpoint Security',
                'slug' => 'licencia-endpoint-security',
                'category' => 'software',
                'price' => 45,
                'description' => 'Protección EDR por endpoint/año: detección de amenazas, aislamiento y panel centralizado.',
                'badge' => null,
                'rating' => 4.8,
                'stock' => 500,
                'image' => 'product-firewall',
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
                    'main_image' => $this->referenceImage($data['image'], 800, 600),
                    'gallery' => [
                        $this->referenceImage($data['image'], 640, 480),
                        $this->referenceImage('datacenter', 640, 480),
                    ],
                    'sort_order' => $index + 1,
                    'status' => 'published',
                ],
            );
        }
    }
}
