<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\ObservacionesDuplicadoAcidez;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoAcidez::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'lc_ensayo_acidez_id' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
