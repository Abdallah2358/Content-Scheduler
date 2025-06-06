<?php

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_platform', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Post::class)
                ->constrained('posts');
            $table->foreignIdFor(Platform::class)
                ->constrained('platforms');
            $table->unique(['post_id', 'platform_id'], 'post_platform_unique');
            $table->boolean('is_published')->default(false)
                ->comment('Indicates if the post has been published on the platform');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_platform');
    }
};
