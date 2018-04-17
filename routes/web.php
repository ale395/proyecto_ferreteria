<?php

Route::get('/', function () {
    //return view('welcome');
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {
	
	//Rutas para MODULOS
	Route::get('modulos', 'ModuloController@index')->name('modulos.index')
		->middleware('permission:modulos.index');

	Route::post('modulos/store', 'ModuloController@store')->name('modulos.store')
		->middleware('permission:modulos.create');

	Route::get('modulos/create', 'ModuloController@create')->name('modulos.create')
		->middleware('permission:modulos.create');

	Route::put('modulos/{modulo}', 'ModuloController@update')->name('modulos.update')
		->middleware('permission:modulos.edit');

	Route::delete('modulos/{modulo}', 'ModuloController@destroy')->name('modulos.destroy')
		->middleware('permission:modulos.destroy');

	Route::get('modulos/{modulo}/edit', 'ModuloController@edit')->name('modulos.edit')
		->middleware('permission:modulos.edit');

	Route::get('api/modulos', 'ModuloController@apiModulos')->name('api.modulos');


	//RUTAS PARA MODELO USER
	Route::get('users', 'UserController@index')->name('users.index');
		//->middleware('permission:modulos.index');

	/*Route::post('modulos/store', 'ModuloController@store')->name('modulos.store')
		->middleware('permission:modulos.create');

	Route::get('modulos/create', 'ModuloController@create')->name('modulos.create')
		->middleware('permission:modulos.create');

	Route::put('modulos/{modulo}', 'ModuloController@update')->name('modulos.update')
		->middleware('permission:modulos.edit');

	Route::delete('modulos/{modulo}', 'ModuloController@destroy')->name('modulos.destroy')
		->middleware('permission:modulos.destroy');

	Route::get('modulos/{modulo}/edit', 'ModuloController@edit')->name('modulos.edit')
		->middleware('permission:modulos.edit');*/

	Route::get('api/users', 'UserController@apiUsers')->name('api.users');


	//Rutas para FAMILIAS - se agregan los permisos correspondientes para c/ operación
	Route::get('familias', 'FamiliaController@index')->name('familias.index')
		->middleware('permission:modulos.index');

	Route::post('familias/store', 'FamiliaController@store')->name('familias.store')
		->middleware('permission:modulos.create');

	Route::get('familias/create', 'FamiliaController@create')->name('familias.create')
		->middleware('permission:modulos.create');

	Route::put('familias/{modulo}', 'FamiliaController@update')->name('familias.update')
		->middleware('permission:modulos.edit');

	Route::delete('familias/{modulo}', 'FamiliaController@destroy')->name('familias.destroy')
		->middleware('permission:modulos.destroy');

	Route::get('familias/{modulo}/edit', 'FamiliaController@edit')->name('familias.edit')
		->middleware('permission:modulos.edit');

	//Route::resource('familias', 'FamiliaController');

		//Rutas para FAMILIAS - se agregan los permisos correspondientes para c/ operación
	Route::get('paises', 'PaisController@index')->name('paises.index');
		//->middleware('permission:paises.index');

	Route::post('paises/store', 'PaisController@store')->name('paises.store')
		->middleware('permission:paises.create');

	Route::get('paises/create', 'PaisController@create')->name('paises.create')
		->middleware('permission:paises.create');

	Route::put('paises/{modulo}', 'PaisController@update')->name('paises.update')
		->middleware('permission:paises.edit');

	Route::delete('paises/{modulo}', 'PaisesController@destroy')->name('paises.destroy')
		->middleware('permission:paises.destroy');

	Route::get('paises/{modulo}/edit', 'PaisesController@edit')->name('paises.edit')
		->middleware('permission:paises.edit');


				//Rutas para FAMILIAS - se agregan los permisos correspondientes para c/ operación
	Route::get('departamentos', 'DepartamentoController@index')->name('departamentos.index');
		//->middleware('permission:departamentos.index');

	Route::post('departamentos/store', 'DepartamentoController@store')->name('departamentos.store')
		->middleware('permission:departamentos.create');

	Route::get('departamentos/create', 'DepartamentoController@create')->name('departamentos.create')
		->middleware('permission:departamentos.create');

	Route::put('departamentos/{modulo}', 'DepartamentoController@update')->name('departamentos.update')
		->middleware('permission:departamentos.edit');

	Route::delete('departamentos/{modulo}', 'DepartamentoController@destroy')->name('departamentos.destroy')
		->middleware('permission:departamentos.destroy');

	Route::get('departamentos/{modulo}/edit', 'DepartamentoController@edit')->name('departamentos.edit')
		->middleware('permission:departamentos.edit');


});
