<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\QuotaPartsPensioner;
use Faker\Generator as Faker;

$factory->define(QuotaPartsPensioner::class, function (Faker $faker) {

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
        'rh' => $faker->word,
        'birth_date' => $faker->word,
        'notification' => $faker->word,
        'level_study_other' => $faker->word,
        'level_study' => $faker->word,
        'phone_event' => $faker->word,
        'name_event' => $faker->word,
        'state' => $faker->word,
        'deceased' => $faker->word,
        'observation_deceased' => $faker->text,
        'total_documents' => $faker->randomDigitNotNull,
        'users_name' => $faker->word,
        'time_work' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word
    ];
});
