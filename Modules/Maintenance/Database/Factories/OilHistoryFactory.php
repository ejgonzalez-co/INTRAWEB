<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\OilHistory;
use Faker\Generator as Faker;

$factory->define(OilHistory::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'action' => $faker->word,
        'description' => $faker->word,
        'name_user' => $faker->word,
        'plaque' => $faker->word,
        'dependencia' => $faker->word,
        'consecutive' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
