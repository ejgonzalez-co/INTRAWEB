<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ObservacionesEspectro;
use Faker\Generator as Faker;

$factory->define(ObservacionesEspectro::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'ensayo' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'lc_ensayo_espectro_id' => $faker->word
    ];
});
