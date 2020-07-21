<?php

use App\Note;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator;

class NoteSeeder extends Seeder
{
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()
            ->each(function (User $user) {
                if ($this->faker->boolean) {
                    factory(Note::class, rand(1, 5))->create([
                        'entity_type' => User::MORPH_NAME,
                        'entity_id' => $user->id,
                    ]);
                }
            });

        Transaction::all()
            ->each(function (Transaction $transaction) {
                if ($this->faker->boolean) {
                    factory(Note::class, rand(1, 5))->create([
                        'entity_type' => Transaction::MORPH_NAME,
                        'entity_id' => $transaction->id,
                    ]);
                }
            });
    }
}
