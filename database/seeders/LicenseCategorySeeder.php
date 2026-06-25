<?php

namespace Database\Seeders;

use App\Models\License\LicenseCategory;
use Illuminate\Database\Seeder;

class LicenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Retail Shop', 'code' => 'RETAIL', 'description' => 'Small retail businesses and shops', 'default_fee' => 50000],
            ['name' => 'Wholesale Business', 'code' => 'WHOLESALE', 'description' => 'Wholesale trading businesses', 'default_fee' => 100000],
            ['name' => 'Restaurant/Hotel', 'code' => 'HOSPITALITY', 'description' => 'Food service and accommodation', 'default_fee' => 150000],
            ['name' => 'Transport Service', 'code' => 'TRANSPORT', 'description' => 'Transport and logistics services', 'default_fee' => 80000],
            ['name' => 'Professional Service', 'code' => 'PROFESSIONAL', 'description' => 'Professional and consultancy services', 'default_fee' => 120000],
            ['name' => 'Manufacturing', 'code' => 'MANUFACTURING', 'description' => 'Manufacturing and processing', 'default_fee' => 200000],
            ['name' => 'Construction', 'code' => 'CONSTRUCTION', 'description' => 'Construction and building services', 'default_fee' => 180000],
            ['name' => 'Agriculture/Fishing', 'code' => 'AGRI_FISH', 'description' => 'Agricultural and fishing businesses', 'default_fee' => 30000],
            ['name' => 'Health Service', 'code' => 'HEALTH', 'description' => 'Health and medical services', 'default_fee' => 150000],
            ['name' => 'Education Service', 'code' => 'EDUCATION', 'description' => 'Educational and training services', 'default_fee' => 80000],
            ['name' => 'Entertainment', 'code' => 'ENTERTAIN', 'description' => 'Entertainment and recreation', 'default_fee' => 100000],
            ['name' => 'Financial Service', 'code' => 'FINANCE', 'description' => 'Financial and insurance services', 'default_fee' => 250000],
        ];

        foreach ($categories as $cat) {
            LicenseCategory::firstOrCreate(['code' => $cat['code']], $cat);
        }
    }
}
