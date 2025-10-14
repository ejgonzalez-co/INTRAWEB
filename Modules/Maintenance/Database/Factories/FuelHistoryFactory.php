<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\FuelHistory;
use Faker\Generator as Faker;

$factory->define(FuelHistory::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'description' => $faker->word,
        'plaque' => $faker->word,
        'user_name' => $faker->word,
        'id_fuel' => $faker->word,
        'action' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
