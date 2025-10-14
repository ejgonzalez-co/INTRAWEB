<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\FuelConsumptionHistoryMinors;
use Faker\Generator as Faker;

$factory->define(FuelConsumptionHistoryMinors::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'action' => $faker->word,
        'description' => $faker->word,
        'name_user' => $faker->word,
        'dependencia' => $faker->word,
        'id_equipment_minor' => $faker->word,
        'fuel_equipment_consumption' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
