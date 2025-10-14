<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\MonthlyRoutines;
use Faker\Generator as Faker;

$factory->define(MonthlyRoutines::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'routine_start_date' => $faker->word,
        'routine_end_date' => $faker->word,
        'state_routine' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
