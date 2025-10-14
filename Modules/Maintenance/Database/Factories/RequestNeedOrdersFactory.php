<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\RequestNeedOrders;
use Faker\Generator as Faker;

$factory->define(RequestNeedOrders::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'descripcion' => $faker->word,
        'unidad' => $faker->word,
        'cantidad' => $faker->word,
        'tipo_mantenimiento' => $faker->word,
        'observacion' => $faker->text,
        'tipo_solicitud' => $faker->word,
        'usuario' => $faker->word,
        'mant_sn_request_id' => $faker->word
    ];
});
