<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\Sent;
use Faker\Generator as Faker;

$factory->define(Sent::class, function (Faker $faker) {

    return [
        'consecutive' => $faker->word,
        'state' => $faker->word,
        'type_document' => $faker->word,
        'title' => $faker->text,
        'matter' => $faker->text,
        'attached' => $faker->text,
        'folios' => $faker->word,
        'annexes' => $faker->word,
        'channel' => $faker->word,
        'received_associated' => $faker->randomDigitNotNull,
        'guide' => $faker->word,
        'sent_to_id' => $faker->word,
        'sent_to_name' => $faker->word,
        'remitting_id' => $faker->word,
        'remitting_name' => $faker->word,
        'remitting_dependency' => $faker->word,
        'origin' => $faker->word,
        'id_pqr_finished' => $faker->word,
        'create_user_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
