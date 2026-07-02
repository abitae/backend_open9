<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Laravel', 'Frontend', 'DevOps', 'Carrera Tech'] as $index => $name) {
            BlogCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 'active', 'sort_order' => $index + 1]
            );
        }
    }
}
