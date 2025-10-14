<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\AnnotationEvaluation;
use Faker\Generator as Faker;

$factory->define(AnnotationEvaluation::class, function (Faker $faker) {

    return [
        'pm_evaluations_id' => $faker->word,
        'users_id' => $faker->word,
        'user_name' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
