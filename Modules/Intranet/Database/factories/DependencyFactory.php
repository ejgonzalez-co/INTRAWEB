<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Dependency;
use Faker\Generator as Faker;

$factory->define(Dependency::class, function (Faker $faker) {

    return [
        'id_sede' => $faker->word,
        'codigo' => $faker->word,
        'nombre' => $faker->word,
        'codigo_oficina_productora' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
