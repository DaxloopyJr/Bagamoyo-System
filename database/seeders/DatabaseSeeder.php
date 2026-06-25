<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RegionsTableSeeder::class,
            DistrictsTableSeeder::class,
            WardsTableSeeder::class,
            VillagesTableSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            LicenseCategorySeeder::class,
            RevenueSourceSeeder::class,
        ]);
    }
}
