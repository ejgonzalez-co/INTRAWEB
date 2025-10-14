<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\DocumentsMinorEquipment;
use Faker\Generator as Faker;

$factory->define(DocumentsMinorEquipment::class, function (Faker $faker) {

    return [
        'mant_minor_equipment_fuel_id' => $faker->word,
        'name' => $faker->word,
        'url' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
