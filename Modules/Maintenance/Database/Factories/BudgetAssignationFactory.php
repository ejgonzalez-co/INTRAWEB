<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\BudgetAssignation;
use Faker\Generator as Faker;

$factory->define(BudgetAssignation::class, function (Faker $faker) {

    return [
        'mant_provider_contract_id' => $faker->randomDigitNotNull,
        'value_cdp' => $faker->randomDigitNotNull,
        'consecutive_cdp' => $faker->word,
        'value_contract' => $faker->randomDigitNotNull,
        'cdp_available' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
