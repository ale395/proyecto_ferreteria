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
        $permiso_listar = Permission::where('slug', 'modulos.index')->first();
        $permiso_crear = Permission::where('slug', 'modulos.create')->first();
        $permiso_editar = Permission::where('slug', 'modulos.edit')->first();
        $permiso_eliminar = Permission::where('slug', 'modulos.destroy')->first();

        $role = new Role();
        $role->name = 'Administrador';
        $role->slug = 'administrador';
        $role->description = 'Administrator del sistema';
        $role->save();

        $role->assignPermission($permiso_listar->id);
        $role->assignPermission($permiso_crear->id);
        $role->assignPermission($permiso_editar->id);
        $role->assignPermission($permiso_eliminar->id);
        $role->save();

        $role = new Role();
        $role->name = 'Operador';
        $role->slug = 'operador';
        $role->description = 'Usuario Operador del sistema';
        $role->save();

        $role->assignPermission($permiso_listar->id);
        $role->assignPermission($permiso_crear->id);
        $role->assignPermission($permiso_editar->id);
        $role->assignPermission($permiso_eliminar->id);
        $role->save();
    }
}
