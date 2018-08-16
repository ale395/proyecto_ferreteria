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

	//RUTAS PARA CONCEPTOS
	Route::resource('tconceptos', 'ConceptoController');
	Route::get('api/tconceptos', 'ConceptoController@apiConceptos')->name('api.tconceptos');

	//RUTAS PARA NUMERACION DE SERIES
	Route::resource('numeSeries', 'NumeracionSerieController');
	Route::get('api/numeSeries', 'NumeracionSerieController@apiNumeSeries')->name('api.numeSeries');

	//RUTAS PARA MODELO "MODULOS"
	Route::resource('modulos', 'ModuloController');
	Route::get('api/modulos', 'ModuloController@apiModulo')->name('api.modulos');

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
	
	//RUTAS PARA MODELO "PAISES"
	Route::resource('paises', 'PaisController', ['parameters' => ['paises' => 'pais']]);
	Route::get('api/paises', 'PaisController@apiPais')->name('api.paises');

	//RUTAS PARA MODELO "DEPARTAMENTOS"
	Route::resource('departamentos', 'DepartamentoController');
	Route::get('api/departamentos', 'DepartamentoController@apiDepartamento')->name('api.departamentos');

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


	/*
	//Rutas para FAMILIAS - se agregan los permisos correspondientes para c/ operación
	Route::get('familias', 'FamiliaController@index')->name('familias.index')
		->middleware('permission:modulos.index');

	Route::post('familias/store', 'FamiliaController@store')->name('familias.store')
		->middleware('permission:modulos.create');

	Route::get('familias/create', 'FamiliaController@create')->name('familias.create')
		->middleware('permission:modulos.create');

	Route::put('familias/{familia}', 'FamiliaController@update')->name('familias.update')
		->middleware('permission:modulos.edit');

	Route::delete('familias/{familia}', 'FamiliaController@destroy')->name('familias.destroy')
		->middleware('permission:modulos.destroy');

	Route::get('familias/{familia}/edit', 'FamiliaController@edit')->name('familias.edit')
		->middleware('permission:modulos.edit');

	//Route::resource('familias', 'FamiliaController');
	*/

		//Rutas para FAMILIAS - se agregan los permisos correspondientes para c/ operación
	/*Route::get('paises', 'PaisController@index')->name('paises.index');
		//->middleware('permission:paises.index');

	Route::post('paises/store', 'PaisController@store')->name('paises.store')
		->middleware('permission:paises.create');

	Route::get('paises/create', 'PaisController@create')->name('paises.create')
		->middleware('permission:paises.create');

	Route::put('paises/{pais}', 'PaisController@update')->name('paises.update')
		->middleware('permission:paises.edit');

	Route::delete('paises/{pais}', 'PaisesController@destroy')->name('paises.destroy')
		->middleware('permission:paises.destroy');

	Route::get('paises/{pais}/edit', 'PaisesController@edit')->name('paises.edit')
		->middleware('permission:paises.edit');


	//Rutas para FAMILIAS - se agregan los permisos correspondientes para c/ operación
	Route::get('departamentos', 'DepartamentoController@index')->name('departamentos.index');
		//->middleware('permission:departamentos.index');

	Route::post('departamentos/store', 'DepartamentoController@store')->name('departamentos.store')
		->middleware('permission:departamentos.create');

	Route::get('departamentos/create', 'DepartamentoController@create')->name('departamentos.create')
		->middleware('permission:departamentos.create');

	Route::put('departamentos/{departamento}', 'DepartamentoController@update')->name('departamentos.update')
		->middleware('permission:departamentos.edit');

	Route::delete('departamentos/{departamento}', 'DepartamentoController@destroy')->name('departamentos.destroy')
		->middleware('permission:departamentos.destroy');

	Route::get('departamentos/{departamento}/edit', 'DepartamentoController@edit')->name('departamentos.edit')
		->middleware('permission:departamentos.edit');*/


});
