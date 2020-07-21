<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('testtest')
        ]);

        $admin_role = Role::create(['name' => 'Admin']);
        $user_role = Role::create(['name'=> 'User']);

        $permissions = Permission::pluck('id','id')->all();

        $admin_role->syncPermissions($permissions);

        $user->assignRole([$admin_role->id]);
    }
}
