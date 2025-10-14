<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ObservacionesDuplicadoOlor;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoOlor::class, function (Faker $faker) {

    return [
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'lc_ensayo_olor_id' => $faker->word,
        'users_id' => $faker->word
    ];
});
