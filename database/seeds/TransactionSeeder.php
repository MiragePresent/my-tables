<?php

use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::customers()
            ->get()
            ->each(function (User $customer) {
                factory(Transaction::class, rand(1, 20))->create(['user_id' => $customer->id]);
            });
    }
}
