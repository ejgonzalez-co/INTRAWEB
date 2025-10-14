<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ResumeInventoryLeca;
use Faker\Generator as Faker;

$factory->define(ResumeInventoryLeca::class, function (Faker $faker) {

    return [
        'no_inventory_epa_esp' => $faker->word,
        'leca_code' => $faker->word,
        'description_equipment_name' => $faker->word,
        'maker' => $faker->word,
        'serial_number' => $faker->word,
        'model' => $faker->word,
        'location' => $faker->word,
        'measured_used' => $faker->word,
        'unit_measurement' => $faker->word,
        'resolution' => $faker->word,
        'manufacturer_error' => $faker->word,
        'operation_range' => $faker->word,
        'range_use' => $faker->word,
        'operating_conditions_temperature' => $faker->word,
        'condition_oper_elative_humidity_hr' => $faker->word,
        'condition_oper_voltage_range' => $faker->word,
        'maintenance_metrological_operation_frequency' => $faker->word,
        'calibration_metrological_operating_frequency' => $faker->word,
        'qualification_metrological_operating_frequency' => $faker->word,
        'intermediate_verification_metrological_operating_frequency' => $faker->word,
        'total_interventions' => $faker->word,
        'name_elaborated' => $faker->word,
        'cargo_role_elaborated' => $faker->word,
        'name_updated' => $faker->word,
        'cargo_role_updated' => $faker->word,
        'technical_director' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'mant_category_id' => $faker->randomDigitNotNull,
        'responsable' => $faker->randomDigitNotNull
    ];
});
