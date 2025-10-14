<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\DocumentsProviderContract;
use Faker\Generator as Faker;

$factory->define(DocumentsProviderContract::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->word,
        'url_document' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'mant_provider_contract_id' => $faker->randomDigitNotNull
    ];
});
