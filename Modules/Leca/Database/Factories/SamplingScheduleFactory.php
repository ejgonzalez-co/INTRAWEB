<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\SamplingSchedule;
use Faker\Generator as Faker;

$factory->define(SamplingSchedule::class, function (Faker $faker) {

    return [
        'lc_sample_points_id' => $faker->word,
        'lc_officials_id' => $faker->word,
        'users_id' => $faker->word,
        'sampling_date' => $faker->word,
        'direction' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
