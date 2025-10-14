<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\SamplePoints;
use Faker\Generator as Faker;

$factory->define(SamplePoints::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'point_location' => $faker->word,
        'no_samples_taken' => $faker->word,
        'sector' => $faker->word,
        'tank_feeding' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
