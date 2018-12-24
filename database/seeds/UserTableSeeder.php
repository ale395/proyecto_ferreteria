<?php

use App\User;
use App\Role;
use App\Empleado;
use Illuminate\Database\Seeder;


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

        $empleado1 = Empleado::where('correo_electronico', 'alexis.fernandez.rc@gmail.com')->first();
        $empleado2 = Empleado::where('correo_electronico', 'yani_rsc@hotmail.com')->first();
        $empleado3 = Empleado::where('correo_electronico', 'admin@ferregest.com')->first();
        $empleado4 = Empleado::where('correo_electronico', 'usuario@ferregest.com')->first();

        $user = new User();
        $user->name = 'Alexis Fernández';
        $user->email = 'alexis.fernandez.rc@gmail.com';
        $user->password = bcrypt('afernandez');
        $user->role_id = $role_admin->id;
        $user->empleado_id = $empleado1->id;
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Yanina Sosa';
        $user->email = 'yani_rsc@hotmail.com';
        $user->password = bcrypt('123');
        $user->role_id = $role_admin->id;
        $user->empleado_id = $empleado2->id;
        $user->save();
        $user->roles()->attach($role_admin);

        //usuario administrador del sistema
        $user = new User();
        $user->name = 'Administrador';
        $user->email = 'admin@ferregest.com';
        $user->password = bcrypt('administrador');
        $user->role_id = $role_admin->id;
        $user->empleado_id = $empleado3->id;
        $user->save();
        $user->roles()->attach($role_admin);

        //usuario generico
        $user = new User();
        $user->name = 'Usuario';
        $user->email = 'usuario@ferregest.com';
        $user->password = bcrypt('usuario');
        $user->role_id = $role_user->id;
        $user->empleado_id = $empleado4->id;
        $user->save();
        $user->roles()->attach($role_user);

        //factory(User::class, 500)->create();

    }
}
