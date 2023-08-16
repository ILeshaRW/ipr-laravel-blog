<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::where('email', 'lesharw@bk.ru')->first();
        if (!$admin) {
            User::factory()->createOne(
                [
                    'name' => 'Алексей',
                    'last_name' => 'Зайцев',
                    'email' => 'lesharw@bk.ru',
                    'password' => Hash::make('qwerty123'),
                ]
            );
        }

        User::factory(10)->create();
    }
}
