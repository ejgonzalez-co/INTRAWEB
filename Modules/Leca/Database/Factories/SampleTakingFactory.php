<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\SampleTaking;
use Faker\Generator as Faker;

$factory->define(SampleTaking::class, function (Faker $faker) {

    return [
        'lc_start_sampling_id' => $faker->word,
        'lc_sample_points_id' => $faker->word,
        'users_id' => $faker->word,
        'user_name' => $faker->word,
        'sample_reception_code' => $faker->word,
        'address' => $faker->word,
        'type_water' => $faker->word,
        'humidity' => $faker->word,
        'temperature' => $faker->word,
        'hour_from_to' => $faker->word,
        'prevailing_climatic_characteristics' => $faker->word,
        'test_perform' => $faker->word,
        'container_number' => $faker->word,
        'hour' => $faker->word,
        'according' => $faker->word,
        'sample_characteristics' => $faker->word,
        'observations' => $faker->text,
        'refrigeration' => $faker->word,
        'filtered_sample' => $faker->word,
        'hno3' => $faker->word,
        'h2so4' => $faker->word,
        'hci' => $faker->word,
        'naoh' => $faker->word,
        'acetate' => $faker->word,
        'ascorbic_acid' => $faker->word,
        'charge' => $faker->word,
        'process' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
