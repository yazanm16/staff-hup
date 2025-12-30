<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    
    Permission::create(['name' => 'task.view']);
    Permission::create(['name' => 'task.update-status']);

    
    Permission::create(['name' => 'comment.create']);
    Permission::create(['name' => 'comment.delete-own']);
    Permission::create(['name' => 'comment.delete-any']);
    Permission::create(['name' => 'comment.view-deleted']);

    
    Permission::create(['name' => 'role.manage']);

    
    $admin = Role::create(['name' => 'admin']);
    $employee = Role::create(['name' => 'employee']);

    
    $admin->givePermissionTo(Permission::all());

    $employee->givePermissionTo([
        'task.view',
        'task.update-status',
        'comment.create',
        'comment.delete-own',
    ]);
}

}
