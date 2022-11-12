<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class rolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // create permissions
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage profile']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'patient']);
        $role->attachPermission('manage profile');

        // or may be done by chaining
        $role = Role::create(['name' => 'doctor']);
        $role->attachPermission('manage profile');

        $role = Role::create(['name' => 'owner']);
        $role->syncPermissions(Permission::all());
    }
}
