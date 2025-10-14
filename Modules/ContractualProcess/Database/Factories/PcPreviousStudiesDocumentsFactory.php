<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\PcPreviousStudiesDocuments;
use Faker\Generator as Faker;

$factory->define(PcPreviousStudiesDocuments::class, function (Faker $faker) {

    return [
        'type_document' => $faker->word,
        'description' => $faker->text,
        'state' => $faker->word,
        'url_document' => $faker->word,
        'sheet' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'pc_previous_studies_id' => $faker->word
    ];
});
