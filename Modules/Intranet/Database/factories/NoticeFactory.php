<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Notice;
use Faker\Generator as Faker;

$factory->define(Notice::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'content' => $faker->text,
        'users_name' => $faker->word,
        'state' => $faker->word,
        'date_start' => $faker->date('Y-m-d H:i:s'),
        'date_end' => $faker->date('Y-m-d H:i:s'),
        'views' => $faker->randomDigitNotNull,
        'image_banner' => $faker->text,
        'image_presentation' => $faker->text,
        'keywords' => $faker->text,
        'featured' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'intranet_news_category_id' => $faker->word
    ];
});
