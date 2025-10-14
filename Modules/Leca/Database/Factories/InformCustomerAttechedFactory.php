<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\leca\Models\InformCustomerAtteched;
use Faker\Generator as Faker;

$factory->define(InformCustomerAtteched::class, function (Faker $faker) {

    return [
        'lc_rm_report_management_id' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'name' => $faker->word,
        'user_name' => $faker->word,
        'attachment' => $faker->text,
        'status' => $faker->word,
        'comments' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
