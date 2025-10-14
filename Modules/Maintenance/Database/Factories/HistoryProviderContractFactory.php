<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\HistoryProviderContract;
use Faker\Generator as Faker;

$factory->define(HistoryProviderContract::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'value_contract' => $faker->word,
        'name_user' => $faker->word,
        'observation' => $faker->text,
        'cd_avaible' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'object' => $faker->text,
        'provider' => $faker->word
    ];
});
