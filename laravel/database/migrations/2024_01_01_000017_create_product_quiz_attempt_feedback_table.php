<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_quiz_attempt_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('product_quiz_attempts')->onDelete('cascade');
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('suggestions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_quiz_attempt_feedback');
    }
};
