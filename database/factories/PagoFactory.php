<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Paciente;
use App\Pago;
use App\Prestacion;
use App\Turno;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Pago::class, function (Faker $faker) {
    return [
        'paciente_dni' => function () {
            return factory(Paciente::class)->create()->dni;
        },
        'turno_id' => function () {
            return factory(Turno::class)->create()->id;
        },
        'monto' => 500,
        'dni_titular' => $faker->numberBetween($min = 10000000, $max = 99999999),
        'nombre_titular' => $faker->name,
        'concepto' => $faker->sentence(3)
    ];
});
