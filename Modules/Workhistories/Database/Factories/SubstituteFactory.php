<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\Substitute;
use Faker\Generator as Faker;

$factory->define(Substitute::class, function (Faker $faker) {

    return [
        'type_document' => $faker->word,
        'number_document' => $faker->word,
        'name' => $faker->word,
        'surname' => $faker->word,
        'address' => $faker->word,
        'phone' => $faker->word,
        'email' => $faker->word,
        'city' => $faker->word,
        'type_substitute' => $faker->word,
        'date_document' => $faker->word,
        'birth_date' => $faker->word,
        'notification' => $faker->word,
        'users_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'work_histories_p_id' => $faker->word
    ];
});
