<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\Officials;
use Faker\Generator as Faker;

$factory->define(Officials::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'pin' => $faker->word,
        'password' => $faker->word,
        'identification_number' => $faker->word,
        'name' => $faker->word,
        'email' => $faker->word,
        'telephone' => $faker->word,
        'direction' => $faker->word,
        'charge' => $faker->word,
        'functions' => $faker->word,
        'state' => $faker->word,
        'receptionist' => $faker->word,
        'firm' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
