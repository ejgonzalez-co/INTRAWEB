<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicProvider;
use Faker\Generator as Faker;

$factory->define(TicProvider::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'type_person' => $faker->word,
        'document_type' => $faker->word,
        'identification' => $faker->word,
        'profession' => $faker->word,
        'professional_card_number' => $faker->word,
        'regime' => $faker->word,
        'address' => $faker->word,
        'phone' => $faker->word,
        'cellular' => $faker->word,
        'state' => $faker->word,
        'ciiu_activities' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
