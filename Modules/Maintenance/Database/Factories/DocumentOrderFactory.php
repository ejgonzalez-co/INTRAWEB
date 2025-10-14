<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\DocumentOrder;
use Faker\Generator as Faker;

$factory->define(DocumentOrder::class, function (Faker $faker) {

    return [
        'mant_sn_orders_id' => $faker->word,
        'users_id' => $faker->word,
        'nombre' => $faker->word,
        'estado' => $faker->word,
        'adjunto' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
