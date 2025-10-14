<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\PcPreviousStudiesRadication;
use Faker\Generator as Faker;

$factory->define(PcPreviousStudiesRadication::class, function (Faker $faker) {

    return [
        'process' => $faker->word,
        'object' => $faker->text,
        'boss' => $faker->word,
        'value' => $faker->word,
        'date_send' => $faker->date('Y-m-d H:i:s'),
        'notification' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'pc_previous_studies_id' => $faker->word
    ];
});
