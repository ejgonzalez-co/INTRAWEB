<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\MonthlyRoutinesOfficials;
use Faker\Generator as Faker;

$factory->define(MonthlyRoutinesOfficials::class, function (Faker $faker) {

    return [
        'lc_monthly_routines_id' => $faker->word,
        'users_id' => $faker->word,
        'user_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
