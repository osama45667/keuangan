<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage roles',
            'manage coa',
            'manage periods',
            'manage journals',
            'manage family',
            'view family',
            'export family',
            'view reports',
            'export reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $accountant = Role::firstOrCreate(['name' => 'Akuntan']);
        $viewer = Role::firstOrCreate(['name' => 'Viewer']);

        $admin->syncPermissions($permissions);
        $accountant->syncPermissions([
            'manage journals',
            'view reports',
            'export reports',
            'manage coa',
            'manage periods',
            'manage family',
            'view family',
            'export family',
        ]);
        $viewer->syncPermissions([
            'view reports',
            'export reports',
            'manage family',
            'view family',
            'export family',
        ]);
    }
}
