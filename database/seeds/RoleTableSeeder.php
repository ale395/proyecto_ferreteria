<?php

use Illuminate\Database\Seeder;
use App\Role;
//use App\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Administrador';
        $role->slug = 'administrador';
        $role->description = 'Administrator del sistema';
        $role->save();

        $role = new Role();
        $role->name = 'Usuario';
        $role->slug = 'usuario';
        $role->description = 'Usuario del sistema';
        $role->save();
    }
}
