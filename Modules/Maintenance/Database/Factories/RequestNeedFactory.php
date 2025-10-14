<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\RequestNeed;
use Faker\Generator as Faker;

$factory->define(RequestNeed::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'tipo_solicitud' => $faker->word,
        'tipo_necesidad' => $faker->word,
        'tipo_activo' => $faker->word,
        'activo_id' => $faker->randomDigitNotNull,
        'rubro_nombre' => $faker->word,
        'rubro_id' => $faker->word,
        'rubro_objeto_contrato_id' => $faker->word,
        'valor_disponible' => $faker->randomDigitNotNull,
        'observacion' => $faker->text,
        'estado' => $faker->word,
        'dependencias_id' => $faker->word,
        'users_id' => $faker->word
    ];
});
