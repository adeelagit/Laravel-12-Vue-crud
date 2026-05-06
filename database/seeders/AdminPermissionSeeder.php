<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Admin;


class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // =========================================
        // Define all admin permissions
        // =========================================


        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Role permissions
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'admin'],
                ['guard_name' => 'admin']
            );
        }
        
        
        // =========================================
        // Create Super Admin Role
        // =========================================

        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'admin'],
            ['guard_name' => 'admin']
        );

        // Give all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());

        // =========================================
        // Optional: Assign to specific admin user
        // =========================================

        $admin = Admin::where('email', 'admin@mail.com')->first();

        if ($admin) {
            $admin->assignRole('Super Admin');
        }
    }
}
