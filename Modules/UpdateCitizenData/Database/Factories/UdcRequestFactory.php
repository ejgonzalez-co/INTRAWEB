<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\UpdateCitizenData\Models\UdcRequest;
use Faker\Generator as Faker;

$factory->define(UdcRequest::class, function (Faker $faker) {

    return [
        'payment_account_number' => $faker->word,
        'subscriber_quality' => $faker->word,
        'citizen_name' => $faker->word,
        'document_type' => $faker->word,
        'identification' => $faker->word,
        'gender' => $faker->word,
        'telephone' => $faker->word,
        'email' => $faker->word,
        'date_birth' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
