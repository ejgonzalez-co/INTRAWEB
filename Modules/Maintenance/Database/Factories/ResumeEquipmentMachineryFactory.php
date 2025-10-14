<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Faker\Generator as Faker;

$factory->define(ResumeEquipmentMachinery::class, function (Faker $faker) {

    return [
        'name_equipment' => $faker->word,
        'no_identification' => $faker->word,
        'no_inventory' => $faker->word,
        'mark' => $faker->word,
        'model' => $faker->word,
        'no_motor' => $faker->word,
        'serie' => $faker->word,
        'ubication' => $faker->word,
        'acquisition_contract' => $faker->word,
        'no_invoice' => $faker->word,
        'purchase_price' => $faker->randomDigitNotNull,
        'equipment_warranty' => $faker->word,
        'service_start_date' => $faker->word,
        'retirement_date' => $faker->word,
        'frecuency_use_month' => $faker->word,
        'frecuency_use_hours' => $faker->word,
        'operates_equipment' => $faker->word,
        'observation' => $faker->word,
        'status' => $faker->word,
        'dependencias_id' => $faker->word,
        'mant_category_id' => $faker->randomDigitNotNull,
        'responsable' => $faker->randomDigitNotNull,
        'mant_providers_id' => $faker->randomDigitNotNull
    ];
});
