<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()
            ->has(Post::factory()->count(5), 'posts')
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        User::factory(10)
            ->has(Post::factory()->count(2), 'posts')
            ->create();

        $defaultPlatforms = Platform::where('id', '<=', 4)->get();
        $posts = Post::all();
        foreach ($posts as $post) {
            // Random count between 1 and total available platforms
            $randomPlatforms = $defaultPlatforms
                ->random(rand(
                    1,
                    $defaultPlatforms->count()
                ))->pluck('id');
            $post->platforms()->attach($randomPlatforms);
        }
    }
}
