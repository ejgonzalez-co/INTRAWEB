<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\TechnicalSheets;
use Faker\Generator as Faker;

$factory->define(TechnicalSheets::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'dependencias_id' => $faker->word,
        'pc_management_unit_id' => $faker->word,
        'cities_id' => $faker->randomDigitNotNull,
        'code_bppiepa' => $faker->word,
        'validity' => $faker->word,
        'date_presentation' => $faker->date('Y-m-d H:i:s'),
        'update_date' => $faker->date('Y-m-d H:i:s'),
        'project_name' => $faker->word,
        'responsible_user' => $faker->word,
        'municipal_development_plan' => $faker->word,
        'period' => $faker->word,
        'strategic_line' => $faker->word,
        'program' => $faker->word,
        'subprogram' => $faker->word,
        'sector' => $faker->word,
        'identification_project' => $faker->word,
        'description_problem_need' => $faker->word,
        'project_description' => $faker->word,
        'justification' => $faker->word,
        'background' => $faker->word,
        'service_coverage' => $faker->word,
        'number_inhabitants' => $faker->randomDigitNotNull,
        'neighborhood' => $faker->word,
        'commune' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
