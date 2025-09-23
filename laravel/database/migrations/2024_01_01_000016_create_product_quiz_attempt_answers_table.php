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
        Schema::create('product_quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('product_quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('product_quiz_questions')->onDelete('cascade');
            $table->string('answer');
            $table->boolean('is_correct')->default(false);
            $table->integer('points_earned')->default(0);
            $table->timestamps();
            
            $table->unique(['attempt_id', 'question_id']);
            $table->index(['question_id', 'is_correct']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_quiz_attempt_answers');
    }
};
