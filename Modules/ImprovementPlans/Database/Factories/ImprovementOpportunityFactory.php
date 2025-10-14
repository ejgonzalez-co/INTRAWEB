<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Faker\Generator as Faker;

$factory->define(ImprovementOpportunity::class, function (Faker $faker) {

    return [
        'evaluations_id' => $faker->word,
        'source_information_id' => $faker->randomDigitNotNull,
        'type_oportunity_improvements_id' => $faker->randomDigitNotNull,
        'name_opportunity_improvement' => $faker->word,
        'description_opportunity_improvement' => $faker->text,
        'unit_responsible_improvement_opportunity' => $faker->word,
        'official_responsible' => $faker->word,
        'deadline_submission' => $faker->word,
        'evidence' => $faker->word,
        'evaluation_criteria' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
