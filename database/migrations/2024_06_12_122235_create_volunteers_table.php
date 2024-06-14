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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('name')->nullable();
            $table->integer('age')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('sex')->nullable();
            $table->string('ethnicity')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('bodyType')->nullable();
            $table->string('bodyGoal')->nullable();
            $table->string('bloodPressure')->nullable();
            $table->string('bloodSugar')->nullable();
            $table->boolean('isPremium')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
