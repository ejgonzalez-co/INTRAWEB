<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\DocumentaryClassification\Models\dependencias;
use Faker\Generator as Faker;

$factory->define(dependencias::class, function (Faker $faker) {

    return [
        'id_sede' => $faker->randomDigitNotNull,
        'codigo' => $faker->word,
        'nombre' => $faker->word,
        'codigo_oficina_productora' => $faker->word,
        'cf_user_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
