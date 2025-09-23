<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kiểm tra xem column đã tồn tại chưa
            if (!Schema::hasColumn('products', 'instructor_id')) {
                $table->unsignedBigInteger('instructor_id')->nullable()->after('status');
                $table->foreign('instructor_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('products', 'duration_hours')) {
                $table->integer('duration_hours')->default(0)->after('status');
            }
            
            if (!Schema::hasColumn('products', 'level')) {
                $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('status');
            }
            
            if (!Schema::hasColumn('products', 'content')) {
                $table->text('content')->nullable()->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'instructor_id')) {
                $table->dropForeign(['instructor_id']);
                $table->dropColumn('instructor_id');
            }
            
            if (Schema::hasColumn('products', 'duration_hours')) {
                $table->dropColumn('duration_hours');
            }
            
            if (Schema::hasColumn('products', 'level')) {
                $table->dropColumn('level');
            }
            
            if (Schema::hasColumn('products', 'content')) {
                $table->dropColumn('content');
            }
        });
    }
};