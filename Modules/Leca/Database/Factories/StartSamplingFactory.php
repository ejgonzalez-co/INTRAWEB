<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\StartSampling;
use Faker\Generator as Faker;

$factory->define(StartSampling::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'user_name' => $faker->word,
        'vehicle_arrival_time' => $faker->word,
        'leca_outlet' => $faker->word,
        'time_sample_completion' => $faker->word,
        'service_agreement' => $faker->word,
        'sample_request' => $faker->word,
        'time' => $faker->word,
        'name' => $faker->word,
        'environmental_conditions' => $faker->word,
        'potentiometer_multiparameter' => $faker->word,
        'chlorine_residual' => $faker->word,
        'turbidimeter' => $faker->word,
        'another_test' => $faker->word,
        'other_equipment' => $faker->word,
        'chlorine_test' => $faker->word,
        'factor' => $faker->word,
        'residual_color' => $faker->word,
        'media_and_DPR' => $faker->word,
        'mean_chlorine_value' => $faker->word,
        'DPR_chlorine_residual' => $faker->word,
        'date_last_ph_adjustment' => $faker->word,
        'pending' => $faker->word,
        'asymmetry' => $faker->word,
        'digital_thermometer' => $faker->word,
        'which' => $faker->word,
        'arrival_LECA' => $faker->word,
        'observations' => $faker->text,
        'witness' => $faker->word,
        'initial' => $faker->word,
        'intermediate' => $faker->word,
        'end' => $faker->word,
        'standard_ph' => $faker->word,
        'chlorine_residual_target' => $faker->word,
        'initial_pattern' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
