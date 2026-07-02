<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Consultoría', 'slug' => 'consultoria', 'description' => 'Diagnóstico, arquitectura y roadmap tecnológico.'],
            ['name' => 'Cloud & DevOps', 'slug' => 'cloud-devops', 'description' => 'Migración, CI/CD y operación en AWS, Azure y GCP.'],
            ['name' => 'Desarrollo', 'slug' => 'desarrollo', 'description' => 'Software web, mobile e integraciones a medida.'],
            ['name' => 'Infraestructura', 'slug' => 'infraestructura', 'description' => 'Hardware, servidores, redes y data centers.'],
            ['name' => 'Seguridad', 'slug' => 'seguridad', 'description' => 'Hardening, cumplimiento y respuesta a incidentes.'],
        ];

        foreach ($categories as $index => $data) {
            ServiceCategory::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['sort_order' => $index + 1, 'status' => 'active'],
            );
        }
    }
}
