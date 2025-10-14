<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\PcPreviousStudies;
use Faker\Generator as Faker;

$factory->define(PcPreviousStudies::class, function (Faker $faker) {

    return [
        'organizational_unit' => $faker->word,
        'program' => $faker->word,
        'subprogram' => $faker->word,
        'project' => $faker->word,
        'legal_marc' => $faker->text,
        'justification_tecnic_description' => $faker->text,
        'justification_tecnic_approach' => $faker->text,
        'justification_tecnic_modality' => $faker->text,
        'fundaments_juridics' => $faker->text,
        'imputation_budget_rubro' => $faker->text,
        'imputation_budget_interventor' => $faker->text,
        'determination_object' => $faker->word,
        'determination_value' => $faker->word,
        'determination_time_limit' => $faker->word,
        'determination_form_pay' => $faker->word,
        'obligation_principal' => $faker->text,
        'obligation_principal_documentation' => $faker->word,
        'situation_estates_public' => $faker->word,
        'situation_estates_public_observation' => $faker->word,
        'situation_estates_private' => $faker->word,
        'situation_estates_private_observation' => $faker->word,
        'solution_servitude' => $faker->word,
        'solution_servitude_observation' => $faker->word,
        'solution_owner' => $faker->word,
        'solution_owner_observation' => $faker->word,
        'process_concilation' => $faker->text,
        'process_licenses_environment' => $faker->word,
        'process_licenses_beach' => $faker->word,
        'process_licenses_forestal' => $faker->word,
        'process_licenses_guadua' => $faker->word,
        'process_licenses_tree' => $faker->word,
        'process_licenses_road' => $faker->word,
        'process_licenses_demolition' => $faker->word,
        'process_licenses_tree_urban' => $faker->word,
        'tipification_danger' => $faker->text,
        'indication_danger_precontractual' => $faker->text,
        'indication_danger_ejecution' => $faker->text,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
