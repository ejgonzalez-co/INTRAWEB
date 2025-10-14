<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ButgetExecution;
use Faker\Generator as Faker;

$factory->define(ButgetExecution::class, function (Faker $faker) {

    return [
        'mant_administration_cost_items_id' => $faker->word,
        'minutes' => $faker->word,
        'date' => $faker->word,
        'executed_value' => $faker->randomDigitNotNull,
        'new_value_available' => $faker->randomDigitNotNull,
        'percentage_execution_item' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
