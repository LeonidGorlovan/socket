<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const int TOTAL_USERS = 10;

    private const string PASSWORD = 'password';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= self::TOTAL_USERS; $i++) {
            User::query()->updateOrCreate(
                ['email' => "user{$i}@mail.com"],
                [
                    'name' => "User {$i}",
                    'password' => self::PASSWORD,
                ]
            );
        }
    }
}
