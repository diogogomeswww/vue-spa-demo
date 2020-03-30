<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Subscriber;
use Faker\Generator as Faker;

$factory->define(App\Models\Field::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'type' => \App\Models\Field::TYPE_STRING,
    ];
});
