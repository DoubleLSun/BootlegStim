<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_recommended')->default(true);
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('review_content')->nullable();
            $table->unsignedInteger('hours_played')->default(0);
            $table->unsignedInteger('helpful_votes')->default(0);
            $table->timestamp('review_date')->nullable();
            $table->timestamps();

            $table->unique(['game_id', 'user_id']);
            $table->index(['game_id', 'is_recommended']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_reviews');
    }
}
