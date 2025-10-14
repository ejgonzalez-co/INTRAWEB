<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\ObservacionDuplicado;
use Faker\Generator as Faker;

$factory->define(ObservacionDuplicado::class, function (Faker $faker) {

    return [
        'lc_ensayo_aluminio_id' => $faker->word,
        'users_id' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
