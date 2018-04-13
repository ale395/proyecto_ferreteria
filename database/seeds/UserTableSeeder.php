<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('slug', 'usuario')->first();
        $role_admin = Role::where('slug', 'administrador')->first();

        /*$role_user->assignPermission(4);
        $role_user->assignPermission(3);
        $role_user->assignPermission(2);
        $role_user->assignPermission(1);
        $role_user->save();*/

        /*$user = new User();
        $user->name = 'User';
        $user->email = 'user@ferregest.com';
        $user->password = bcrypt('user');
        $user->save();
        $user->roles()->attach($role_user);*/

        //$user = User::find(1);
        //$user->roles()->attach($role_user);

        $role_user->revokePermission(2);
        //$role_user->assignPermission(4);

        /*$user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@ferregest.com';
        $user->password = bcrypt('admin');
        $user->save();
        $user->roles()->attach($role_admin);*/
    }
}
