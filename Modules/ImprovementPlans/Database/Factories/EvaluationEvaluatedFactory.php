<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\EvaluationEvaluated;
use Faker\Generator as Faker;

$factory->define(EvaluationEvaluated::class, function (Faker $faker) {

    return [
        'evaluator_id' => $faker->word,
        'evaluated_id' => $faker->word,
        'type_evaluation' => $faker->word,
        'evaluation_name' => $faker->word,
        'objective_evaluation' => $faker->text,
        'evaluation_scope' => $faker->text,
        'evaluation_site' => $faker->word,
        'evaluation_start_date' => $faker->word,
        'evaluation_start_time' => $faker->word,
        'evaluation_end_date' => $faker->word,
        'evaluation_end_time' => $faker->word,
        'unit_responsible_for_evaluation' => $faker->word,
        'evaluation_officer' => $faker->word,
        'process' => $faker->text,
        'attached' => $faker->text,
        'status' => $faker->word,
        'evaluation_process_attachment' => $faker->word,
        'general_description_evaluation_results' => $faker->text,
        'name_improvement_plan' => $faker->word,
        'is_accordance' => $faker->word,
        'execution_percentage' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
