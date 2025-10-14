<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\Internal;
use Faker\Generator as Faker;

$factory->define(Internal::class, function (Faker $faker) {

    return [
        'consecutive' => $faker->word,
        'state' => $faker->word,
        'title' => $faker->text,
        'content' => $faker->text,
        'folios' => $faker->word,
        'annexes' => $faker->word,
        'annexes_description' => $faker->text,
        'type_document' => $faker->word,
        'require_answer' => $faker->word,
        'answer_consecutive' => $faker->word,
        'template' => $faker->word,
        'editor' => $faker->word,
        'origen' => $faker->word,
        'recipients' => $faker->text,
        'document' => $faker->text,
        'document_pdf' => $faker->text,
        'from' => $faker->word,
        'dependency_from' => $faker->word,
        'elaborated' => $faker->word,
        'reviewd' => $faker->word,
        'approved' => $faker->word,
        'elaborated_names' => $faker->text,
        'reviewd_names' => $faker->text,
        'approved_names' => $faker->text,
        'creator_name' => $faker->word,
        'creator_dependency_name' => $faker->word,
        'elaborated_now' => $faker->word,
        'reviewd_now' => $faker->word,
        'approved_now' => $faker->word,
        'number_review' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'times_read' => $faker->word,
        'user_from_last_update' => $faker->word,
        'user_for_last_update' => $faker->word,
        'year' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'type' => 1,
        'users_id' => 17,
        'dependencias_id' => 5

    ];
});
