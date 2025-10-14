<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\Stock;
use Faker\Generator as Faker;

$factory->define(Stock::class, function (Faker $faker) {

    return [
        'id_solicitud_necesidad' => $faker->word,
        'codigo' => $faker->word,
        'articulo' => $faker->word,
        'grupo' => $faker->word,
        'cantidad' => $faker->randomDigitNotNull,
        'costo_unitario' => $faker->word,
        'total' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
