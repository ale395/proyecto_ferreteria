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
        $role_user = Role::where('slug', 'operador')->first();
        $role_admin = Role::where('slug', 'administrador')->first();

        $user = new User();
        $user->name = 'Alexis Fernández';
        $user->email = 'alexis.fernandez.rc@gmail.com';
        $user->password = bcrypt('afernandez');
        $user->save();
        $user->roles()->attach($role_user);

        $user = new User();
        $user->name = 'Yanina Sosa';
        $user->email = 'yani_rsc@hotmail.com';
        $user->password = bcrypt('Belen28');
        $user->save();
        $user->roles()->attach($role_admin);

        //usuario administrador del sistema
        $user = new User();
        $user->name = 'Administrador';
        $user->email = 'admin@ferregest.com';
        $user->password = bcrypt('administrador');
        $user->save();
        $user->roles()->attach($role_admin);

        //usuario generico
        $user = new User();
        $user->name = 'Usuario';
        $user->email = 'usuario@ferregest.com';
        $user->password = bcrypt('usuario');
        $user->save();
        $user->roles()->attach($role_user);

        factory(User::class, 500)->create();

    }
}
