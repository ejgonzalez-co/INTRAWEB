<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Faker\Generator as Faker;

$factory->define(ResumeEquipmentLeca::class, function (Faker $faker) {

    return [
        'name_equipment' => $faker->word,
        'internal_code_leca' => $faker->word,
        'inventory_no' => $faker->word,
        'mark' => $faker->word,
        'serie' => $faker->word,
        'model' => $faker->word,
        'location' => $faker->word,
        'software' => $faker->word,
        'purchase_date' => $faker->word,
        'commissioning_date' => $faker->word,
        'date_withdrawal_service' => $faker->word,
        'maker' => $faker->word,
        'provider' => $faker->word,
        'catalogue' => $faker->word,
        'catalogue_location' => $faker->word,
        'idiom' => $faker->word,
        'instructive' => $faker->word,
        'instructional_location' => $faker->word,
        'magnitude_control' => $faker->word,
        'consumables' => $faker->word,
        'resolution' => $faker->word,
        'accessories' => $faker->word,
        'operation_range' => $faker->word,
        'voltage' => $faker->word,
        'use' => $faker->word,
        'use_range' => $faker->word,
        'allowable_error' => $faker->word,
        'minimum_permissible_error' => $faker->word,
        'environmental_operating_conditions' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'dependencias_id' => $faker->word,
        'mant_category_id' => $faker->randomDigitNotNull,
        'responsable' => $faker->randomDigitNotNull
    ];
});
