<?php

namespace Database\Seeders;

use App\Models\Settings\RevenueSource;
use Illuminate\Database\Seeder;

class RevenueSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            ['name' => 'Business License Fees', 'type' => 'license', 'description' => 'Fees from business license registration and renewal'],
            ['name' => 'Market Stall Rents', 'type' => 'market', 'description' => 'Rental income from market stalls and cages'],
            ['name' => 'Business Frame Rents', 'type' => 'frame_rent', 'description' => 'Rental income from business frames/vibanda'],
            ['name' => 'Fishing License Fees', 'type' => 'fishery', 'description' => 'Fees from fishing licenses and boat registration'],
            ['name' => 'Advertisement Fees', 'type' => 'other', 'description' => 'Fees from mobile app advertisements'],
            ['name' => 'Penalty Fees', 'type' => 'other', 'description' => 'Late renewal and violation penalties'],
            ['name' => 'Environmental Fees', 'type' => 'other', 'description' => 'Environmental hygiene and cleanliness fees'],
        ];

        foreach ($sources as $source) {
            RevenueSource::firstOrCreate(['name' => $source['name']], $source);
        }
    }
}
