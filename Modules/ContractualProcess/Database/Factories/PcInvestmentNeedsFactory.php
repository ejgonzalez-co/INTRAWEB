<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\PcInvestmentNeeds;
use Faker\Generator as Faker;

$factory->define(PcInvestmentNeeds::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'description' => $faker->word,
        'estimated_value' => $faker->randomDigitNotNull,
        'observation' => $faker->word,
        'pc_needs_id' => $faker->word
    ];
});
