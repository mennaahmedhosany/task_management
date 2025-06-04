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
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->string('description')->nullable(false)->change();

            $table->integer('priority')->default(0)->change();
        });
    }
};
