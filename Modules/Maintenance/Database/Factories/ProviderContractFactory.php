<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ProviderContract;
use Faker\Generator as Faker;

$factory->define(ProviderContract::class, function (Faker $faker) {

    return [
        'object' => $faker->word,
        'type_contract' => $faker->word,
        'contract_number' => $faker->word,
        'start_date' => $faker->word,
        'CDP_approved' => $faker->word,
        'CDP_available' => $faker->word,
        'contract_value' => $faker->word,
        'closing_date' => $faker->word,
        'last_minute' => $faker->word,
        'executed_value' => $faker->word,
        'balance_value' => $faker->word,
        'execution_percentage' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'mant_providers_id' => $faker->randomDigitNotNull
    ];
});
