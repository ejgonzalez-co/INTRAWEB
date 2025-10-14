<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\HistoryBudgetAssignation;
use Faker\Generator as Faker;

$factory->define(HistoryBudgetAssignation::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'observation' => $faker->text,
        'name_user' => $faker->word,
        'value_cdp' => $faker->word,
        'value_contract' => $faker->word,
        'cdp_avaible' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'mant_provider_contract_id' => $faker->randomDigitNotNull
    ];
});
