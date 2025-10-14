<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\Providers;
use Faker\Generator as Faker;

$factory->define(Providers::class, function (Faker $faker) {

    return [
        'type_person' => $faker->word,
        'document_type' => $faker->word,
        'identification' => $faker->word,
        'name' => $faker->word,
        'mail' => $faker->word,
        'regime' => $faker->word,
        'phone' => $faker->word,
        'address' => $faker->word,
        'municipality' => $faker->word,
        'department' => $faker->word,
        'attached' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
