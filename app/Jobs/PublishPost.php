<?php

namespace App\Jobs;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishPost implements ShouldQueue
{
    use Queueable;
    /**
     * The post instance.
     *
     * @var Post
     */
    protected Post $post;

    /**
     * The platform instance.
     *
     * @var Platform
     */
    protected Platform $platform;
    /**
     * Create a new job instance.
     */
    public function __construct(Post $post, Platform $platform)
    {
        $this->post = $post;
        $this->platform = $platform;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->platform->publish($this->post);
        $this->post->platforms()->updateExistingPivot(
            $this->platform->id,
            ['is_published' => true]
        );
    }
}
