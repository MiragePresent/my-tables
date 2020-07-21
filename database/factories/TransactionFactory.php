<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'amount' => rand(1, 999999),
        'type' => $faker->boolean ? Transaction::TYPE_CREDIT : Transaction::TYPE_DEBIT,
        'created_at' => Carbon::createFromTimestamp($faker->dateTimeBetween('-10 days')->getTimestamp()),
    ];
});
