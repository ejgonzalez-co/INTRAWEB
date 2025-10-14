<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\EquipmentMinorFuelConsumption;
use Faker\Generator as Faker;

$factory->define(EquipmentMinorFuelConsumption::class, function (Faker $faker) {

    return [
        'mant_minor_equipment_fuel_id' => $faker->word,
        'supply_date' => $faker->word,
        'process' => $faker->word,
        'equipment_description' => $faker->word,
        'gallons_supplied' => $faker->randomDigitNotNull,
        'name_receives_equipment' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
