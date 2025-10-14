<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\WeeklyRoutines;
use Faker\Generator as Faker;

$factory->define(WeeklyRoutines::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'lc_monthly_routines_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
