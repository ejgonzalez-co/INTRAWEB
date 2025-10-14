<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\NewsHistoriesPen;
use Faker\Generator as Faker;

$factory->define(NewsHistoriesPen::class, function (Faker $faker) {

    return [
        'new' => $faker->text,
        'type_document' => $faker->word,
        'users_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'work_histories_p_id' => $faker->word,
        'users_id' => $faker->word,
        'documents_id' => $faker->word
    ];
});
