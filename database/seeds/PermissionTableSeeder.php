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
    	//PERMISOS PARA EL FORMULARIO DE USUARIOS
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

    	//PERMISOS PARA EL FORMULARIO DE ROL
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

        //PERMISOS PARA EL FORMULARIO DE BANCOS
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

        //PERMISOS PARA EL FORMULARIO DE EMPRESA
        $permission = new Permission();
        $permission->name = 'Ver Configuración de Empresa';
        $permission->slug = 'empresas.index';
        $permission->description = 'Permite ver la configuación de Empresa en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Configuración de Empresa';
        $permission->slug = 'empresas.edit';
        $permission->description = 'Permite modificar los valores de la Configuración de Empresa del sistema';
        $permission->save();

         //PERMISOS PARA EL FORMULARIO DE IMPUESTOS
         $permission = new Permission();
         $permission->name = 'Crear Impuesto';
         $permission->slug = 'impuestos.create';
         $permission->description = 'Permite la creación de nuevos impuestos en el sistema';
         $permission->save();
 
         $permission = new Permission();
         $permission->name = 'Borrar Impuesto';
         $permission->slug = 'impuestos.destroy';
         $permission->description = 'Permite la eliminación de impuestos del sistema';
         $permission->save();
 
         $permission = new Permission();
         $permission->name = 'Editar Impuesto';
         $permission->slug = 'impuestos.edit';
         $permission->description = 'Permite modificar los valores de un impuestos del sistema';
         $permission->save();
 
         $permission = new Permission();
         $permission->name = 'Listar Impuestos';
         $permission->slug = 'impuestos.index';
         $permission->description = 'Permite ver el listado de impuestos del sistema';
         $permission->save();

        //PERMISOS PARA EL FORMULARIO DE IMPUESTOS
         $permission = new Permission();
         $permission->name = 'Crear Formas de pago';
         $permission->slug = 'formasPagos.create';
         $permission->description = 'Permite la creación de nuevos formas pagos en el sistema';
         $permission->save();
 
         $permission = new Permission();
         $permission->name = 'Borrar Forma de pago';
         $permission->slug = 'formasPagos.destroy';
         $permission->description = 'Permite la eliminación de la forma de pago del sistema';
         $permission->save();
 
         $permission = new Permission();
         $permission->name = 'Editar Forma de pago';
         $permission->slug = 'formasPagos.edit';
         $permission->description = 'Permite modificar los valores de una forma de pago del sistema';
         $permission->save();
 
         $permission = new Permission();
         $permission->name = 'Listar forma de pago';
         $permission->slug = 'formasPagos.index';
         $permission->description = 'Permite ver el listado de formas de pago del sistema';
         $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Banco';
        $permission->slug = 'bancos.show';
        $permission->description = 'Permite ver un banco del sistema';
        $permission->save();*/

    	//PERMISOS PARA EL FORMULARIO DE CLIENTES
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

        //PERMISOS PARA EL FORMULARIO DE VENDEDORES
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

        //PERMISOS PARA EL FORMULARIO DE TIMBRADOS
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

        //PERMISOS PARA EL FORMULARIO DE SUCURSALES
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

        //PERMISOS PARA EL FORMULARIO DE SERIE
        $permission = new Permission();
        $permission->name = 'Crear Serie';
        $permission->slug = 'series.create';
        $permission->description = 'Permite la creación de Series en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Serie';
        $permission->slug = 'series.destroy';
        $permission->description = 'Permite la eliminación de Series del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Serie';
        $permission->slug = 'series.edit';
        $permission->description = 'Permite modificar los valores de una Serie del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Series';
        $permission->slug = 'series.index';
        $permission->description = 'Permite ver el listado de Series del sistema';
        $permission->save();

        //Para tablas pequeñas no creo que haga falta
        /*$permission = new Permission();
        $permission->name = 'Ver Serie';
        $permission->slug = 'series.show';
        $permission->description = 'Permite ver una Serie del sistema';
        $permission->save();*/

        //PERMISOS PARA EL FORMULARIO DE LISTA DE PRECIOS
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

        //PERMISOS PARA EL FORMULARIO DE FAMILIAS
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

        //PERMISOS PARA EL FORMULARIO DE LINEAS
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

        //PERMISOS PARA EL FORMULARIO DE RUBROS
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

        //PERMISOS PARA EL FORMULARIO DE UNIDADES DE MEDIDAS
        $permission = new Permission();
        $permission->name = 'Crear Unidad de Medida';
        $permission->slug = 'unidadesMedidas.create';
        $permission->description = 'Permite la creación de nuevas unidades de medidas en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Unidad de Medida';
        $permission->slug = 'unidadesMedidas.destroy';
        $permission->description = 'Permite la eliminación de unidad de medida del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Unidad de Medida';
        $permission->slug = 'unidadesMedidas.edit';
        $permission->description = 'Permite modificar los valores de una unidad de medida';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Unidad de Medida';
        $permission->slug = 'unidadesMedidas.index';
        $permission->description = 'Permite ver el listado de rubros del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE CONCEPTO AJUSTES
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

        //PERMISOS PARA EL FORMULARIO DE CLASIFICACION DE CLIENTES
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

        //PERMISOS PARA EL FORMULARIO DE CAJEROS
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

        //Pedidos - Ventas
        $permission = new Permission();
        $permission->name = 'Cargar Pedido de Venta';
        $permission->slug = 'pedidosVentas.create';
        $permission->description = 'Permite la carga de un pedido de venta en el sistema';
        $permission->save();

        /*$permission = new Permission();
        $permission->name = 'Borrar Pedido de Venta';
        $permission->slug = 'pedidosVentas.destroy';
        $permission->description = 'Permite la eliminación de un pedido de venta del sistema';
        $permission->save();*/

        $permission = new Permission();
        $permission->name = 'Editar Pedido de Venta';
        $permission->slug = 'pedidosVentas.edit';
        $permission->description = 'Permite modificar los valores de un pedido del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Pedidos de Ventas';
        $permission->slug = 'pedidosVentas.index';
        $permission->description = 'Permite ver el listado de Pedidos de Ventas del sistema';
        $permission->save();

        
        $permission = new Permission();
        $permission->name = 'Ver Pedido de Venta';
        $permission->slug = 'pedidosVentas.show';
        $permission->description = 'Permite ver un Pedido de Venta del sistema';
        $permission->save();
    }
}
