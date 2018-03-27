<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Permisos
        $permission = new Permission();
        $permission->name = 'create_permission';
        $permission->description = 'Crear Permiso';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'delete_permission';
        $permission->description = 'Borrar Permiso';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'update_permission';
        $permission->description = 'Modificar Permiso';
        $permission->save();

		$permission = new Permission();
        $permission->name = 'read_permission';
        $permission->description = 'Ver Permiso';
        $permission->save();

    	//Usuarios
        $permission = new Permission();
        $permission->name = 'create_user';
        $permission->description = 'Crear Usuarios';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'delete_usuario';
        $permission->description = 'Borrar Usuarios';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'update_usuario';
        $permission->description = 'Modificar Usuarios';
        $permission->save();

		$permission = new Permission();
        $permission->name = 'read_usuario';
        $permission->description = 'Ver Usuarios';
        $permission->save();

    }
}
