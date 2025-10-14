<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\AdministrationCostItem;
use Faker\Generator as Faker;

$factory->define(AdministrationCostItem::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'code_cost' => $faker->word,
        'cost_center_name' => $faker->word,
        'cost_center' => $faker->word,
        'value_item' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'mant_heading_id' => $faker->word,
        'mant_center_cost_id' => $faker->word,
        'mant_budget_assignation_id' => $faker->word
    ];
});
