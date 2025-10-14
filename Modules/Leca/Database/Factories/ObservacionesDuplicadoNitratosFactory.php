<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\ObservacionesDuplicadoNitratos;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoNitratos::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'lc_ensayo_nitratos_id' => $faker->word
    ];
});
