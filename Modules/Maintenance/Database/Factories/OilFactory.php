<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\Oil;
use Faker\Generator as Faker;

$factory->define(Oil::class, function (Faker $faker) {

    return [
        'mant_resume_machinery_vehicles_yellow_id' => $faker->randomDigitNotNull,
        'mant_oil_element_wear_configurations_id' => $faker->word,
        'register_date' => $faker->date('Y-m-d H:i:s'),
        'show_type' => $faker->word,
        'component' => $faker->word,
        'serial_number' => $faker->randomDigitNotNull,
        'brand' => $faker->word,
        'model' => $faker->randomDigitNotNull,
        'job_place' => $faker->word,
        'number_warranty_extended' => $faker->randomDigitNotNull,
        'work_order' => $faker->word,
        'serial_component' => $faker->word,
        'model_component' => $faker->word,
        'maker_component' => $faker->word,
        'number_control_lab' => $faker->word,
        'grade_oil' => $faker->word,
        'type_fluid' => $faker->word,
        'date_finished' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
