<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\Goal;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {

    return [
        'goal_type' => $faker->word,
        'goal_name' => $faker->word,
        'goal_weight' => $faker->word,
        'indicator_description' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
