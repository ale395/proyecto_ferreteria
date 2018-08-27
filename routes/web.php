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
	//RUTAS PARA BANCOS
	Route::resource('bancos', 'BancoController');
	Route::get('api/bancos', 'BancoController@apiBancos')->name('api.bancos');

	//RUTAS PARA VENDEDORES
	Route::resource('vendedores', 'VendedorController');
	Route::get('api/Vendedores', 'VendedorController@apiVendedores')->name('api.vendedores');

	//RUTAS PARA CLIENTES
	Route::resource('clientes', 'ClienteController');
	Route::get('api/clientes', 'ClienteController@apiClientes')->name('api.clientes');//seriesVendedores

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
	Route::resource('listaPrecios', 'ListaPrecioCabeceraController');
	Route::get('api/listaPrecios', 'ListaPrecioCabeceraController@apiListaPrecios')->name('api.listaPrecios');

	//RUTAS PARA MODELO LISTA PRECIOS DETALLE
	Route::resource('listaPreciosDet', 'ListaPrecioDetalleController');
	Route::get('api/listaPreciosDet/{list_prec_id}', 'ListaPrecioDetalleController@apiListaPrecios')->name('api.listaPreciosDet');

	//RUTAS PARA MODELO USER
	Route::resource('users', 'UserController');
	Route::get('api/users', 'UserController@apiUsers')->name('api.users');

	//RUTAS PARA MODELO SUCURSALES
	Route::resource('sucursales', 'SucursalController');
	Route::get('api/sucursales', 'SucursalController@apiSucursales')->name('api.sucursales');

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


	//rutas para modelo 'clasificacioncliente'
	Route::resource('clasificacionclientes', 'ClasificacionClienteController');
	Route::get('api/clasificacionclientes', 'ClasificacionClienteController@apiClasifClientes')->name('api.clasificacionclientes');

	//Rutas para modelo Cajeros
	Route::resource('cajeros', 'CajeroController');
	Route::get('api/cajeros', 'CajeroController@apiCajeros')->name('api.cajeros');

});
