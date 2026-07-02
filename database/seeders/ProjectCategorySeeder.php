<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cloud & DevOps', 'slug' => 'cloud-devops', 'description' => 'Migraciones, Kubernetes y plataformas en la nube.'],
            ['name' => 'Software a medida', 'slug' => 'software', 'description' => 'Aplicaciones web, APIs y portales corporativos.'],
            ['name' => 'Infraestructura', 'slug' => 'infraestructura', 'description' => 'Servidores, redes y data centers.'],
            ['name' => 'E-commerce', 'slug' => 'ecommerce', 'description' => 'Tiendas B2B/B2C y catálogos digitales.'],
            ['name' => 'Automatización', 'slug' => 'automatizacion', 'description' => 'Integraciones, RPA y flujos operativos.'],
        ];

        foreach ($categories as $index => $data) {
            ProjectCategory::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['sort_order' => $index + 1, 'status' => 'active'],
            );
        }
    }
}
