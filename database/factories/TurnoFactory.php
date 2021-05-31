<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Turno;
use Faker\Generator as Faker;
use Carbon\Carbon;

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

$factory->define(Turno::class, function (Faker $faker) {
    $date = $faker->unique()->dateTimeThisMonth('2020-11-20 12:00:00');
    return [
        'fecha' => Carbon::parse($date)->format('Y-m-d'),
        'horario' => Carbon::parse($date)->format('H:i'),
        'paciente_dni' => null,
        'prestacion_id' => null,
        'precio' => null
    ];
});
