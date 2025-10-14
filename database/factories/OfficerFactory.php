<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Officer;
use Faker\Generator as Faker;

$factory->define(Officer::class, function (Faker $faker) {

    return [
        'position_id' => $faker->word,
        'name' => $faker->word,
        'email' => $faker->word,
        'email_verified_at' => $faker->date('Y-m-d H:i:s'),
        'password' => $faker->word,
        'remember_token' => $faker->word,
        'url_img_profile' => $faker->word,
        'url_digital_signature' => $faker->word,
        'uuid' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
