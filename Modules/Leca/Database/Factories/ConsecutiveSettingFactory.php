<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\ConsecutiveSetting;
use Faker\Generator as Faker;

$factory->define(ConsecutiveSetting::class, function (Faker $faker) {

    return [
        'lc_sample_taking_id' => $faker->word,
        'users_id' => $faker->word,
        'lc_customers_id' => $faker->word,
        'consecutive' => $faker->word,
        'nex_consecutiveIC' => $faker->word,
        'name_customer' => $faker->word,
        'coments_consecutive' => $faker->text,
        'date_report' => $faker->word,
        'user_name' => $faker->word,
        'nex_consecutiveIE' => $faker->word,
        'status' => $faker->word,
        'query_report' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
