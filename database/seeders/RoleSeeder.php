<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleOperator = Role::create(['name' => 'Operator']);

        foreach (config('permission.list_permissions') as $permission) {
            foreach ($permission['lists'] as $list) {
                Permission::create(['name' => $list]);
            }
        }

        $userSuperAdmin = User::first();
        $userSuperAdmin->assignRole('super admin');
        $roleSuperAdmin->givePermissionTo(Permission::all());
    }
}
