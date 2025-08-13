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
            $table->string('model_path', 50);
            $table->integer('positive_labels');
            $table->integer('negative_labels');
            $table->integer('netral_labels');
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
