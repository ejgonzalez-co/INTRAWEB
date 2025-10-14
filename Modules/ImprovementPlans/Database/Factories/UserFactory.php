<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    return [
        'id_cargo' => $faker->word,
        'id_dependencia' => $faker->word,
        'name' => $faker->word,
        'email' => $faker->word,
        'email_verified_at' => $faker->date('Y-m-d H:i:s'),
        'password' => $faker->word,
        'remember_token' => $faker->word,
        'url_img_profile' => $faker->word,
        'url_digital_signature' => $faker->word,
        'username' => $faker->word,
        'block' => $faker->word,
        'sendEmail' => $faker->word,
        'lastvisitDate' => $faker->date('Y-m-d H:i:s'),
        'registerDate' => $faker->date('Y-m-d H:i:s'),
        'lastResetTime' => $faker->date('Y-m-d H:i:s'),
        'resetCount' => $faker->randomDigitNotNull,
        'logapp' => $faker->word,
        'certificatecrt' => $faker->word,
        'certificatekey' => $faker->word,
        'certificatepfx' => $faker->word,
        'localcertificatecrt' => $faker->word,
        'localcertificatekey' => $faker->word,
        'localcertificatepfx' => $faker->word,
        'cf_user_id' => $faker->word,
        'user_joomla_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
