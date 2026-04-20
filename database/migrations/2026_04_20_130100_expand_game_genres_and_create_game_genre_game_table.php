<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExpandGameGenresAndCreateGameGenreGameTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('game_genre_game')) {
            Schema::create('game_genre_game', function (Blueprint $table) {
                $table->id();
                $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
                $table->foreignId('genre_id')->constrained('game_genres')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['game_id', 'genre_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('game_genre_game');
    }
}
