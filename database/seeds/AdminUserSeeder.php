<?php

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ];

        $data['email'] = env('ADMIN_EMAIL', $defaults['email']);
        $data['password'] = env('ADMIN_PASSWORD', $defaults['password']);

        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_num|min:4|max:20',
        ];

        $messages = [
            'email.*' => sprintf('ADMIN_EMAIL is invalid. Default one [%s] will be used.', $defaults['email']),
            'password.*' => sprintf('ADMIN_PASSWORD is invalid. Default one [%s] will be used.', $defaults['password']),
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->getMessages() as $field => $error) {
                $this->command->getOutput()->warning(current($error));

                $data[$field] = $defaults[$field];
            }
        }

        try {
            User::create([
                'email' => $data['email'],
                'name' => 'Admin',
                'password' => bcrypt($data['password']),
                'role' => User::ROLE_ADMIN,
            ]);

            $this->command->getOutput()->text('Default admin is created.');
            $this->command->getOutput()->text('    Email: ' . $data['email']);
            $this->command->getOutput()->text('    Password: ' . $data['password']);
        } catch (QueryException $exception) {
            if ((int) $exception->getCode() !== 23000) {
                throw $exception;
            }

            $this->command->getOutput()->error(sprintf('User with email [%s] already exists.', $data['email']));
        }
    }
}
