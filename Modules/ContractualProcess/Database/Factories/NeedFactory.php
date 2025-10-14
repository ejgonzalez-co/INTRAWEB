<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\Need;
use Faker\Generator as Faker;

$factory->define(Need::class, function (Faker $faker) {

    return [
        'pc_paa_calls_id' => $faker->word,
        'pc_process_leaders_id' => $faker->word,
        'name_process' => $faker->word,
        'state' => $faker->word,
        'total_value_paa' => $faker->randomDigitNotNull,
        'total_operating_value' => $faker->randomDigitNotNull,
        'future_validity_status' => $faker->word,
        'total_investment_value' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
