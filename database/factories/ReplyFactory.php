<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'user_id'=>\App\User::all()->random()->id,
        'question_id'=>\App\Question::all()->random()->id,
        'content'=>'<p>'.$faker->sentence(10).'</p>'
    ];
});
