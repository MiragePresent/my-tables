<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Note;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'entity_type' => $faker->randomElement([User::MORPH_NAME, Transaction::MORPH_NAME]),
        'entity_id' => function ($row) {
            if ($row['entity_type'] === User::MORPH_NAME) {
                return factory(User::class)->create()->id;
            }

            return factory(Transaction::class)->create()->id;
        },
        'title' => $faker->sentence,
        'description' => $faker->paragraph(rand(1, 4)),
        'created_at' => Carbon::createFromTimestamp($faker->dateTimeBetween('-5 days')->getTimestamp()),
        'updated_at' => function ($row) use ($faker) {
            if ($faker->boolean(20)) {
                /** @var Carbon $createdAt */
                $createdAt = $row['created_at'];

                return Carbon::createFromTimestamp($faker->dateTimeBetween($createdAt->getTimestamp())->getTimestamp());
            }

            return null;
        }
    ];
});
