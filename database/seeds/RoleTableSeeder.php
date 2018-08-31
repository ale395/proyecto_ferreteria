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

        //permisos de ROLES
        $permiso_listar_rol = Permission::where('slug', 'roles.index')->first();
        $permiso_crear_rol = Permission::where('slug', 'roles.create')->first();
        $permiso_editar_rol = Permission::where('slug', 'roles.edit')->first();
        $permiso_eliminar_rol = Permission::where('slug', 'roles.destroy')->first();

        //permisos de Clientes
        $permiso_listar_cliente = Permission::where('slug', 'clientes.index')->first();
        $permiso_crear_cliente = Permission::where('slug', 'clientes.create')->first();
        $permiso_editar_cliente = Permission::where('slug', 'clientes.edit')->first();
        $permiso_eliminar_cliente = Permission::where('slug', 'clientes.destroy')->first();
        $permiso_ver_cliente = Permission::where('slug', 'clientes.show')->first();

        //permisos para Pedido de Venta
        $permiso_listar_pedido_venta = Permission::where('slug', 'pedidosVentas.index')->first();
        $permiso_crear_pedido_venta = Permission::where('slug', 'pedidosVentas.create')->first();
        $permiso_editar_pedido_venta = Permission::where('slug', 'pedidosVentas.edit')->first();
        //$permiso_eliminar_pedido_venta = Permission::where('slug', 'pedidosVentas.destroy')->first();
        $permiso_ver_pedido_venta = Permission::where('slug', 'pedidosVentas.show')->first();

        $permiso_listar_timb = Permission::where('slug', 'timbrados.index')->first();
        $permiso_crear_timb = Permission::where('slug', 'timbrados.create')->first();
        $permiso_editar_timb = Permission::where('slug', 'timbrados.edit')->first();
        $permiso_eliminar_timb = Permission::where('slug', 'timbrados.destroy')->first();

        //permisos de NUMERACION DE SERIES
        $permiso_listar_nume_series = Permission::where('slug', 'series.index')->first();
        $permiso_crear_nume_series = Permission::where('slug', 'series.create')->first();
        $permiso_editar_nume_series = Permission::where('slug', 'series.edit')->first();
        $permiso_eliminar_nume_series = Permission::where('slug', 'series.destroy')->first();

        //permisos de Listas de Precios
        $permiso_listar_lprec = Permission::where('slug', 'listaprecio.index')->first();
        $permiso_crear_lprec = Permission::where('slug', 'listaprecio.create')->first();
        $permiso_editar_lprec = Permission::where('slug', 'listaprecio.edit')->first();
        $permiso_eliminar_lprec = Permission::where('slug', 'listaprecio.destroy')->first();

        $permiso_asignar_lprec = Permission::where('slug', 'listapreciodet.asignar')->first();
        $permiso_eliminar_asignacion_lprec = Permission::where('slug', 'listapreciodet.destroy')->first();

        //permisos de Sucursales
        $permiso_listar_sucu = Permission::where('slug', 'sucursales.index')->first();
        $permiso_crear_sucu = Permission::where('slug', 'sucursales.create')->first();
        $permiso_editar_sucu = Permission::where('slug', 'sucursales.edit')->first();
        $permiso_eliminar_sucu = Permission::where('slug', 'sucursales.destroy')->first();
        //permisos de Impuestos
        $permiso_listar_impuesto = Permission::where('slug', 'impuestos.index')->first();
        $permiso_crear_impuesto = Permission::where('slug', 'impuestos.create')->first();
        $permiso_editar_impuesto = Permission::where('slug', 'impuestos.edit')->first();
        $permiso_eliminar_impuesto = Permission::where('slug', 'impuestos.destroy')->first();

        //permisos de Monedas
        $permiso_listar_moneda = Permission::where('slug', 'monedas.index')->first();
        $permiso_crear_moneda = Permission::where('slug', 'monedas.create')->first();
        $permiso_editar_moneda = Permission::where('slug', 'monedas.edit')->first();
        $permiso_eliminar_moneda = Permission::where('slug', 'monedas.destroy')->first();

        //permisos de Impuestos
        $permiso_listar_formaPago = Permission::where('slug', 'formasPagos.index')->first();
        $permiso_crear_formaPago = Permission::where('slug', 'formasPagos.create')->first();
        $permiso_editar_formaPago = Permission::where('slug', 'formasPagos.edit')->first();
        $permiso_eliminar_formaPago = Permission::where('slug', 'formasPagos.destroy')->first();
        
        //permisos de Bancos
        $permiso_listar_banco = Permission::where('slug', 'bancos.index')->first();
        $permiso_crear_banco = Permission::where('slug', 'bancos.create')->first();
        $permiso_editar_banco = Permission::where('slug', 'bancos.edit')->first();
        $permiso_eliminar_banco = Permission::where('slug', 'bancos.destroy')->first();
        //permisos de Bancos
        $permiso_listar_deposito = Permission::where('slug', 'depositos.index')->first();
        $permiso_crear_deposito = Permission::where('slug', 'depositos.create')->first();
        $permiso_editar_deposito = Permission::where('slug', 'depositos.edit')->first();
        $permiso_eliminar_deposito = Permission::where('slug', 'depositos.destroy')->first();
        //permisos de Tipos Empleados
        $permiso_listar_tipo_empleado = Permission::where('slug', 'tiposEmpleados.index')->first();
        $permiso_crear_tipo_empleado = Permission::where('slug', 'tiposEmpleados.create')->first();
        $permiso_editar_tipo_empleado = Permission::where('slug', 'tiposEmpleados.edit')->first();
        $permiso_eliminar_tipo_empleado = Permission::where('slug', 'tiposEmpleados.destroy')->first();

        //permisos para Configuración de Empresa
        $permiso_listar_empresa = Permission::where('slug', 'empresas.index')->first();
        $permiso_editar_empresa = Permission::where('slug', 'empresas.edit')->first();

        //permisos de Vendedores
        $permiso_listar_vend = Permission::where('slug', 'vendedores.index')->first();
        $permiso_crear_vend = Permission::where('slug', 'vendedores.create')->first();
        $permiso_editar_vend = Permission::where('slug', 'vendedores.edit')->first();
        $permiso_eliminar_vend = Permission::where('slug', 'vendedores.destroy')->first();

        //permisos de Familias
        $permiso_familia_listar = Permission::where('slug', 'familias.index')->first();
        $permiso_familia_crear = Permission::where('slug', 'familias.create')->first();
        $permiso_familia_editar = Permission::where('slug', 'familias.edit')->first();
        $permiso_familia_eliminar = Permission::where('slug', 'familias.destroy')->first();

        //permisos de Lineas
        $permiso_linea_listar = Permission::where('slug', 'lineas.index')->first();
        $permiso_linea_crear = Permission::where('slug', 'lineas.create')->first();
        $permiso_linea_editar = Permission::where('slug', 'lineas.edit')->first();
        $permiso_linea_eliminar = Permission::where('slug', 'lineas.destroy')->first();

        //permisos de Rubros
        $permiso_rubro_listar = Permission::where('slug', 'rubros.index')->first();
        $permiso_rubro_crear = Permission::where('slug', 'rubros.create')->first();
        $permiso_rubro_editar = Permission::where('slug', 'rubros.edit')->first();
        $permiso_rubro_eliminar = Permission::where('slug', 'rubros.destroy')->first();

        //permisos de unidades_medidas
        $permiso_unidadmedida_listar = Permission::where('slug', 'unidadesMedidas.index')->first();
        $permiso_unidadmedida_crear = Permission::where('slug', 'unidadesMedidas.create')->first();
        $permiso_unidadmedida_editar = Permission::where('slug', 'unidadesMedidas.edit')->first();
        $permiso_unidadmedida_eliminar = Permission::where('slug', 'unidadesMedidas.destroy')->first();

        //permisos de Conceptos de Ajuste
        $permiso_concepto_listar = Permission::where('slug', 'conceptoajuste.index')->first();
        $permiso_concepto_crear = Permission::where('slug', 'conceptoajuste.create')->first();
        $permiso_concepto_editar = Permission::where('slug', 'conceptoajuste.edit')->first();
        $permiso_concepto_eliminar = Permission::where('slug', 'conceptoajuste.destroy')->first();

         //permisos de Categoria de clientes
        $permiso_clasicli_listar = Permission::where('slug', 'clasificacioncliente.index')->first();
        $permiso_clasicli_crear = Permission::where('slug', 'clasificacioncliente.create')->first();
        $permiso_clasicli_editar = Permission::where('slug', 'clasificacioncliente.edit')->first();
        $permiso_clasicli_eliminar = Permission::where('slug', 'clasificacioncliente.destroy')->first();

        //permisos de Cajeros
        $permiso_cajero_listar = Permission::where('slug', 'cajeros.index')->first();
        $permiso_cajero_crear = Permission::where('slug', 'cajeros.create')->first();
        $permiso_cajero_editar = Permission::where('slug', 'cajeros.edit')->first();
        $permiso_cajero_eliminar = Permission::where('slug', 'cajeros.destroy')->first();

        //permisos de Tipo de Proveedor
        $permiso_tproveedor_listar = Permission::where('slug', 'tiposproveedores.index')->first();
        $permiso_tproveedor_crear = Permission::where('slug', 'tiposproveedores.create')->first();
        $permiso_tproveedor_editar = Permission::where('slug', 'tiposproveedores.edit')->first();
        $permiso_tproveedor_eliminar = Permission::where('slug', 'tiposproveedores.destroy')->first();

        $role = new Role();
        $role->name = 'Administrador';
        $role->slug = 'administrador';
        $role->description = 'Administrator del sistema';
        $role->save();

        //A los Administradores le damos todos los permisos

        $role->assignPermission($permiso_familia_listar->id);
        $role->assignPermission($permiso_familia_crear->id);
        $role->assignPermission($permiso_familia_editar->id);
        $role->assignPermission($permiso_familia_eliminar->id);

        $role->assignPermission($permiso_listar_formaPago->id);
        $role->assignPermission($permiso_crear_formaPago->id);
        $role->assignPermission($permiso_editar_formaPago->id);
        $role->assignPermission($permiso_eliminar_formaPago->id);

        $role->assignPermission($permiso_listar_sucu->id);
        $role->assignPermission($permiso_crear_sucu->id);
        $role->assignPermission($permiso_editar_sucu->id);
        $role->assignPermission($permiso_eliminar_sucu->id);

        $role->assignPermission($permiso_listar_rol->id);
        $role->assignPermission($permiso_crear_rol->id);
        $role->assignPermission($permiso_editar_rol->id);
        $role->assignPermission($permiso_eliminar_rol->id);

        $role->assignPermission($permiso_listar_user->id);
        $role->assignPermission($permiso_crear_user->id);
        $role->assignPermission($permiso_editar_user->id);
        $role->assignPermission($permiso_eliminar_user->id);

        $role->assignPermission($permiso_linea_listar->id);
        $role->assignPermission($permiso_linea_crear->id);
        $role->assignPermission($permiso_linea_editar->id);
        $role->assignPermission($permiso_linea_eliminar->id);

        $role->assignPermission($permiso_rubro_listar->id);
        $role->assignPermission($permiso_rubro_crear->id);
        $role->assignPermission($permiso_rubro_editar->id);
        $role->assignPermission($permiso_rubro_eliminar->id);

        $role->assignPermission($permiso_unidadmedida_listar->id);
        $role->assignPermission($permiso_unidadmedida_crear->id);
        $role->assignPermission($permiso_unidadmedida_editar->id);
        $role->assignPermission($permiso_unidadmedida_eliminar->id);

        $role->assignPermission($permiso_concepto_listar->id);
        $role->assignPermission($permiso_concepto_crear->id);
        $role->assignPermission($permiso_concepto_editar->id);
        $role->assignPermission($permiso_concepto_eliminar->id);

        $role->assignPermission($permiso_clasicli_listar->id);
        $role->assignPermission($permiso_clasicli_crear->id);
        $role->assignPermission($permiso_clasicli_editar->id);
        $role->assignPermission($permiso_clasicli_eliminar->id);

        $role->assignPermission($permiso_cajero_listar->id);
        $role->assignPermission($permiso_cajero_crear->id);
        $role->assignPermission($permiso_cajero_editar->id);
        $role->assignPermission($permiso_cajero_eliminar->id);

        /*$role->assignPermission($permiso_listar_concepto->id);
        $role->assignPermission($permiso_crear_concepto->id);
        $role->assignPermission($permiso_editar_concepto->id);
        $role->assignPermission($permiso_eliminar_concepto->id);*/
        $role->assignPermission($permiso_listar_impuesto->id);
        $role->assignPermission($permiso_crear_impuesto->id);
        $role->assignPermission($permiso_editar_impuesto->id);
        $role->assignPermission($permiso_eliminar_impuesto->id);

        $role->assignPermission($permiso_listar_moneda->id);
        $role->assignPermission($permiso_crear_moneda->id);
        $role->assignPermission($permiso_editar_moneda->id);
        $role->assignPermission($permiso_eliminar_moneda->id);

        $role->assignPermission($permiso_listar_banco->id);
        $role->assignPermission($permiso_crear_banco->id);
        $role->assignPermission($permiso_editar_banco->id);
        $role->assignPermission($permiso_eliminar_banco->id);

        $role->assignPermission($permiso_listar_deposito->id);
        $role->assignPermission($permiso_crear_deposito->id);
        $role->assignPermission($permiso_editar_deposito->id);
        $role->assignPermission($permiso_eliminar_deposito->id);

        $role->assignPermission($permiso_listar_tipo_empleado->id);
        $role->assignPermission($permiso_crear_tipo_empleado->id);
        $role->assignPermission($permiso_editar_tipo_empleado->id);
        $role->assignPermission($permiso_eliminar_tipo_empleado->id);

        $role->assignPermission($permiso_listar_pedido_venta->id);
        $role->assignPermission($permiso_crear_pedido_venta->id);
        $role->assignPermission($permiso_editar_pedido_venta->id);
        //$role->assignPermission($permiso_eliminar_pedido_venta->id);
        $role->assignPermission($permiso_ver_pedido_venta->id);

        $role->assignPermission($permiso_listar_timb->id);
        $role->assignPermission($permiso_crear_timb->id);
        $role->assignPermission($permiso_editar_timb->id);
        $role->assignPermission($permiso_eliminar_timb->id);

        $role->assignPermission($permiso_listar_vend->id);
        $role->assignPermission($permiso_crear_vend->id);
        $role->assignPermission($permiso_editar_vend->id);
        $role->assignPermission($permiso_eliminar_vend->id);

        $role->assignPermission($permiso_listar_nume_series->id);
        $role->assignPermission($permiso_crear_nume_series->id);
        $role->assignPermission($permiso_editar_nume_series->id);
        $role->assignPermission($permiso_eliminar_nume_series->id);

        $role->assignPermission($permiso_listar_lprec->id);
        $role->assignPermission($permiso_crear_lprec->id);
        $role->assignPermission($permiso_editar_lprec->id);
        $role->assignPermission($permiso_eliminar_lprec->id);

        $role->assignPermission($permiso_asignar_lprec->id);
        $role->assignPermission($permiso_eliminar_asignacion_lprec->id);

        $role->assignPermission($permiso_listar_sucu->id);
        $role->assignPermission($permiso_crear_sucu->id);
        $role->assignPermission($permiso_editar_sucu->id);
        $role->assignPermission($permiso_eliminar_sucu->id);

        $role->assignPermission($permiso_tproveedor_listar->id);
        $role->assignPermission($permiso_tproveedor_crear->id);
        $role->assignPermission($permiso_tproveedor_editar->id);
        $role->assignPermission($permiso_tproveedor_eliminar->id);

        $role->assignPermission($permiso_listar_cliente->id);
        $role->assignPermission($permiso_crear_cliente->id);
        $role->assignPermission($permiso_editar_cliente->id);
        $role->assignPermission($permiso_eliminar_cliente->id);
        $role->assignPermission($permiso_ver_cliente->id);

        $role->save();

        $role = new Role();
        $role->name = 'Operador';
        $role->slug = 'operador';
        $role->description = 'Usuario Operador del sistema';
        $role->save();

        //Se asignan unos pocos permisos al rol operador
        $role->assignPermission($permiso_listar_rol->id);
        $role->assignPermission($permiso_crear_rol->id);
        $role->assignPermission($permiso_editar_rol->id);
        $role->assignPermission($permiso_eliminar_rol->id);

        $role->assignPermission($permiso_listar_user->id);
        $role->assignPermission($permiso_crear_user->id);
        $role->assignPermission($permiso_editar_user->id);
        $role->assignPermission($permiso_eliminar_user->id);

        $role->assignPermission($permiso_listar_vend->id);
        $role->assignPermission($permiso_crear_vend->id);
        $role->assignPermission($permiso_editar_vend->id);
        $role->assignPermission($permiso_eliminar_vend->id);

        $role->assignPermission($permiso_listar_timb->id);
        $role->assignPermission($permiso_crear_timb->id);
        $role->assignPermission($permiso_editar_timb->id);
        $role->assignPermission($permiso_eliminar_timb->id);

        $role->assignPermission($permiso_listar_sucu->id);
        $role->assignPermission($permiso_crear_sucu->id);
        $role->assignPermission($permiso_editar_sucu->id);
        $role->assignPermission($permiso_eliminar_sucu->id);

        $role->assignPermission($permiso_listar_impuesto->id);
        $role->assignPermission($permiso_crear_impuesto->id);
        $role->assignPermission($permiso_editar_impuesto->id);
        $role->assignPermission($permiso_eliminar_impuesto->id);

        $role->assignPermission($permiso_listar_banco->id);
        $role->assignPermission($permiso_crear_banco->id);
        $role->assignPermission($permiso_editar_banco->id);
        $role->assignPermission($permiso_eliminar_banco->id);

        $role->assignPermission($permiso_listar_cliente->id);
        $role->assignPermission($permiso_crear_cliente->id);
        $role->assignPermission($permiso_editar_cliente->id);
        $role->assignPermission($permiso_eliminar_cliente->id);
        $role->assignPermission($permiso_ver_cliente->id);

        $role->assignPermission($permiso_listar_pedido_venta->id);
        $role->assignPermission($permiso_crear_pedido_venta->id);
        $role->assignPermission($permiso_editar_pedido_venta->id);
        //$role->assignPermission($permiso_eliminar_pedido_venta->id);
        $role->assignPermission($permiso_ver_pedido_venta->id);

        $role->assignPermission($permiso_listar_nume_series->id);
        $role->assignPermission($permiso_crear_nume_series->id);
        $role->assignPermission($permiso_editar_nume_series->id);
        $role->assignPermission($permiso_eliminar_nume_series->id);

        $role->assignPermission($permiso_listar_lprec->id);
        $role->assignPermission($permiso_crear_lprec->id);
        $role->assignPermission($permiso_editar_lprec->id);
        $role->assignPermission($permiso_eliminar_lprec->id);

        $role->assignPermission($permiso_asignar_lprec->id);
        $role->assignPermission($permiso_eliminar_asignacion_lprec->id);

        $role->assignPermission($permiso_clasicli_listar->id);
        $role->assignPermission($permiso_clasicli_crear->id);
        $role->assignPermission($permiso_clasicli_editar->id);
        $role->assignPermission($permiso_clasicli_eliminar->id);

        $role->assignPermission($permiso_listar_empresa->id);
        $role->assignPermission($permiso_editar_empresa->id);
        
        $role->save();
    }
}
