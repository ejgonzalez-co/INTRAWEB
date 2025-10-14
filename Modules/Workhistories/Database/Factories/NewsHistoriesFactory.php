<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\NewsHistories;
use Faker\Generator as Faker;

$factory->define(NewsHistories::class, function (Faker $faker) {

    return [
        'new' => $faker->text,
        'type_document' => $faker->word,
        'users_name' => $faker->word,
        'work_histories_documents_id' => $faker->word,
        'work_histories_id' => $faker->word,
        'users_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
