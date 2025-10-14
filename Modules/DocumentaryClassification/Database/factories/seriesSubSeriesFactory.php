<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Faker\Generator as Faker;

$factory->define(seriesSubSeries::class, function (Faker $faker) {

    return [
        'no_serie' => $faker->word,
        'name_serie' => $faker->word,
        'no_subserie' => $faker->word,
        'name_subserie' => $faker->word,
        'time_gestion_archives' => $faker->word,
        'time_central_file' => $faker->word,
        'soport' => $faker->word,
        'confidentiality' => $faker->word,
        'full_conversation' => $faker->word,
        'select' => $faker->word,
        'delete' => $faker->word,
        'medium_tecnology' => $faker->word,
        'not_transferable_central' => $faker->word,
        'description_final' => $faker->word,
        'type' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
