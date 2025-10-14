<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\FunctioningNeed;
use Faker\Generator as Faker;

$factory->define(FunctioningNeed::class, function (Faker $faker) {

    return [
        'pc_needs_id' => $faker->word,
        'description' => $faker->word,
        'estimated_start_date' => $faker->date('Y-m-d H:i:s'),
        'selection_mode' => $faker->word,
        'estimated_total_value' => $faker->randomDigitNotNull,
        'estimated_value_current_validity' => $faker->randomDigitNotNull,
        'additions' => $faker->word,
        'total_value' => $faker->randomDigitNotNull,
        'future_validity_status' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
