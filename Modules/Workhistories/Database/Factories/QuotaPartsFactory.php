<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\QuotaParts;
use Faker\Generator as Faker;

$factory->define(QuotaParts::class, function (Faker $faker) {

    return [
        'name_company' => $faker->word,
        'time_work' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'url_document' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'config_documents_id' => $faker->word
    ];
});
