<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Web Apps', 'Ecommerce', 'SaaS', 'Automatizacion'] as $index => $name) {
            ProjectCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 'active', 'sort_order' => $index + 1]
            );
        }
    }
}
