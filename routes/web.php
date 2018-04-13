<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home2', function () {
    return view('home2');
});

Route::resource('modulos', 'ModuloController');