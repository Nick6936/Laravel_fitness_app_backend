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
        Schema::create('customs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('name');
            $table->decimal('quantity', 5, 2)->default(0);
            $table->text('description')->nullable();
            $table->decimal('calories', 6, 2);
            $table->decimal('carbohydrate', 5, 2);
            $table->decimal('protein', 5, 2);
            $table->decimal('fat', 5, 2);
            $table->decimal('sodium', 5, 2);
            $table->decimal('volume', 7, 2)->default(0);
            $table->boolean('food')->default(0);
            $table->boolean('drink')->default(0);
            $table->string('photo_name')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customs');
    }
};
