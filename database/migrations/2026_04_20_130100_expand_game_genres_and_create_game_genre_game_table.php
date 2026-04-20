<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExpandGameGenresAndCreateGameGenreGameTable extends Migration
{
    public function up()
    {
        Schema::table('game_genres', function (Blueprint $table) {
            if (!Schema::hasColumn('game_genres', 'name')) {
                $table->string('name')->unique()->after('id');
            }

            if (!Schema::hasColumn('game_genres', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }

            if (!Schema::hasColumn('game_genres', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }

            if (!Schema::hasColumn('game_genres', 'display_flag')) {
                $table->boolean('display_flag')->default(true)->after('description');
            }
        });

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

        Schema::table('game_genres', function (Blueprint $table) {
            if (Schema::hasColumn('game_genres', 'display_flag')) {
                $table->dropColumn('display_flag');
            }
            if (Schema::hasColumn('game_genres', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('game_genres', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('game_genres', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
}
