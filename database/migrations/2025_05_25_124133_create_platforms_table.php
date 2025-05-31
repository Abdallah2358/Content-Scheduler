<?php

use App\Enums\PlatformTypeEnum;
use App\Models\Platform;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // 0: Twitter, 1: Instagram, 2: LinkedIn, 3: Facebook 
            $table->smallInteger('type')
                ->default(PlatformTypeEnum::TWITTER);
            $table->timestamps();
            $table->boolean('is_active')
                ->default(true)
                ->comment('Indicates if the platform is active');
        });

        // create default platforms
        Platform::factory()->create([
            'name' => 'Twitter',
            'type' => PlatformTypeEnum::TWITTER,
        ]);
        Platform::factory()->create([
            'name' => 'Instagram',
            'type' => PlatformTypeEnum::INSTAGRAM,
        ]);
        Platform::factory()->create([
            'name' => 'LinkedIn',
            'type' => PlatformTypeEnum::LINKEDIN,
        ]);
        Platform::factory()->create([
            'name' => 'Facebook',
            'type' => PlatformTypeEnum::FACEBOOK,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
