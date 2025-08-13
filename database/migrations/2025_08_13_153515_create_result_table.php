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
        Schema::create('result', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at')->nullable();

            // Data jumlah train & test
            $table->integer('data_training')->nullable();
            $table->integer('data_training_positive')->nullable();
            $table->integer('data_training_negative')->nullable();
            $table->integer('data_training_netral')->nullable();
            $table->integer('data_testing')->nullable();

            // Confusion matrix
            $table->integer('tp_positive')->nullable();
            $table->integer('fp_positive')->nullable();
            $table->integer('fn_positive')->nullable();

            $table->integer('tp_negative')->nullable();
            $table->integer('fp_negative')->nullable();
            $table->integer('fn_negative')->nullable();

            $table->integer('tp_netral')->nullable();
            $table->integer('fp_netral')->nullable();
            $table->integer('fn_netral')->nullable();

            // Prediksi total
            $table->integer('predict_positive')->nullable();
            $table->integer('predict_negative')->nullable();
            $table->integer('predict_netral')->nullable();

            // Simpan model (optional)
            $table->text('vocabulary')->nullable();
            $table->text('vocab_weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result');
    }
};
