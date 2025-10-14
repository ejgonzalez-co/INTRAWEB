<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\DocumentsSubstitute;
use Faker\Generator as Faker;

$factory->define(DocumentsSubstitute::class, function (Faker $faker) {

    return [
        'type_document' => $faker->word,
        'description' => $faker->text,
        'state' => $faker->word,
        'url_document' => $faker->word,
        'sheet' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'config_documents_id' => $faker->word,
        'work_histories_p_substitute_id' => $faker->word
    ];
});
