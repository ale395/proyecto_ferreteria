<?php

Route::get('/', function () {
    //return view('welcome');
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {
	
	Route::get('datatables/translation/spanish', 'DatatablesTranslationController@spanish');

	//RUTAS PARA GESTION DE PERMISOS POR ROL
	Route::resource('gestionpermisos', 'PermissionRoleController');
	Route::get('api/gestionpermisos', 'PermissionRoleController@apiRolePermission')->name('api.rolepermission');

	//RUTAS PARA TIMBRADOS
	Route::resource('timbrados', 'TimbradoController');
	Route::get('api/timbrados', 'TimbradoController@apiTimbrados')->name('api.timbrados');
	
	//RUTAS PARA IMPUESTOS
	Route::resource('impuestos', 'ImpuestoController');
	Route::get('api/impuestos', 'ImpuestoController@apiImpuestos')->name('api.impuestos');
	
	//RUTAS PARA COTIZACIONES
	Route::resource('cotizaciones', 'CotizacionController', ['parameters'=>['cotizaciones'=>'cotizacion']]);
	Route::get('api/cotizaciones', 'CotizacionController@apiCotizaciones')->name('api.cotizaciones');
	Route::get('api/cotizaciones/venta/{moneda}', 'CotizacionController@apiCotizacionValorVenta')->name('api.cotizaciones.valorventa');

	//RUTAS PARA IMPUESTOS
	Route::resource('articulos', 'ArticuloController');
	Route::get('api/articulos', 'ArticuloController@apiArticulos')->name('api.articulos');
	Route::get('api/articulos/cotizacion/{articulo}/{lista_precio}', 'ArticuloController@apiArticulosCotizacion')->name('api.articulos.cotizacion');
	Route::get('api/articulos/costo/{articulo}', 'ArticuloController@apiArticulosCosto')->name('api.articulos.costo');

	//RUTAS PARA Monedas
	Route::resource('monedas', 'MonedaController');
	Route::get('api/monedas', 'MonedaController@apiMonedas')->name('api.monedas');
	Route::get('api/monedas/select', 'MonedaController@apiMonedasSelect')->name('api.monedas.select');
	
	//RUTAS PARA BANCOS
	Route::resource('bancos', 'BancoController');
	Route::get('api/bancos', 'BancoController@apiBancos')->name('api.bancos');

	//RUTAS PARA DEPOSITO
	Route::resource('depositos', 'DepositoController');
	Route::get('api/deposito', 'DepositoController@apiDepositos')->name('api.depositos');

	//RUTAS PARA TIPOS DE EMPLEADOS
	Route::resource('tiposEmpleados', 'TipoEmpleadoController');
	Route::get('api/tiposEmpleados', 'TipoEmpleadoController@apiTiposEmpleados')->name('api.tiposEmpleados');

	Route::resource('empleados', 'EmpleadoController');
	//Para eliminar sucursales dentro de la vista EDIT
	Route::post('empleados/{empleado_id}/{sucursal_id}', 'EmpleadoController@deleteSucursal')->name('empleados.sucursal');
	//Listado de Empleados para la vista INDEX
	Route::get('api/empleados', 'EmpleadoController@apiEmpleados')->name('api.empleados');
	//Listado de sucursales a la que el empleado ya tiene acceso para la vista EDIT
	Route::get('api/empleados-sucursales/{empleado_id}', 'EmpleadoController@apiEmpleadosSucursales')->name('api.empleados-sucursales');
	//Listado de Sucursales para el Select2 en la vista AGREGARSUCURSAL
	Route::get('api/empleados/sucursales/{empleado_id}', 'EmpleadoController@apiSucursales')->name('api.empleados.sucursales');
	//Para agregar una sucursal al empleado en la vista AGREGARSUCURSAL
	Route::post('empleados/sucursales', 'EmpleadoController@agregarSucursal')->name('empleados.agregar.sucursal');
	Route::get('api/empleados/cambioSucursal/{empleado_id}/{sucursal_id}', 'EmpleadoController@cambioSucursal')->name('api.empleados.cambio.sucursal');

	//RUTAS PARA VENDEDORES
	Route::resource('vendedores', 'VendedorController');
	Route::get('api/Vendedores', 'VendedorController@apiVendedores')->name('api.vendedores');

	//RUTAS PARA CLIENTES
	Route::resource('clientes', 'ClienteController');
	Route::get('api/clientes', 'ClienteController@apiClientes')->name('api.clientes');
	Route::get('api/clientes/ventas', 'ClienteController@apiClientesVentas')->name('api.clientes.ventas');
	Route::get('api/articulos/ventas', 'ArticuloController@apiArticulosVentas')->name('api.articulos.ventas');

	Route::get('api/zonas/select', 'ZonaController@apiZonasSelect')->name('api.zonas.select');

	//RUTAS PARA NUMERACION DE SERIES
	Route::resource('series', 'SerieController');
	Route::get('api/series', 'SerieController@apiSeries')->name('api.series');
	
	//RUTAS PARA FORMAS DE PAGO
	Route::resource('formasPagos', 'FormaPagoController', ['parameters'=>['formasPagos'=>'formaPago']]);
	Route::get('api/formasPagos', 'FormaPagoController@apiFormasPagos')->name('api.formasPagos');
	
	//RUTAS PARA NUMERACION DE SERIES
	Route::resource('seriesVendedores', 'SerieVendedorController');
	Route::get('api/seriesVendedores', 'SerieVendedorController@apiSeriesVendedores')->name('api.seriesVendedores');

	//RUTAS PARA EMPRESA
	Route::resource('empresa', 'EmpresaController');

	//RUTAS PARA MODELO "ROLES"
	Route::resource('roles', 'RoleController');
	Route::get('api/roles', 'RoleController@apiRole')->name('api.roles');

	//RUTAS PARA MODELO LISTA PRECIOS CABECERA
	Route::get('listaPrecios/actualizar', 'ListaPrecioCabeceraController@actualizar')->name('listaPrecios.actualizar');
	Route::post('listaPrecios/actualizarPrecios', 'ListaPrecioCabeceraController@actualizarPrecios')->name('listaPrecios.actualizarPrecios');
	Route::resource('listaPrecios', 'ListaPrecioCabeceraController');
	Route::get('api/listaPrecios', 'ListaPrecioCabeceraController@apiListaPrecios')->name('api.listaPrecios');
	Route::get('api/listaPrecios/select', 'ListaPrecioCabeceraController@apiListaPreciosSelect')->name('api.listaPrecios.select');
	

	//RUTAS PARA MODELO LISTA PRECIOS DETALLE
	Route::resource('listaPreciosDet', 'ListaPrecioDetalleController');
	Route::get('api/listaPreciosDet/{list_prec_id}', 'ListaPrecioDetalleController@apiListaPrecios')->name('api.listaPreciosDet');

	//RUTAS PARA MODELO USER
	Route::resource('users', 'UserController');
	Route::get('api/users', 'UserController@apiUsers')->name('api.users');

	//RUTA PARA EL CONTROLADOR DE PEDIDOS - VENTAS
	Route::resource('pedidosVentas', 'PedidoVentaController');
	Route::get('api/pedidosVentas', 'PedidoVentaController@apiPedidosVentas')->name('api.pedidos.ventas');
	Route::get('api/pedidos/cliente/{cliente_id}', 'PedidoVentaController@apiPedidosCliente')->name('api.pedidos.cliente');
	Route::get('api/pedidos/detalles/{array_pedidos}', 'PedidoVentaController@apiPedidosDetalles')->name('api.pedidos.detalles');

	//RUTA PARA EL CONTROLADOR DE FACTURACION - VENTAS
	Route::resource('facturacionVentas', 'FacturaVentaController');
	Route::get('api/facturacionVentas', 'FacturaVentaController@apiFacturacionVentas')->name('api.facturacion.ventas');

	//RUTA PARA EL CONTROLADOR DE AJUSTEE DE INVENTARIO
	Route::resource('ajustesInventarios', 'AjusteInventarioController');
	Route::get('api/ajustesInventarios', 'AjusteInventarioController@apiAjustesInventarios')->name('api.ajustes.inventarios');

	//RUTAS PARA MODELO SUCURSALES
	Route::resource('sucursales', 'SucursalController');
	Route::get('api/sucursales', 'SucursalController@apiSucursales')->name('api.sucursales');
	Route::get('api/sucursales/buscador', 'SucursalController@apiSucursalesBuscador')->name('api.sucursales.buscador');

	//rutas para modelo 'Familias'
	Route::resource('familias', 'FamiliaController');
	Route::get('api/familias', 'FamiliaController@apiFamilias')->name('api.familias');

	//rutas para modelo 'Lineas'
	Route::resource('lineas', 'LineaController');
	Route::get('api/lineas', 'LineaController@apiLineas')->name('api.lineas');

	//rutas para modelo 'rubros'
	Route::resource('rubros', 'RubroController');
	Route::get('api/rubros', 'RubroController@apiRubros')->name('api.rubros');

	//rutas para modelo 'unidadesmedidas'
	Route::resource('unidadesMedidas', 'UnidadMedidaController');
	Route::get('api/unidadesMedidas', 'UnidadMedidaController@apiUnidadesMedidas')->name('api.unidadesMedidas');

	//rutas para modelo 'conceptoajuste'
	Route::resource('conceptos', 'ConceptoAjusteController');
	Route::get('api/conceptosajuste', 'ConceptoAjusteController@apiConceptosAjuste')->name('api.conceptos');
	Route::get('api/conceptosAjustes/buscador', 'ConceptoAjusteController@apiConceptosAjustesBuscador')->name('api.conceptosAjustes.buscador');

	//rutas para modelo 'conceptos_caja'
	Route::resource('conceptocaja', 'ConceptoCajaController');
	Route::get('api/conceptocaja', 'ConceptoCajaController@apiConceptosAjuste')->name('api.conceptocaja');	

	//rutas para modelo 'clasificacioncliente'
	Route::resource('clasificacionclientes', 'ClasificacionClienteController');
	Route::get('api/clasificacionclientes', 'ClasificacionClienteController@apiClasifClientes')->name('api.clasificacionclientes');
	Route::get('api/clasificacionclientes/select', 'ClasificacionClienteController@apiTiposClientesSelect')->name('api.tipos.clientes.select');

	//Rutas para modelo Cajeros
	Route::resource('cajeros', 'CajeroController');
	Route::get('api/cajeros', 'CajeroController@apiCajeros')->name('api.cajeros');

	//Rutas para tipos proveedoress
	Route::resource('tiposproveedores', 'TipoProveedorController');
	Route::get('api/tiposproveedores', 'TipoProveedorController@apiTipoProveedor')->name('api.tiposproveedores');

	//Rutas para proveedores
	Route::resource('proveedores', 'ProveedorController');
	Route::get('api/proveedores', 'ProveedorController@apiProveedores')->name('api.proveedores');
	Route::get('api/proveedores/buscador', 'ProveedorController@apiProveedoresBuscador')->name('api.proveedores.buscador');

	//Rutas para orden de compra
	Route::resource('ordencompra', 'OrdenCompraController');
	Route::get('api/ordencompra', 'OrdenCompraController@apiOrdenCompra')->name('api.ordencompra');

	//Rutas para orden de compra
	Route::resource('compra', 'CompraController');
	Route::get('api/compra', 'CompraController@apiCompras')->name('api.compra');

	//Para ver los errores de PHP
	Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

});
