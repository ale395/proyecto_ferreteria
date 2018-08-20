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
    	//Permisos para el formulario de usuarios
        $permission = new Permission();
        $permission->name = 'Crear Usuario';
        $permission->slug = 'users.create';
        $permission->description = 'Permite la creación de nuevos Usuarios en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Usuario';
        $permission->slug = 'users.destroy';
        $permission->description = 'Permite la eliminación de Usuarios del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Usuario';
        $permission->slug = 'users.edit';
        $permission->description = 'Permite modificar los valores de un Usuario del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Usuarios';
        $permission->slug = 'users.index';
        $permission->description = 'Permite ver el listado de Usuario del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Usuario';
        $permission->slug = 'users.show';
        $permission->description = 'Permite ver un Usuario del sistema';
        $permission->save();*/

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

        //Permisos para el formulario de Bancos
        $permission = new Permission();
        $permission->name = 'Crear Banco';
        $permission->slug = 'bancos.create';
        $permission->description = 'Permite la creación de nuevos bancos en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Banco';
        $permission->slug = 'bancos.destroy';
        $permission->description = 'Permite la eliminación de bancos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Banco';
        $permission->slug = 'bancos.edit';
        $permission->description = 'Permite modificar los valores de un banco del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Bancos';
        $permission->slug = 'bancos.index';
        $permission->description = 'Permite ver el listado de bancos del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Banco';
        $permission->slug = 'bancos.show';
        $permission->description = 'Permite ver un banco del sistema';
        $permission->save();*/

        //Permisos para el formulario de Clientes
        $permission = new Permission();
        $permission->name = 'Crear Cliente';
        $permission->slug = 'clientes.create';
        $permission->description = 'Permite la creación de nuevos clientes en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Cliente';
        $permission->slug = 'clientes.destroy';
        $permission->description = 'Permite la eliminación de clientes del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Cliente';
        $permission->slug = 'clientes.edit';
        $permission->description = 'Permite modificar los valores de un cliente del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Clientes';
        $permission->slug = 'clientes.index';
        $permission->description = 'Permite ver el listado de clientes del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Cliente';
        $permission->slug = 'clientes.show';
        $permission->description = 'Permite ver un cliente del sistema';
        $permission->save();

        //Permisos para el formulario de Vendedores
        $permission = new Permission();
        $permission->name = 'Crear Vendedor';
        $permission->slug = 'vendedores.create';
        $permission->description = 'Permite la creación de nuevos vendedores en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Vendedor';
        $permission->slug = 'vendedores.destroy';
        $permission->description = 'Permite la eliminación de vendedores del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Vendedor';
        $permission->slug = 'vendedores.edit';
        $permission->description = 'Permite modificar los valores de un vendedor del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Vendedores';
        $permission->slug = 'vendedores.index';
        $permission->description = 'Permite ver el listado de vendedores del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Vendedor';
        $permission->slug = 'vendedores.show';
        $permission->description = 'Permite ver un vendedor del sistema';
        $permission->save();*/

        //Permisos para el formulario de Timbrados
        $permission = new Permission();
        $permission->name = 'Crear Timbrado';
        $permission->slug = 'timbrados.create';
        $permission->description = 'Permite la creación de nuevos Timbrados en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Timbrado';
        $permission->slug = 'timbrados.destroy';
        $permission->description = 'Permite la eliminación de Timbrados del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Timbrado';
        $permission->slug = 'timbrados.edit';
        $permission->description = 'Permite modificar los valores de un Timbrado del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Timbrados';
        $permission->slug = 'timbrados.index';
        $permission->description = 'Permite ver el listado de Timbrados del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Timbrado';
        $permission->slug = 'timbrados.show';
        $permission->description = 'Permite ver un Timbrado del sistema';
        $permission->save();*/

        //Permisos para el formulario de Sucursales
        $permission = new Permission();
        $permission->name = 'Crear Sucursal';
        $permission->slug = 'sucursales.create';
        $permission->description = 'Permite la creación de nuevas Sucursales en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Sucursal';
        $permission->slug = 'sucursales.destroy';
        $permission->description = 'Permite la eliminación de Sucursales del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Sucursal';
        $permission->slug = 'sucursales.edit';
        $permission->description = 'Permite modificar los valores de una sucursal del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Sucursales';
        $permission->slug = 'sucursales.index';
        $permission->description = 'Permite ver el listado de Sucursales del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Sucursal';
        $permission->slug = 'sucursales.show';
        $permission->description = 'Permite ver una Sucursal del sistema';
        $permission->save();*/

        //Permisos para el formulario de numeracion de series
        $permission = new Permission();
        $permission->name = 'Crear Numeracion de Serie';
        $permission->slug = 'numeseries.create';
        $permission->description = 'Permite la creación de nuevos rangos de numeración de Series en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Numeracion de Serie';
        $permission->slug = 'numeseries.destroy';
        $permission->description = 'Permite la eliminación de Rangos de Numeración de Series del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Numeracion de Serie';
        $permission->slug = 'numeseries.edit';
        $permission->description = 'Permite modificar los valores de un Rango de Numeración de Serie del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Numeracion de Series';
        $permission->slug = 'numeseries.index';
        $permission->description = 'Permite ver el listado de Rangos de Numeración de Series del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Numeracion de Serie';
        $permission->slug = 'numeseries.show';
        $permission->description = 'Permite ver un Rango de Numeración de Serie del sistema';
        $permission->save();*/

        //Permisos para el formulario de usuarios
        $permission = new Permission();
        $permission->name = 'Crear Lista Precio';
        $permission->slug = 'listaprecio.create';
        $permission->description = 'Permite la creación de Listas de Precios en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Lista Precio';
        $permission->slug = 'listaprecio.destroy';
        $permission->description = 'Permite la eliminación de Listas de Precios del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Lista Precio';
        $permission->slug = 'listaprecio.edit';
        $permission->description = 'Permite modificar los valores de un Listas de Precios del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Lista Precio';
        $permission->slug = 'listaprecio.index';
        $permission->description = 'Permite ver el listado de Listas de Precios del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Lista Precio';
        $permission->slug = 'listaprecio.show';
        $permission->description = 'Permite ver una Lista de Precios del sistema';
        $permission->save();*/

        $permission = new Permission();
        $permission->name = 'Asignación de Precios';
        $permission->slug = 'listapreciodet.asignar';
        $permission->description = 'Permite asignar precios por artículo de una Lista de Precio del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Asignación de Precio';
        $permission->slug = 'listapreciodet.destroy';
        $permission->description = 'Permite eliminar una asignacion de precio por artículo de una Lista de Precio del sistema';
        $permission->save();

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

        //Permisos para el formulario de paises
        $permission = new Permission();
        $permission->name = 'Crear Pais';
        $permission->slug = 'paises.create';
        $permission->description = 'Permite la creación de nuevos paises en el sistema';
        $permission->save();
        
        $permission = new Permission();
        $permission->name = 'Borrar Pais';
        $permission->slug = 'paises.destroy';
        $permission->description = 'Permite la eliminación de familias del sistema';
        $permission->save();
        
        $permission = new Permission();
        $permission->name = 'Editar Pais';
        $permission->slug = 'paises.edit';
        $permission->description = 'Permite modificar los valores de un pais';
        $permission->save();
        
        $permission = new Permission();
        $permission->name = 'Listar Paises';
        $permission->slug = 'paises.index';
        $permission->description = 'Permite ver el listado de paises del sistema';
        $permission->save();
        
             //Permisos para el formulario de Ciudades
        $permission = new Permission();
        $permission->name = 'Crear Ciudad';
        $permission->slug = 'ciudades.create';
        $permission->description = 'Permite la creación de nuevos ciudades en el sistema';
        $permission->save();
        
        $permission = new Permission();
        $permission->name = 'Borrar Ciudad';
        $permission->slug = 'ciudades.destroy';
        $permission->description = 'Permite la eliminación de ciudades del sistema';
        $permission->save();
        
        $permission = new Permission();
        $permission->name = 'Editar Ciudad';
        $permission->slug = 'ciudades.edit';
        $permission->description = 'Permite modificar los valores de un pais';
        $permission->save();
        
        $permission = new Permission();
        $permission->name = 'Listar Ciudades';
        $permission->slug = 'ciudades.index';
        $permission->description = 'Permite ver el listado de ciudades del sistema';
        $permission->save();

        //Permisos para el formulario de lineas
        $permission = new Permission();
        $permission->name = 'Crear Linea';
        $permission->slug = 'lineas.create';
        $permission->description = 'Permite la creación de nuevas lineas en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Linea';
        $permission->slug = 'lineas.destroy';
        $permission->description = 'Permite la eliminación de lineas del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Linea';
        $permission->slug = 'lineas.edit';
        $permission->description = 'Permite modificar los valores de una linea';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Linea';
        $permission->slug = 'lineas.index';
        $permission->description = 'Permite ver el listado de lineas del sistema';
        $permission->save();

        //Permisos para el formulario de rubros
        $permission = new Permission();
        $permission->name = 'Crear Rubro';
        $permission->slug = 'rubros.create';
        $permission->description = 'Permite la creación de nuevos rubros en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Rubro';
        $permission->slug = 'rubros.destroy';
        $permission->description = 'Permite la eliminación de rubros del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Rubro';
        $permission->slug = 'rubros.edit';
        $permission->description = 'Permite modificar los valores de un rubro';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Rubro';
        $permission->slug = 'rubros.index';
        $permission->description = 'Permite ver el listado de rubros del sistema';
        $permission->save();

        //Permisos para el formulario de unidad de medida
        $permission = new Permission();
        $permission->name = 'Crear Unidad de Medida';
        $permission->slug = 'unidadmedidas.create';
        $permission->description = 'Permite la creación de nuevas unidades de medidas en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Unidad de Medida';
        $permission->slug = 'unidadmedidas.destroy';
        $permission->description = 'Permite la eliminación de unidad de medida del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Unidad de Medida';
        $permission->slug = 'unidadmedidas.edit';
        $permission->description = 'Permite modificar los valores de un rubro';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Unidad de Medida';
        $permission->slug = 'unidadmedidas.index';
        $permission->description = 'Permite ver el listado de rubros del sistema';
        $permission->save();

        //Permisos para el formulario de concepto de ajuste
        $permission = new Permission();
        $permission->name = 'Crear Concepto de Ajuste';
        $permission->slug = 'conceptoajuste.create';
        $permission->description = 'Permite la creación de nuevos conceptos de ajuste en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Concepto de Ajuste';
        $permission->slug = 'conceptoajuste.destroy';
        $permission->description = 'Permite la eliminación de Concepto de Ajuste del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Concepto de Ajuste';
        $permission->slug = 'conceptoajuste.edit';
        $permission->description = 'Permite modificar los valores de un Concepto de Ajuste';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Concepto de Ajuste';
        $permission->slug = 'conceptoajuste.index';
        $permission->description = 'Permite ver el listado de conceptos de ajuste';
        $permission->save();

        //Permisos para el formulario de clasificacion de cliente
        $permission = new Permission();
        $permission->name = 'Crear Clasificacion de Cliente';
        $permission->slug = 'clasificacioncliente.create';
        $permission->description = 'Permite la creación de nuevas clasificacion de clientes en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Clasificacion de Cliente';
        $permission->slug = 'clasificacioncliente.destroy';
        $permission->description = 'Permite la eliminación de clasificacion de cliente del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Clasificacion de Cliente';
        $permission->slug = 'clasificacioncliente.edit';
        $permission->description = 'Permite modificar los valores de una clasificacion de cliente';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Clasificacion de Clientee';
        $permission->slug = 'clasificacioncliente.index';
        $permission->description = 'Permite ver el listado de clasificacion de clientes';
        $permission->save();

        //Permisos para el formulario de cajeros
        $permission = new Permission();
        $permission->name = 'Crear Cajero';
        $permission->slug = 'cajeros.create';
        $permission->description = 'Permite la creación de nuevas clasificacion de cajeros en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Cajero';
        $permission->slug = 'cajeros.destroy';
        $permission->description = 'Permite la eliminación de cajeros del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Cajero';
        $permission->slug = 'cajeros.edit';
        $permission->description = 'Permite modificar los valores de un cajero';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Cajero';
        $permission->slug = 'cajeros.index';
        $permission->description = 'Permite ver el listado de cajeros';
        $permission->save();
    }
}
