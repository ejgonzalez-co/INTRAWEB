<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ReportManagement;
use Faker\Generator as Faker;

$factory->define(ReportManagement::class, function (Faker $faker) {

    return [
        'lc_sample_taking_id' => $faker->word,
        'users_id' => $faker->word,
        'lc_customers_id' => $faker->word,
        'user_name' => $faker->word,
        'consecutive' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'coments_consecutive' => $faker->word
    ];
});
