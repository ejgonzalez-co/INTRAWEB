<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\HistoryCostItem;
use Faker\Generator as Faker;

$factory->define(HistoryCostItem::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'name_cost' => $faker->word,
        'observation' => $faker->text,
        'code_cost' => $faker->word,
        'cost_center' => $faker->word,
        'cost_center_name' => $faker->word,
        'value_item' => $faker->randomDigitNotNull,
        'name_user' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'mant_budget_assignation_id' => $faker->word
    ];
});
