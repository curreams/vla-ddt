<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::create(['name' => 'Admin']);
        $user_role = Role::create(['name'=> 'User']);

        $permissions = Permission::pluck('id','id')->all();

        $admin_role->syncPermissions($permissions);
        $user_role->syncPermissions($permissions);
    }
}
