<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\DownloadFileVote;
use Faker\Generator as Faker;

$factory->define(DownloadFileVote::class, function (Faker $faker) {

    return [
        'rating' => $faker->word,
        'ordering' => $faker->randomDigitNotNull,
        'intranet_downloads_id' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
