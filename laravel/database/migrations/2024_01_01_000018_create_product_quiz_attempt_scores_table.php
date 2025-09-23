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
        Schema::create('product_quiz_attempt_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('product_quiz_attempts')->onDelete('cascade');
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('incorrect_answers')->default(0);
            $table->integer('skipped_questions')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->integer('time_taken')->default(0); // Thời gian làm bài (giây)
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
        Schema::dropIfExists('product_quiz_attempt_scores');
    }
};
