<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\TypeEvaluation;
use Faker\Generator as Faker;

$factory->define(TypeEvaluation::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'name' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
