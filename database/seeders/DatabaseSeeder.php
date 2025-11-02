<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed email templates, payment gateways, and system settings
        $this->call([
            EmailTemplateSeeder::class,
            PaySeeder::class,
            SystemSettingSeeder::class,
        ]);
    }
}
