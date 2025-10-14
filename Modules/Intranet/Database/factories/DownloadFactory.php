<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Download;
use Faker\Generator as Faker;

$factory->define(Download::class, function (Faker $faker) {

    return [
        'owner_id' => $faker->randomDigitNotNull,
        'title' => $faker->word,
        'alias' => $faker->word,
        'filename' => $faker->word,
        'filesize' => $faker->randomDigitNotNull,
        'filename_play' => $faker->word,
        'filename_preview' => $faker->word,
        'author' => $faker->word,
        'author_email' => $faker->word,
        'author_url' => $faker->word,
        'license' => $faker->word,
        'license_url' => $faker->word,
        'video_filename' => $faker->word,
        'image_filename' => $faker->word,
        'image_filename_spec1' => $faker->word,
        'image_filename_spec2' => $faker->word,
        'image_download' => $faker->word,
        'link_external' => $faker->word,
        'mirror1link' => $faker->word,
        'mirror1title' => $faker->word,
        'mirror1target' => $faker->word,
        'mirror2link' => $faker->word,
        'mirror2title' => $faker->word,
        'mirror2target' => $faker->word,
        'description' => $faker->text,
        'features' => $faker->text,
        'changelog' => $faker->text,
        'notes' => $faker->text,
        'version' => $faker->word,
        'directlink' => $faker->word,
        'date' => $faker->date('Y-m-d H:i:s'),
        'publish_up' => $faker->date('Y-m-d H:i:s'),
        'publish_down' => $faker->date('Y-m-d H:i:s'),
        'hits' => $faker->randomDigitNotNull,
        'published' => $faker->word,
        'approved' => $faker->word,
        'checked_out' => $faker->randomDigitNotNull,
        'checked_out_time' => $faker->date('Y-m-d H:i:s'),
        'ordering' => $faker->randomDigitNotNull,
        'unaccessible_file' => $faker->randomDigitNotNull,
        'metakey' => $faker->text,
        'metadesc' => $faker->text,
        'metadata' => $faker->text,
        'intranet_downloads_categories_id' => $faker->randomDigitNotNull,
        'intranet_downloads_licenses_id' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
