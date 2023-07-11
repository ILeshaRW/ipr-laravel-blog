<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Comment::factory(25)->create();
    }
}
