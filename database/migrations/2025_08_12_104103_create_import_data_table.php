<?php

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
        Schema::create('raw_data', function (Blueprint $table) {
            $table->id();
            $table->string('conversation_id_str')->nullable();
            $table->string('created_at')->nullable(); // waktu tweet dibuat
            $table->integer('favorite_count')->nullable();
            $table->text('full_text')->nullable(); // isi tweet
            $table->string('id_str')->nullable();
            $table->string('image_url')->nullable();
            $table->string('in_reply_to_screen_name')->nullable();
            $table->string('lang', 10)->nullable();
            $table->string('location')->nullable();
            $table->integer('quote_count')->nullable();
            $table->integer('reply_count')->nullable();
            $table->integer('retweet_count')->nullable();
            $table->string('tweet_url')->nullable();
            $table->string('user_id_str')->nullable();
            $table->string('username')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_data');
    }
};
