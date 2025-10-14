<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Headquarter;
use Faker\Generator as Faker;

$factory->define(Headquarter::class, function (Faker $faker) {

    return [
        'nombre' => $faker->word,
        'descripcion' => $faker->word,
        'codigo' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
