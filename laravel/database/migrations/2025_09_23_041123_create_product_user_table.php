<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kiểm tra xem table đã tồn tại chưa
        if (!Schema::hasTable('product_user')) {
            Schema::create('product_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('status', ['enrolled', 'in_progress', 'completed', 'dropped'])->default('enrolled');
                $table->integer('progress_percentage')->default(0);
                $table->timestamp('enrolled_at')->useCurrent();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['product_id', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_user');
    }
};