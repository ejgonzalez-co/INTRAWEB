<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\ObservacionesDuplicadoNitritos;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoNitritos::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'lc_ensayo_nitritos_id' => $faker->word
    ];
});
