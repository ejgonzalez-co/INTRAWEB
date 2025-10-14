<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\WorkHistoriesActive;
use Faker\Generator as Faker;

$factory->define(WorkHistoriesActive::class, function (Faker $faker) {

    return [
        'type_document' => $faker->word,
        'number_document' => $faker->word,
        'name' => $faker->word,
        'surname' => $faker->word,
        'address' => $faker->word,
        'phone' => $faker->word,
        'email' => $faker->word,
        'gender' => $faker->word,
        'group_ethnic' => $faker->word,
        'birth_date' => $faker->date('Y-m-d H:i:s'),
        'notification' => $faker->word,
        'level_study' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
