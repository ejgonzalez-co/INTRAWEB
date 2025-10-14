<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\TypeImprovementOpportunity;
use Faker\Generator as Faker;

$factory->define(TypeImprovementOpportunity::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'name' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
