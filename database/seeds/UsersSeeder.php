<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::pluck('id','id')->all();
        $admin_role =Role::firstWhere('name', 'Admin');
        $user_role = Role::firstWhere('name', 'User');
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('DDTVLA2021')
        ]);

        $user->assignRole([$admin_role->id]);

        $userP = User::create([
            'name' => 'Peter O\'Donnell',
            'email' => 'peter.odonnell@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userP->assignRole([$user_role->id]);

        $userA = User::create([
            'name' => 'Alethea Belford',
            'email' => 'alethea.belford@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userA->assignRole([$user_role->id]);

        $userPe = User::create([
            'name' => 'Peter Noble',
            'email' => 'peter.noble@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userPe->assignRole([$user_role->id]);

        $userM = User::create([
            'name' => 'Marie Baird',
            'email' => 'marie.baird@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userM->assignRole([$user_role->id]);

        $userK = User::create([
            'name' => 'Kasia Pawlikowski',
            'email' => 'kasia.pawlikowski@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userK->assignRole([$user_role->id]);

        $userAn = User::create([
            'name' => 'Annie Mereos',
            'email' => 'annie.mereos@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userAn->assignRole([$user_role->id]);

        $userO = User::create([
            'name' => 'Olivia Bakyew',
            'email' => 'olivia.bakyew@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userO->assignRole([$user_role->id]);

        $userT = User::create([
            'name' => 'Tilda Hum',
            'email' => 'tilda.hum@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userT->assignRole([$user_role->id]);

        $userAm = User::create([
            'name' => 'Amy Schwebel',
            'email' => 'amy.schwebel@vla.vic.gov.au',
            'password' => bcrypt('VLADDT2021')
        ]);

        $userAm->assignRole([$user_role->id]);

    }
}
