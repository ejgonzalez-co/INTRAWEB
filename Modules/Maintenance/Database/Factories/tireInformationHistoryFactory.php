<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\tireInformationHistory;
use Faker\Generator as Faker;

$factory->define(tireInformationHistory::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'mant_tire_quantities_id' => $faker->word,
        'user_name' => $faker->word,
        'action' => $faker->word,
        'description' => $faker->text,
        'plaque' => $faker->word,
        'dependencia' => $faker->word,
        'number' => $faker->word,
        'position' => $faker->word,
        'brand' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
