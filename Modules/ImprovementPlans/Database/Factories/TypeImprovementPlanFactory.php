<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\TypeImprovementPlan;
use Faker\Generator as Faker;

$factory->define(TypeImprovementPlan::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'code' => $faker->word,
        'name' => $faker->word,
        'state' => $faker->word,
        'days_anticipated' => $faker->randomDigitNotNull,
        'message' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
