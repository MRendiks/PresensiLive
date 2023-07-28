<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin', // Set the desired name
            'email' => 'admin@admin.com', // Set the desired email
            'email_verified_at' => now(),
            'password' => Hash::make('admin'), // Set the desired password
            'is_admin' => true, // Set the desired admin status
            'photo' => null, // Set the desired photo (if any)
            'remember_token' => null,
        ]);
        User::create([
            'name' => 'test', // Set the desired name
            'email' => 'test@test.com', // Set the desired email
            'email_verified_at' => now(),
            'password' => Hash::make('test'), // Set the desired password
            'is_admin' => false, // Set the desired admin status
            'photo' => null, // Set the desired photo (if any)
            'remember_token' => null,
        ]);
        User::create([
            'name' => 'test2', // Set the desired name
            'email' => 'test2@test.com', // Set the desired email
            'email_verified_at' => now(),
            'password' => Hash::make('test'), // Set the desired password
            'is_admin' => false, // Set the desired admin status
            'photo' => null, // Set the desired photo (if any)
            'remember_token' => null,
        ]);
    }
}
