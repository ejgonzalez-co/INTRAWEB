<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\DocumentsAssets;
use Faker\Generator as Faker;

$factory->define(DocumentsAssets::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->word,
        'url_document' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'mant_resume_machinery_vehicles_yellow_id' => $faker->randomDigitNotNull
    ];
});
