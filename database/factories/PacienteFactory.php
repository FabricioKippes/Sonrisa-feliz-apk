<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ObraSocial;
use App\Paciente;
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

$factory->define(Paciente::class, function (Faker $faker) {
    return [
        'dni' => $faker->unique()->numberBetween($min = 10000000, $max = 99999999),
        'nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'fecha_nacimiento' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'telefono' => $faker->isbn10,
        'usuario_id' => function () {
            return factory(App\Usuario::class)->create()->id;
        },
        'obra_social_id' => function () {
            $os = ObraSocial::firstOrCreate(['id' => 1]);
            return $os->id;
        }
    ];
});
