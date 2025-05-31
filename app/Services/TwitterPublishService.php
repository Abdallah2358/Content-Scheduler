<?php

namespace App\Services;
use App\Models\Post;

class TwitterPublishService
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

        return "Tweet published successfully: {$this->post->title}";
    }
}
