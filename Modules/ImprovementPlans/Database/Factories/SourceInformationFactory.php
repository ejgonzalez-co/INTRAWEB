<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\SourceInformation;
use Faker\Generator as Faker;

$factory->define(SourceInformation::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'name' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
