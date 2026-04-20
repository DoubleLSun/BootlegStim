<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowMultiplePricingsPerGame extends Migration
{
    public function up()
    {
        Schema::table('game_pricings', function (Blueprint $table) {
            // Remove one-pricing-per-game constraint so admin can manage multiple pricing options.
            $table->dropUnique('game_pricings_game_id_unique');
            $table->index('game_id');
        });
    }

    public function down()
    {
        Schema::table('game_pricings', function (Blueprint $table) {
            $table->dropIndex(['game_id']);
            $table->unique('game_id');
        });
    }
}
