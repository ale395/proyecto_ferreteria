<?php

use Faker\Generator as Faker;

$factory->define(App\Cliente::class, function (Faker $faker) {
    $tipo_persona = $faker->randomElement($array = array ('F','J'));
    if ($tipo_persona == 'F') {
    	return [
	        'tipo_persona' => 'F',
	        'nombre' => $faker->firstName,
	        'apellido' => $faker->lastName,
	        'nro_cedula' => $faker->unique()->numberBetween($min = 800000, $max = 7000000)
	    ];
    } elseif ($tipo_persona == 'J') {
    	return [
	        'tipo_persona' => 'J',
	        'razon_social' => $faker->name,
	        'ruc' => $faker->unique()->numberBetween($min = 800000, $max = 7000000)
	    ];
    }
});