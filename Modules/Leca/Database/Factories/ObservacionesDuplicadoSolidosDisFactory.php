<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ObservacionesDuplicadoSolidosDis;
use Faker\Generator as Faker;

$factory->define(ObservacionesDuplicadoSolidosDis::class, function (Faker $faker) {

    return [
        'lc_ensayo_solidos_dis_id' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
