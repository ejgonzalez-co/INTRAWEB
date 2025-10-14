<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use Faker\Generator as Faker;

$factory->define(dependenciasSerieSubseries::class, function (Faker $faker) {

    return [
        'id_dependencia' => $faker->randomDigitNotNull,
        'id_series_subseries' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
