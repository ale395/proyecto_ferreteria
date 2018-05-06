<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //permisos de USUARIOS
        $permiso_listar_user = Permission::where('slug', 'users.index')->first();
        $permiso_crear_user = Permission::where('slug', 'users.create')->first();
        $permiso_editar_user = Permission::where('slug', 'users.edit')->first();
        $permiso_eliminar_user = Permission::where('slug', 'users.destroy')->first();

        //permisos de MODULOS
        $permiso_listar_modulo = Permission::where('slug', 'modulos.index')->first();
        $permiso_crear_modulo = Permission::where('slug', 'modulos.create')->first();
        $permiso_editar_modulo = Permission::where('slug', 'modulos.edit')->first();
        $permiso_eliminar_modulo = Permission::where('slug', 'modulos.destroy')->first();

        //permisos de ROLES
        $permiso_listar_rol = Permission::where('slug', 'roles.index')->first();
        $permiso_crear_rol = Permission::where('slug', 'roles.create')->first();
        $permiso_editar_rol = Permission::where('slug', 'roles.edit')->first();
        $permiso_eliminar_rol = Permission::where('slug', 'roles.destroy')->first();

        //permisos de Familias
        $permiso_familia_listar = Permission::where('slug', 'familias.index')->first();
        $permiso_familia_crear = Permission::where('slug', 'familias.create')->first();
        $permiso_familia_editar = Permission::where('slug', 'familias.edit')->first();
        $permiso_familia_eliminar = Permission::where('slug', 'familias.destroy')->first();


      //permisos de PAISES
        $permiso_listar = Permission::where('slug', 'paises.index')->first();
        $permiso_crear = Permission::where('slug', 'paises.create')->first();
        $permiso_editar = Permission::where('slug', 'paises.edit')->first();
        $permiso_eliminar = Permission::where('slug', 'paises.destroy')->first();

    //permisos de DEPARTAMENTOS
        $permiso_departamentos_listar = Permission::where('slug', 'departamentos.index')->first();
        $permiso_departamentos_crear = Permission::where('slug', 'departamentos.create')->first();
        $permiso_departamentos_editar = Permission::where('slug', 'departamentos.edit')->first();
        $permiso_departamentos_eliminar = Permission::where('slug', 'departamentos.destroy')->first();

        //permisos de Lineas
        $permiso_linea_listar = Permission::where('slug', 'lineas.index')->first();
        $permiso_linea_crear = Permission::where('slug', 'lineas.create')->first();
        $permiso_linea_editar = Permission::where('slug', 'lineas.edit')->first();
        $permiso_linea_eliminar = Permission::where('slug', 'lineas.destroy')->first();


        $role = new Role();
        $role->name = 'Administrador';
        $role->slug = 'administrador';
        $role->description = 'Administrator del sistema';
        $role->save();

        //A los Administradores le damos todos los permisos
        $role->assignPermission($permiso_listar->id);
        $role->assignPermission($permiso_crear->id);
        $role->assignPermission($permiso_editar->id);
        $role->assignPermission($permiso_eliminar->id);
        $role->assignPermission($permiso_familia_listar->id);
        $role->assignPermission($permiso_familia_crear->id);
        $role->assignPermission($permiso_familia_editar->id);
        $role->assignPermission($permiso_familia_eliminar->id);
        $role->assignPermission($permiso_listar_rol->id);
        $role->assignPermission($permiso_crear_rol->id);
        $role->assignPermission($permiso_editar_rol->id);
        $role->assignPermission($permiso_eliminar_rol->id);
        $role->assignPermission($permiso_listar_user->id);
        $role->assignPermission($permiso_crear_user->id);
        $role->assignPermission($permiso_editar_user->id);
        $role->assignPermission($permiso_eliminar_user->id);

        $role->assignPermission($permiso_departamentos_listar->id);
        $role->assignPermission($permiso_departamentos_crear->id);
        $role->assignPermission($permiso_departamentos_editar->id);
        $role->assignPermission($permiso_departamentos_eliminar->id);

        $role->assignPermission($permiso_linea_listar->id);
        $role->assignPermission($permiso_linea_crear->id);
        $role->assignPermission($permiso_linea_editar->id);
        $role->assignPermission($permiso_linea_eliminar->id);

        $role->assignPermission($permiso_linea_modulo->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->save();

        $role = new Role();
        $role->name = 'Operador';
        $role->slug = 'operador';
        $role->description = 'Usuario Operador del sistema';
        $role->save();

        //A los operadores le damos unos cuantitos nomas
        $role->assignPermission($permiso_listar->id);
        $role->assignPermission($permiso_crear->id);
        $role->assignPermission($permiso_editar->id);
        $role->assignPermission($permiso_eliminar->id);
        $role->assignPermission($permiso_listar_rol->id);
        $role->assignPermission($permiso_crear_rol->id);
        $role->assignPermission($permiso_editar_rol->id);
        $role->assignPermission($permiso_eliminar_rol->id);
        $role->assignPermission($permiso_listar_user->id);
        $role->assignPermission($permiso_crear_user->id);
        $role->assignPermission($permiso_editar_user->id);
        $role->assignPermission($permiso_eliminar_user->id);
        $role->assignPermission($permiso_departamentos_listar->id);
        $role->assignPermission($permiso_departamentos_crear->id);
        $role->assignPermission($permiso_departamentos_editar->id);
        $role->assignPermission($permiso_departamentos_eliminar->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->assignPermission($permiso_linea_modulo->id);
        $role->save();
    }
}
