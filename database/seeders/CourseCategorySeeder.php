<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Programacion', 'Cloud', 'Data', 'Ciberseguridad'] as $index => $name) {
            CourseCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 'active', 'sort_order' => $index + 1]
            );
        }
    }
}
