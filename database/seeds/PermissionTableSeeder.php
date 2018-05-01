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
    	//Permisos para el formulario de roles
        $permission = new Permission();
        $permission->name = 'Crear Rol';
        $permission->slug = 'roles.create';
        $permission->description = 'Permite la creación de nuevos roles en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Rol';
        $permission->slug = 'roles.destroy';
        $permission->description = 'Permite la eliminación de roles del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Rol';
        $permission->slug = 'roles.edit';
        $permission->description = 'Permite modificar los valores de un rol del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Roles';
        $permission->slug = 'roles.index';
        $permission->description = 'Permite ver el listado de roles del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Rol';
        $permission->slug = 'roles.show';
        $permission->description = 'Permite ver un rol del sistema';
        $permission->save();*/


        //Permisos para el formulario de módulos
        $permission = new Permission();
        $permission->name = 'Crear Módulo';
        $permission->slug = 'modulos.create';
        $permission->description = 'Permite la creación de nuevos módulos en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Módulo';
        $permission->slug = 'modulos.destroy';
        $permission->description = 'Permite la eliminación de módulos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Módulo';
        $permission->slug = 'modulos.edit';
        $permission->description = 'Permite modificar los valores de un módulo del sistema';
        $permission->save();

		$permission = new Permission();
        $permission->name = 'Listar Módulos';
        $permission->slug = 'modulos.index';
        $permission->description = 'Permite ver el listado de módulos del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Módulo';
        $permission->slug = 'modulos.show';
        $permission->description = 'Permite ver un módulo del sistema';
        $permission->save();*/

        //Permisos para el formulario de familias
        $permission = new Permission();
        $permission->name = 'Crear Familia';
        $permission->slug = 'familias.create';
        $permission->description = 'Permite la creación de nuevas familias en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Familia';
        $permission->slug = 'familias.destroy';
        $permission->description = 'Permite la eliminación de familias del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Familia';
        $permission->slug = 'familias.edit';
        $permission->description = 'Permite modificar los valores de una familia';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Familias';
        $permission->slug = 'familias.index';
        $permission->description = 'Permite ver el listado de familias del sistema';
        $permission->save();

    }
}
