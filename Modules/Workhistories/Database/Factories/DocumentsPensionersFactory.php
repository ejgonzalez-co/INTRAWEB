<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\DocumentsPensioners;
use Faker\Generator as Faker;

$factory->define(DocumentsPensioners::class, function (Faker $faker) {

    return [
        'type_document' => $faker->word,
        'description' => $faker->text,
        'state' => $faker->word,
        'url_document' => $faker->word,
        'sheet' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'work_histories_p_id' => $faker->word,
        'config_documents_id' => $faker->word
    ];
});
