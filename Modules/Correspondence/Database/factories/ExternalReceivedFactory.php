<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\ExternalReceived;
use Faker\Generator as Faker;

$factory->define(ExternalReceived::class, function (Faker $faker) {

    return [
        'dependency_id' => $faker->word,
        'functionary_id' => $faker->word,
        'citizen_id' => $faker->word,
        'type_documentary_id' => $faker->word,
        'dependency_name' => $faker->word,
        'functionary_name' => $faker->word,
        'citizen_name' => $faker->word,
        'user_name' => $faker->word,
        'consecutive' => $faker->word,
        'issue' => $faker->text,
        'folio' => $faker->randomDigitNotNull,
        'annexed' => $faker->word,
        'channel' => $faker->word,
        'novelty' => $faker->text,
        'finish_pqr' => $faker->word,
        'receiving_channel' => $faker->word,
        'attached_document' => $faker->text,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
