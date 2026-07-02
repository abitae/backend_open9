<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'site', 'key' => 'name', 'value' => 'Open9', 'type' => 'string', 'is_public' => true],
            ['group' => 'site', 'key' => 'tagline', 'value' => 'Tecnologia, cursos y proyectos', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'email', 'value' => 'contacto@open9.dev', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'phone', 'value' => '', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'description', 'value' => 'Plataforma de tecnologia, cursos, proyectos y blog.', 'type' => 'text', 'is_public' => true],
            ['group' => 'social', 'key' => 'links', 'value' => json_encode([]), 'type' => 'json', 'is_public' => true],
            ['group' => 'store', 'key' => 'usd_pen_rate', 'value' => '3.75', 'type' => 'number', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
