<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\TireWears;
use Faker\Generator as Faker;

$factory->define(TireWears::class, function (Faker $faker) {

    return [
        'mant_tire_informations_id' => $faker->word,
        'registration_depth' => $faker->randomDigitNotNull,
        'revision_date' => $faker->word,
        'wear_total' => $faker->randomDigitNotNull,
        'revision_mileage' => $faker->randomDigitNotNull,
        'route_total' => $faker->randomDigitNotNull,
        'wear_cost_mm' => $faker->randomDigitNotNull,
        'cost_km' => $faker->randomDigitNotNull,
        'revision_pressure' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
