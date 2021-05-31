<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\PasswordReset::class, function (Faker $faker) {

    $user = factory(\App\Usuario::class)->create();

    return [
        'email' => $user->email,
        'token' => \Illuminate\Support\Str::random(60)
    ];
});
