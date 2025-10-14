<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\Documents;
use Faker\Generator as Faker;

$factory->define(Documents::class, function (Faker $faker) {

    return [
        'type_document' => $faker->word,
        'description' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'work_histories_config_documents_id' => $faker->word,
        'work_histories_id' => $faker->word
    ];
});
