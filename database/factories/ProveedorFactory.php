<?php

use Faker\Generator as Faker;

$factory->define(App\Proveedor::class, function (Faker $faker) {
    //$tipo_persona = $faker->randomElement($array = array ('F','J'));
    /*
    return [
        'tipo_persona' => 'F',
        'nombre' => $faker->firstName,
        'razon_social' => $faker->company,
        'nro_cedula' => $faker->unique()->numberBetween($min = 800000, $max = 7000000)
    ];
    */
});