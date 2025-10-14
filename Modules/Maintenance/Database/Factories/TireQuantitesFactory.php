<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\TireQuantites;
use Faker\Generator as Faker;

$factory->define(TireQuantites::class, function (Faker $faker) {

    return [
        'mant_resume_machinery_vehicles_yellow_id' => $faker->randomDigitNotNull,
        'dependencias_id' => $faker->word,
        'plaque' => $faker->word,
        'tire_quantity' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
