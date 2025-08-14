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
        Schema::create('modelling', function (Blueprint $table) {
            $table->id();
            $table->string('model_name', 50);
            $table->integer('positif_sentiment');
            $table->integer('netral_sentiment');
            $table->integer('negatif_sentiment');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelling');
    }
};
