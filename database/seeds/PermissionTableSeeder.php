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

        //PERMISOS PARA EL FORMULARIO DE EMPLEADOS
        $permission = new Permission();
        $permission->name = 'Crear Empleado';
        $permission->slug = 'empleados.create';
        $permission->description = 'Permite la creación de nuevos Empleados en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Empleado';
        $permission->slug = 'empleados.destroy';
        $permission->description = 'Permite la eliminación de Empleado del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Empleado';
        $permission->slug = 'empleados.edit';
        $permission->description = 'Permite modificar los valores de un Empleado del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Empleados';
        $permission->slug = 'empleados.index';
        $permission->description = 'Permite ver el listado de Empleados del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Empleado';
        $permission->slug = 'empleados.show';
        $permission->description = 'Permite ver un Empleado del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE DEPOSITOS
        $permission = new Permission();
        $permission->name = 'Crear Deposito';
        $permission->slug = 'depositos.create';
        $permission->description = 'Permite la creación de nuevos depositos en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Deposito';
        $permission->slug = 'depositos.destroy';
        $permission->description = 'Permite la eliminación de depositos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Deposito';
        $permission->slug = 'depositos.edit';
        $permission->description = 'Permite modificar los valores de un deposito del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Depositos';
        $permission->slug = 'depositos.index';
        $permission->description = 'Permite ver el listado de depositos del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE TIPOS DE EMPLEADOS
        $permission = new Permission();
        $permission->name = 'Crear Tipo Empleado';
        $permission->slug = 'tiposEmpleados.create';
        $permission->description = 'Permite la creación de un nuevo Tipo de Empleado en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Tipo de Empleado';
        $permission->slug = 'tiposEmpleados.destroy';
        $permission->description = 'Permite la eliminación de un Tipo de Empleado del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Tipo de Empleado';
        $permission->slug = 'tiposEmpleados.edit';
        $permission->description = 'Permite modificar los valores de un Tipo de Empleado del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Tipos de Empleados';
        $permission->slug = 'tiposEmpleados.index';
        $permission->description = 'Permite ver el listado de Tipos de Empleados del sistema';
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
        //PERMISOS PARA EL FORMULARIO DE ARTICULOS
        $permission = new Permission();
        $permission->name = 'Crear Articulo';
        $permission->slug = 'articulos.create';
        $permission->description = 'Permite la creación de nuevos impuestos en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Articulo';
        $permission->slug = 'articulos.destroy';
        $permission->description = 'Permite la eliminación de impuestos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Articulo';
        $permission->slug = 'articulos.edit';
        $permission->description = 'Permite modificar los valores de un impuestos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Articulos';
        $permission->slug = 'articulos.index';
        $permission->description = 'Permite ver el listado de impuestos del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE COTIZACIONES
        $permission = new Permission();
        $permission->name = 'Crear Cotizacion';
        $permission->slug = 'cotizaciones.create';
        $permission->description = 'Permite la creación de nuevos impuestos en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Cotizacion';
        $permission->slug = 'cotizaciones.destroy';
        $permission->description = 'Permite la eliminación de impuestos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Cotizacion';
        $permission->slug = 'cotizaciones.edit';
        $permission->description = 'Permite modificar los valores de un impuestos del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Cotizaciones';
        $permission->slug = 'cotizaciones.index';
        $permission->description = 'Permite ver el listado de impuestos del sistema';
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


        //PERMISOS PARA EL FORMULARIO DE MONEDAS
        $permission = new Permission();
        $permission->name = 'Crear Moneda';
        $permission->slug = 'monedas.create';
        $permission->description = 'Permite la creación de nuevos monedas en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Moneda';
        $permission->slug = 'monedas.destroy';
        $permission->description = 'Permite la eliminación de mmonedas del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Moneda';
        $permission->slug = 'monedas.edit';
        $permission->description = 'Permite modificar los valores de una moneda del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Monedas';
        $permission->slug = 'monedas.index';
        $permission->description = 'Permite ver el listado de monedas del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE Formas de pagos
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

        //PERMISOS PARA EL FORMULARIO DE CONCEPTO AJUSTES
        $permission = new Permission();
        $permission->name = 'Crear Concepto de Caja';
        $permission->slug = 'conceptocaja.create';
        $permission->description = 'Permite la creación de nuevos conceptos de caja en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Concepto de Caja';
        $permission->slug = 'conceptocaja.destroy';
        $permission->description = 'Permite la eliminación de Concepto de caja del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Concepto de Caja';
        $permission->slug = 'conceptocaja.edit';
        $permission->description = 'Permite modificar los valores de un Concepto de caja';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Concepto de Caja';
        $permission->slug = 'conceptocaja.index';
        $permission->description = 'Permite ver el listado de conceptos de caja';
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

        $permission = new Permission();
        $permission->name = 'Borrar Pedido de Venta';
        $permission->slug = 'pedidosVentas.destroy';
        $permission->description = 'Permite la eliminación de un pedido de venta del sistema';
        $permission->save();

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

        //Facturacion - Ventas
        $permission = new Permission();
        $permission->name = 'Cargar Factura de Venta';
        $permission->slug = 'facturacionVentas.create';
        $permission->description = 'Permite la carga de una Factura de venta en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Factura de Venta';
        $permission->slug = 'facturacionVentas.destroy';
        $permission->description = 'Permite la eliminación de una Factura de venta del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Factura de Venta';
        $permission->slug = 'facturacionVentas.edit';
        $permission->description = 'Permite modificar los valores de una Factura del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Facturas de Ventas';
        $permission->slug = 'facturacionVentas.index';
        $permission->description = 'Permite ver el listado de Facturas de Ventas del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Factura de Venta';
        $permission->slug = 'facturacionVentas.show';
        $permission->description = 'Permite ver una Factura de Venta del sistema';
        $permission->save();

        //Notas Credito - Ventas
        $permission = new Permission();
        $permission->name = 'Cargar Nota de Crédito Venta';
        $permission->slug = 'notaCreditoVentas.create';
        $permission->description = 'Permite la carga de una Nota de Crédito de venta en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Nota de Crédito Venta';
        $permission->slug = 'notaCreditoVentas.destroy';
        $permission->description = 'Permite la eliminación de una Nota de Crédito de venta del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Nota de Crédito Venta';
        $permission->slug = 'notaCreditoVentas.edit';
        $permission->description = 'Permite modificar los valores de una Nota de Crédito del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Notas de Crédito Ventas';
        $permission->slug = 'notaCreditoVentas.index';
        $permission->description = 'Permite ver el listado de Notas de Crédito Ventas del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Nota de Crédito Venta';
        $permission->slug = 'notaCreditoVentas.show';
        $permission->description = 'Permite ver una Nota de Crédito Venta del sistema';
        $permission->save();

        //Ajustes - Inventarios
        $permission = new Permission();
        $permission->name = 'Cargar Ajuste de inventario';
        $permission->slug = 'ajustesInventarios.create';
        $permission->description = 'Permite la carga de un ajuste de inventario en el sistema';
        $permission->save();

        /*$permission = new Permission();
        $permission->name = 'Borrar Ajuste de inventario';
        $permission->slug = 'ajustesInventarios.destroy';
        $permission->description = 'Permite la eliminación de un ajuste de inventario del sistema';
        $permission->save();*/

        $permission = new Permission();
        $permission->name = 'Editar ajuste de inventario';
        $permission->slug = 'ajustesInventarios.edit';
        $permission->description = 'Permite modificar los valores de un pedido del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar ajuste de inventario';
        $permission->slug = 'ajustesInventarios.index';
        $permission->description = 'Permite ver el listado de ajuste de inventario del sistema';
        $permission->save();

        
        $permission = new Permission();
        $permission->name = 'Ver Ajuste de inventario';
        $permission->slug = 'ajustesInventarios.show';
        $permission->description = 'Permite ver un ajuste de inventrio del sistema';
        $permission->save();
  
          //Inventarios
          $permission = new Permission();
          $permission->name = 'Cargar  inventario';
          $permission->slug = 'inventarios.create';
          $permission->description = 'Permite la carga de un ajuste de inventario en el sistema';
          $permission->save();
  
          /*$permission = new Permission();
          $permission->name = 'Borrar  inventario';
          $permission->slug = 'inventarios.destroy';
          $permission->description = 'Permite la eliminación de un ajuste de inventario del sistema';
          $permission->save();*/
  
          $permission = new Permission();
          $permission->name = 'Editar  inventario';
          $permission->slug = 'inventarios.edit';
          $permission->description = 'Permite modificar los valores de un pedido del sistema';
          $permission->save();
  
          $permission = new Permission();
          $permission->name = 'Listar  inventario';
          $permission->slug = 'inventarios.index';
          $permission->description = 'Permite ver el listado de ajuste de inventario del sistema';
          $permission->save();
  
          $permission = new Permission();
          $permission->name = 'Ver inventario';
          $permission->slug = 'inventarios.show';
          $permission->description = 'Permite ver un ajuste de inventrio del sistema';
          $permission->save();
    

        //PERMISOS PARA EL FORMULARIO DE Tipo de Proveedor
        $permission = new Permission();
        $permission->name = 'Crear Tipo de Proveedor';
        $permission->slug = 'tiposproveedores.create';
        $permission->description = 'Permite la creación de nuevos tipos de proveedores en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Tipo de Proveedor';
        $permission->slug = 'tiposproveedores.destroy';
        $permission->description = 'Permite la eliminación de tipos de proveedores del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Tipo de Proveedor';
        $permission->slug = 'tiposproveedores.edit';
        $permission->description = 'Permite modificar los valores de un tipo de proveedor del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Tipo de Proveedor';
        $permission->slug = 'tiposproveedores.index';
        $permission->description = 'Permite ver el listado de tipos de proveedores del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE Proveedor
        $permission = new Permission();
        $permission->name = 'Crear Proveedor';
        $permission->slug = 'proveedores.create';
        $permission->description = 'Permite la creación de nuevos proveedores en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Proveedor';
        $permission->slug = 'proveedores.destroy';
        $permission->description = 'Permite la eliminación de proveedores del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Proveedor';
        $permission->slug = 'proveedores.edit';
        $permission->description = 'Permite modificar los datos de un proveedor';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Proveedores';
        $permission->slug = 'proveedores.index';
        $permission->description = 'Permite ver el listado de proveedores';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Proveedor';
        $permission->slug = 'proveedores.show';
        $permission->description = 'Permite ver detalles del proveedor';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE ORDEN DE COMPRA
        $permission = new Permission();
        $permission->name = 'Crear Orden de Compra';
        $permission->slug = 'ordencompra.create';
        $permission->description = 'Permite registrar una orden de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Orden de Compra';
        $permission->slug = 'ordencompra.destroy';
        $permission->description = 'Permite la eliminación de una orden de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Orden de Compra';
        $permission->slug = 'ordencompra.edit';
        $permission->description = 'Permite modificar una orden de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Ordenes de Compra';
        $permission->slug = 'ordencompra.index';
        $permission->description = 'Permite ver el listado de ordenes de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Orden de Compra';
        $permission->slug = 'ordencompra.show';
        $permission->description = 'Permite ver la Orden de Compra Cargada';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE COMPRAS
        $permission = new Permission();
        $permission->name = 'Crear Compra';
        $permission->slug = 'compra.create';
        $permission->description = 'Permite registrar una orden de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Compra';
        $permission->slug = 'compra.destroy';
        $permission->description = 'Permite la eliminación de una orden de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Compra';
        $permission->slug = 'compra.edit';
        $permission->description = 'Permite modificar una orden de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Compras';
        $permission->slug = 'compra.index';
        $permission->description = 'Permite ver el listado de ordenes de compra';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Compra';
        $permission->slug = 'compra.show';
        $permission->description = 'Permite ver la Orden de Compra Cargada';
        $permission->save();

        //Notas Credito - Compra
        $permission = new Permission();
        $permission->name = 'Cargar Nota de Crédito Compra';
        $permission->slug = 'notacreditocompras.create';
        $permission->description = 'Permite la carga de una Nota de Crédito de Compra en el sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Nota de Crédito Compra';
        $permission->slug = 'notacreditocompras.destroy';
        $permission->description = 'Permite la eliminación de una Nota de Crédito de Compra del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Nota de Crédito Compra';
        $permission->slug = 'notacreditocompras.edit';
        $permission->description = 'Permite modificar los valores de una Nota de Compra del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Notas de Crédito Compra';
        $permission->slug = 'notacreditocompras.index';
        $permission->description = 'Permite ver el listado de Notas de Crédito de Compra del sistema';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Nota de Crédito Compra';
        $permission->slug = 'notacreditocompras.show';
        $permission->description = 'Permite ver una Nota de Crédito Compra del sistema';
        $permission->save();

        //PERMISOS PARA EL FORMULARIO DE ORDEN DE PAGO
        $permission = new Permission();
        $permission->name = 'Crear Orden de Compra';
        $permission->slug = 'ordenpago.create';
        $permission->description = 'Permite registrar una orden de pago';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Orden de Compra';
        $permission->slug = 'ordenpago.destroy';
        $permission->description = 'Permite la eliminación de una orden de pago';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Orden de Compra';
        $permission->slug = 'ordenpago.edit';
        $permission->description = 'Permite modificar una orden de pago';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Ordenes de Compra';
        $permission->slug = 'ordenpago.index';
        $permission->description = 'Permite ver el listado de ordenes de pago';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Ver Orden de Compra';
        $permission->slug = 'ordenpago.show';
        $permission->description = 'Permite ver la Orden de pago cargada';
        $permission->save();

        //GESTION DE CAJAS
        $permission = new Permission();
        $permission->name = 'Habilitar Caja';
        $permission->slug = 'gestionCajas.habilitarCaja';
        $permission->description = 'Permite habilitar caja para el inicio de las gestiones de cobranzas';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Cerrar Caja';
        $permission->slug = 'gestionCajas.cerrarCaja';
        $permission->description = 'Permite cerrar caja cuando finaliza el turno del cajero';
        $permission->save();

        //ANULACION DE COMPROBANTES
        $permission = new Permission();
        $permission->name = 'Ver listado de comprobantes para anular';
        $permission->slug = 'anulacioncomprobantes.index';
        $permission->description = 'Permite ver el listado de comprobantes pendientes que pueden ser anulados';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Anular Comprobante';
        $permission->slug = 'anulacioncomprobantes.anular';
        $permission->description = 'Permite anular el comprobante';
        $permission->save();

        //MOTIVOS DE ANULACION
        $permission = new Permission();
        $permission->name = 'Crear Motivo de Anulacion';
        $permission->slug = 'motivoanulacion.create';
        $permission->description = 'Permite crear un motivo de anulación';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Borrar Motivo de Anulacion';
        $permission->slug = 'motivoanulacion.destroy';
        $permission->description = 'Permite borrar un motivo de anulación';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Editar Motivo de Anulacion';
        $permission->slug = 'motivoanulacion.edit';
        $permission->description = 'Permite editar un motivo de anulación';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Listar Motivos de Anulaciones';
        $permission->slug = 'motivoanulacion.index';
        $permission->description = 'Permite listar los motivos de anulaciones';
        $permission->save();
    }
}
