<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\QuotaPartsNewsUsers;
use Faker\Generator as Faker;

$factory->define(QuotaPartsNewsUsers::class, function (Faker $faker) {

    return [
        'new' => $faker->text,
        'type_document' => $faker->word,
        'users_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word
    ];
});
