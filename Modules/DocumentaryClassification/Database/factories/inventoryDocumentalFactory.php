<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\DocumentaryClassification\Models\inventoryDocumental;
use Faker\Generator as Faker;

$factory->define(inventoryDocumental::class, function (Faker $faker) {

    return [
        'id_dependencias' => $faker->randomDigitNotNull,
        'id_series_subseries' => $faker->randomDigitNotNull,
        'description_expedient' => $faker->word,
        'folios' => $faker->word,
        'clasification' => $faker->word,
        'consultation_frequency' => $faker->word,
        'soport' => $faker->word,
        'shelving' => $faker->word,
        'tray' => $faker->word,
        'box' => $faker->word,
        'file' => $faker->word,
        'book' => $faker->word,
        'date_initial' => $faker->word,
        'date_finish' => $faker->word,
        'range_initial' => $faker->word,
        'range_finish' => $faker->word,
        'observation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
