<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\EvaluationProcess;
use Faker\Generator as Faker;

$factory->define(EvaluationProcess::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'state' => $faker->word,
        'id_user' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
