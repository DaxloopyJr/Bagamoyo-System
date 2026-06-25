<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard_view',

            // Licenses
            'license_view', 'license_create', 'license_edit', 'license_delete', 'license_export',

            // Fishery
            'fishery_view', 'fishery_create', 'fishery_edit', 'fishery_delete',

            // Markets
            'market_view', 'market_create', 'market_edit', 'market_delete',

            // Business Frames
            'frame_view', 'frame_create', 'frame_edit', 'frame_delete',

            // SMS
            'sms_send', 'sms_view_logs',

            // Reports
            'report_view',

            // Users
            'user_view', 'user_create', 'user_edit', 'user_delete',

            // Roles & Permissions
            'role_view', 'role_create', 'role_edit', 'role_delete',
            'permission_view', 'permission_create', 'permission_edit', 'permission_delete',

            // Settings
            'setting_view', 'setting_edit',

            // Mobile App
            'mobile_app_view', 'mobile_app_manage',

            // Activity Logs
            'log_view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Super Admin role
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        // Create Admin role
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        // Create Manager role (limited)
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'dashboard_view', 'license_view', 'license_create', 'license_edit',
            'fishery_view', 'fishery_create', 'fishery_edit',
            'market_view', 'market_create', 'market_edit',
            'frame_view', 'frame_create', 'frame_edit',
            'sms_send', 'sms_view_logs', 'report_view', 'log_view',
        ]);

        // Create Staff role (view only + create)
        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'dashboard_view', 'license_view', 'license_create',
            'fishery_view', 'fishery_create',
            'market_view', 'market_create',
            'frame_view', 'frame_create',
            'sms_send', 'report_view',
        ]);
    }
}
