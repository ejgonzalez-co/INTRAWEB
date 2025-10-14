<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {

    return [
        'category_id' => $faker->word,
        'title' => $faker->word,
        'slug' => $faker->word,
        'cover_path' => $faker->word,
        'content' => $faker->text,
        'online' => $faker->word,
        'user_id' => $faker->word,
        'visits' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
