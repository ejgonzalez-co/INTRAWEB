<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\AssetManagement;
use Faker\Generator as Faker;

$factory->define(AssetManagement::class, function (Faker $faker) {

    return [
        'nombre_activo' => $faker->word,
        'tipo_mantenimiento' => $faker->word,
        'kilometraje_actual' => $faker->word,
        'kilometraje_recibido_proveedor' => $faker->word,
        'nombre_proveedor' => $faker->word,
        'no_salida_almacen' => $faker->word,
        'no_factura' => $faker->word,
        'no_solicitud' => $faker->word,
        'actividad' => $faker->text,
        'repuesto' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
