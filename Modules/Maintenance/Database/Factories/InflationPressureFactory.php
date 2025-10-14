<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\InflationPressure;
use Faker\Generator as Faker;

$factory->define(InflationPressure::class, function (Faker $faker) {

    return [
        'registration_date' => $faker->word,
        'tire_reference' => $faker->word,
        'inflation_pressure' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
