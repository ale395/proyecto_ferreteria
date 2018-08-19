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

	//RUTAS PARA BANCOS
	Route::resource('bancos', 'BancoController');
	Route::get('api/bancos', 'BancoController@apiBancos')->name('api.bancos');

	//RUTAS PARA VENDEDORES
	Route::resource('vendedores', 'VendedorController');
	Route::get('api/Vendedores', 'VendedorController@apiVendedores')->name('api.vendedores');

	//RUTAS PARA CLIENTES
	Route::resource('clientes', 'ClienteController');
	Route::get('api/clientes', 'ClienteController@apiClientes')->name('api.clientes');

	//RUTAS PARA NUMERACION DE SERIES
	Route::resource('numeSeries', 'NumeracionSerieController');
	Route::get('api/numeSeries', 'NumeracionSerieController@apiNumeSeries')->name('api.numeSeries');

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
	Route::get('api/familias', 'FamiliaController@apiFamilia')->name('api.familias');
<<<<<<< HEAD
	
	//RUTAS PARA MODELO "PAISES"
	Route::resource('paises', 'PaisController', ['parameters' => ['paises' => 'pais']]);
	Route::get('api/paises', 'PaisController@apiPais')->name('api.paises');

	//RUTAS PARA MODELO "CIUDADES"
	Route::resource('ciudades', 'CiudadController');
	Route::get('api/ciudades', 'CiudadController@apiCiudades')->name('api.ciudades');
=======
>>>>>>> 5d5b68b41abeab7bba4dcb702fb12fd689e26a33

	//rutas para modelo 'Lineas'
	Route::resource('lineas', 'LineaController');
	Route::get('api/lineas', 'LineaController@apiLineas')->name('api.lineas');

	//rutas para modelo 'rubros'
	Route::resource('rubros', 'RubroController');
	Route::get('api/rubros', 'RubroController@apiRubros')->name('api.rubros');

	//rutas para modelo 'unidadmedidas'
	Route::resource('unidadmedidas', 'UnidadMedidaController');
	Route::get('api/unidadmedidas', 'UnidadMedidaController@apiUnidadMedidas')->name('api.unidadmedidas');

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
