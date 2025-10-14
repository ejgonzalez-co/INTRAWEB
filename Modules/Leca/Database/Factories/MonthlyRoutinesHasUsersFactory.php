<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\MonthlyRoutinesHasUsers;
use Faker\Generator as Faker;

$factory->define(MonthlyRoutinesHasUsers::class, function (Faker $faker) {

    return [
        'lc_monthly_routines_id' => $faker->word,
        'users_id' => $faker->word,
        'lc_list_trials_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
