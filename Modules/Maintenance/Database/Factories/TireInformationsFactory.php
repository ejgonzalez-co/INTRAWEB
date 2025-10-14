<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\TireInformations;
use Faker\Generator as Faker;

$factory->define(TireInformations::class, function (Faker $faker) {

    return [
        'mant_tire_quantities_id' => $faker->word,
        'date_register' => $faker->word,
        'position_tire' => $faker->randomDigitNotNull,
        'type_tire' => $faker->word,
        'cost_tire' => $faker->word,
        'depth_tire' => $faker->randomDigitNotNull,
        'mileage_initial' => $faker->word,
        'available_depth' => $faker->randomDigitNotNull,
        'general_cost_mm' => $faker->randomDigitNotNull,
        'location_tire' => $faker->word,
        'code_tire' => $faker->word,
        'observation' => $faker->text,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
