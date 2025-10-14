<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\FuelDocument;
use Faker\Generator as Faker;

$factory->define(FuelDocument::class, function (Faker $faker) {

    return [
        'mant_vehicle_fuels_id' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->text,
        'url_document_fuel' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
