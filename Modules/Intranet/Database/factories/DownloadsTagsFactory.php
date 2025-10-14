<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\DownloadsTags;
use Faker\Generator as Faker;

$factory->define(DownloadsTags::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'alias' => $faker->word,
        'link_ext' => $faker->word,
        'description' => $faker->text,
        'published' => $faker->word,
        'checked_out' => $faker->randomDigitNotNull,
        'checked_out_time' => $faker->date('Y-m-d H:i:s'),
        'ordering' => $faker->randomDigitNotNull,
        'intranet_downloads_categories_id' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
