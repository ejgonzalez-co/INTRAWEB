<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\PaaProcessAttachment;
use Faker\Generator as Faker;

$factory->define(PaaProcessAttachment::class, function (Faker $faker) {

    return [
        'pc_needs_id' => $faker->word,
        'name' => $faker->word,
        'attached' => $faker->text,
        'description' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
