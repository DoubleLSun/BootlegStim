<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * user_profile fields added to users table:
     * - avatar_url       : profile picture
     * - banner_url       : profile background banner
     * - bio              : short "about me" text
     * - location         : freetext location
     * - steam_level      : numeric level badge
     * - status           : enum online | away | busy | offline
     * - status_game_id   : FK → games.id, the game they are currently playing
     * - profile_theme    : optional CSS class name for theming
     * - last_online_at   : timestamp
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('email');
            $table->string('banner_url')->nullable()->after('avatar_url');
            $table->text('bio')->nullable()->after('banner_url');
            $table->string('location', 120)->nullable()->after('bio');
            $table->unsignedSmallInteger('steam_level')->default(0)->after('location');
            $table->enum('status', ['online', 'away', 'busy', 'offline'])->default('offline')->after('steam_level');
            $table->unsignedBigInteger('status_game_id')->nullable()->after('status');
            $table->timestamp('last_online_at')->nullable()->after('status_game_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_url',
                'banner_url',
                'bio',
                'location',
                'steam_level',
                'status',
                'status_game_id',
                'last_online_at',
            ]);
        });
    }
}