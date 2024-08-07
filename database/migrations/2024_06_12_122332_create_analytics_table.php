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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->decimal('calories', 7, 2);
            $table->decimal('carbohydrate', 6, 2);
            $table->decimal('protein', 6, 2);
            $table->decimal('fat', 6, 2);
            $table->decimal('sodium', 6, 2);
            $table->decimal('volume', 7, 2)->default(0);
            $table->decimal('steps', 7, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
