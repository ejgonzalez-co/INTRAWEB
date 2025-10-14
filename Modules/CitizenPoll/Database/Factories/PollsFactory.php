<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\CitizenPoll\Models\Polls;
use Faker\Generator as Faker;

$factory->define(Polls::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'gender' => $faker->word,
        'email' => $faker->word,
        'direction_state' => $faker->word,
        'phone' => $faker->word,
        'suscriber_quaity' => $faker->word,
        'aqueduct' => $faker->word,
        'sewerage' => $faker->word,
        'cleanliness' => $faker->word,
        'attention_qualification_respect' => $faker->word,
        'attention_solve_problems' => $faker->word,
        'qualification_waiting_time' => $faker->word,
        'time_solution_petition' => $faker->word,
        'chance' => $faker->word,
        'reports_effectiveness' => $faker->word,
        'aqueduct_benefit_service' => $faker->word,
        'aqueduct_continuity_service' => $faker->word,
        'sewerage_benefit_service' => $faker->word,
        'cleanliness_benefit_service' => $faker->word,
        'cleanliness_qualification_service' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
