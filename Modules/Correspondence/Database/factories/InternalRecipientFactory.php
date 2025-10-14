<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\InternalRecipient;
use Faker\Generator as Faker;

$factory->define(InternalRecipient::class, function (Faker $faker) {

    return [

        'type'=>'Usuario',
        'correspondence_internal_id'=>$faker->randomDigitNotNull,
        'users_id' => 17,
        'name'=>"erika"
        
    ];
});
