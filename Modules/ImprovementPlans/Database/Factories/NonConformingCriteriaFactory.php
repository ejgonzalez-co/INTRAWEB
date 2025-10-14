<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\NonConformingCriteria;
use Faker\Generator as Faker;

$factory->define(NonConformingCriteria::class, function (Faker $faker) {

    return [
        'evaluations_id' => $faker->word,
        'criteria_name' => $faker->word,
        'status' => $faker->word,
        'observations' => $faker->text,
        'description_cause_analysis' => $faker->text,
        'weight' => $faker->word,
        'possible_causes' => $faker->text,
        'execution_percentage' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
