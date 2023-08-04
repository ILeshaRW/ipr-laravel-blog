<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(
            [
                'name' => 'Алексей',
                'last_name' => 'Зайцев',
                'email' => 'lesharw@bk.ru',
                'password' => Hash::make('qwerty123'),
            ]
        );

        User::factory(10)->create();
    }
}
