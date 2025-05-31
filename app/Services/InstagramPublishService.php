<?php

namespace App\Services;
use App\Models\Post;

class InstagramPublishService
{
    /**
     * The post instance.
     *
     * @var Post
     */
    protected Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    /**
     * Publish the post to Twitter.
     *
     * @return string
     */
    public function publish()
    {

        return "Post published successfully on Instagram: {$this->post->title}";
    }
}
