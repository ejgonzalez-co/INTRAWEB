<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ObservacionesDuplicadoColor;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoColor::class, function (Faker $faker) {

    return [
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'lc_ensayo_color_id' => $faker->word
    ];
});
