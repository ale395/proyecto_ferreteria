<?php

Route::get('/', function () {
    //return view('welcome');
    return view('login');
    //return view('home2');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home2', function () {
    return view('home2');
});