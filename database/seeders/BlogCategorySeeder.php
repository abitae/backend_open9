<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cloud', 'slug' => 'cloud', 'description' => 'AWS, Azure, Google Cloud y arquitectura híbrida.'],
            ['name' => 'DevOps', 'slug' => 'devops', 'description' => 'CI/CD, contenedores, monitoreo e infraestructura como código.'],
            ['name' => 'Desarrollo', 'slug' => 'desarrollo', 'description' => 'Laravel, React, APIs y buenas prácticas de software.'],
            ['name' => 'Hardware', 'slug' => 'hardware', 'description' => 'Servidores, redes y equipamiento de data center.'],
            ['name' => 'Seguridad', 'slug' => 'seguridad', 'description' => 'Ciberseguridad, cumplimiento y hardening.'],
            ['name' => 'Carrera Tech', 'slug' => 'carrera-tech', 'description' => 'Formación, liderazgo y tendencias del sector.'],
        ];

        foreach ($categories as $index => $data) {
            BlogCategory::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['sort_order' => $index + 1, 'status' => 'active'],
            );
        }
    }
}
