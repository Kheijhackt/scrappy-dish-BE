<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            $table->json('ingredients_used');
            $table->json('steps');

            $table->integer('cook_time_minutes');
            $table->integer('difficulty');
            $table->integer('servings');

            $table->json('cuisine_tags')->nullable();
            $table->json('dish_tags')->nullable();
            $table->json('general_tags')->nullable();

            $table->text('nutrition_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};