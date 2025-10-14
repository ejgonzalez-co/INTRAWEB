<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\TireWearHistory;
use Faker\Generator as Faker;

$factory->define(TireWearHistory::class, function (Faker $faker) {

    return [
        'mant_tire_wears_id' => $faker->word,
        'users_id' => $faker->word,
        'user_name' => $faker->word,
        'action' => $faker->word,
        'plaque' => $faker->word,
        'position' => $faker->word,
        'revision_pressure' => $faker->randomDigitNotNull,
        'revision_mileage' => $faker->randomDigitNotNull,
        'wear_total' => $faker->randomDigitNotNull,
        'status' => $faker->word,
        'observation' => $faker->text,
        'descripcion' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
