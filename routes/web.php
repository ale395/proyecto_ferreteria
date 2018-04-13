<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {
<<<<<<< HEAD
	
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
=======
	Route::resource('modulos', 'ModuloController');
	Route::resource('familias', 'FamiliaController');
>>>>>>> ebdd4dde75fe989b0dd4eb5b2a7e3211b9cd45f1
});
