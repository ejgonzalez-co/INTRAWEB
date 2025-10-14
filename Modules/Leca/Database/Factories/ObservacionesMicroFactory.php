<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\ObservacionesMicro;
use Faker\Generator as Faker;

$factory->define(ObservacionesMicro::class, function (Faker $faker) {

    return [
        'lc_ensayos_microbiologicos_id' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'ensayo' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
