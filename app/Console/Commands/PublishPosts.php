<?php

namespace App\Console\Commands;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::where('scheduled_at', '<=', now())
            ->where('status', PostStatusEnum::SCHEDULED)
            ->get();
        if ($posts->isEmpty()) {
            $this->info('No posts to publish.');
            return;
        }
        foreach ($posts as $post) {
            $post->status = PostStatusEnum::PUBLISHED;
            $post->save();
            foreach ($post->platforms as $platform) {
                
            }
            $this->info("Post '{$post->title}' published successfully.");
        }
        $this->info('All scheduled posts have been published.');
    }
}
