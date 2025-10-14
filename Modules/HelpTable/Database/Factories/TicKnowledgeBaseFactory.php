<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicKnowledgeBase;
use Faker\Generator as Faker;

$factory->define(TicKnowledgeBase::class, function (Faker $faker) {

    return [
        'ht_tic_type_request_id' => $faker->word,
        'users_id' => $faker->word,
        'ht_tic_requests_id' => $faker->word,
        'affair' => $faker->word,
        'knowledge_description' => $faker->word,
        'knowledge_state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
