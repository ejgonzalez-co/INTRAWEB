<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ImportSparePartsProviderContract;
use Faker\Generator as Faker;

$factory->define(ImportSparePartsProviderContract::class, function (Faker $faker) {

    return [
        'code' => $faker->word,
        'name' => $faker->word,
        'value' => $faker->word,
        'description' => $faker->word,
        'file_import' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'mant_provider_contract_id' => $faker->randomDigitNotNull
    ];
});
