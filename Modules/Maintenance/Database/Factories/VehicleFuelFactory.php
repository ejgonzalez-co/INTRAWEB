<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\VehicleFuel;
use Faker\Generator as Faker;

$factory->define(VehicleFuel::class, function (Faker $faker) {

    return [
        'dependencies_id' => $faker->word,
        'mant_resume_machinery_vehicles_yellow_id' => $faker->word,
        'mant_asset_type_id' => $faker->word,
        'asset_name' => $faker->word,
        'invoice_number' => $faker->word,
        'invoice_date' => $faker->word,
        'tanking_hour' => $faker->word,
        'driver_name' => $faker->word,
        'fuel_type' => $faker->word,
        'current_mileage' => $faker->randomDigitNotNull,
        'fuel_quantity' => $faker->randomDigitNotNull,
        'gallon_price' => $faker->randomDigitNotNull,
        'total_price' => $faker->randomDigitNotNull,
        'current_hourmeter' => $faker->randomDigitNotNull,
        'previous_hourmeter' => $faker->randomDigitNotNull,
        'variation_tanking_hour' => $faker->randomDigitNotNull,
        'previous_mileage' => $faker->randomDigitNotNull,
        'variation_route_hour' => $faker->randomDigitNotNull,
        'performance_by_gallon' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
