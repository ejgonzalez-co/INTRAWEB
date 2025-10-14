<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\Customers;
use Faker\Generator as Faker;

$factory->define(Customers::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'pin' => $faker->text,
        'password' => $faker->word,
        'identification_number' => $faker->word,
        'name' => $faker->word,
        'email' => $faker->word,
        'telephone' => $faker->word,
        'extension' => $faker->word,
        'cell_number' => $faker->word,
        'description' => $faker->text,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
