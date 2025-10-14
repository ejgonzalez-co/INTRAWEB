<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ObservacionesDuplicadoSulfatos;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoSulfatos::class, function (Faker $faker) {

    return [
        'lc_ensayo_sulfatos_id' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
