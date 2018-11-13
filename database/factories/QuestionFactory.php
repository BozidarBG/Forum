<?php

use Faker\Generator as Faker;

$factory->define(\App\Question::class, function (Faker $faker) {
    return [
        'user_id' =>\App\User::all()->random()->id,
        'title'=>'How do I '.$faker->sentence(6),
        'content'=>'<p>'.$faker->text(300).'</p>',

        ];
});
