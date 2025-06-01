<?php

use App\Models\Platform;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_disabled_platforms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(Platform::class)->constrained('platforms')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_disabled_platforms');
    }
};
