<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Subscriber;
use Faker\Generator as Faker;

$factory->define(App\Models\Subscriber::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->name,
        'state' => App\Models\Subscriber::STATE_ACTIVE,
    ];
});
