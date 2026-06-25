<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@bagamoyo.go.tz'],
            [
                'name' => 'System Administrator',
                'phone' => '+255712345678',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'manager@bagamoyo.go.tz'],
            [
                'name' => 'Municipal Manager',
                'phone' => '+255712345679',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['Manager']);

        // Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@bagamoyo.go.tz'],
            [
                'name' => 'Staff Member',
                'phone' => '+255712345680',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $staff->syncRoles(['Staff']);
    }
}
