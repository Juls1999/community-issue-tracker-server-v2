<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create an administrator account';

    public function handle(): int
    {
        $first_name = $this->ask('Admin first name');
        $middle_name = $this->ask('Admin middle name');
        $last_name = $this->ask('Admin last name');
        $phone_number = $this->ask('Admin phone number');
        $barangay_id = $this->ask('Barangay');
        $email = $this->ask('Admin email');
        $password = $this->secret('Admin password');

        $validator = Validator::make([
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'barangay_id' => $barangay_id,
            'email' => $email,
            'password' => $password,
        ], [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:users,phone_number'],
            'barangay_id' => ['required', 'integer', 'exists:barangays,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $role = Role::where('name', 'admin')->first();

        if (!$role) {
            $this->error('Admin role not found. Run your seeders first.');

            return self::FAILURE;
        }

        $user = new User();
        $user->first_name = $first_name;
        $user->middle_name = $middle_name;
        $user->last_name = $last_name;
        $user->phone_number = $phone_number;
        $user->email = $email;
        $user->barangay_id = $barangay_id;
        $user->password = Hash::make($password);
        $user->role()->associate($role);
        $user->save();

        $this->info("Admin account created: {$user->email}");

        return self::SUCCESS;
    }
}
