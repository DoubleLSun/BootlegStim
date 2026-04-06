<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
        * games
        *  id
        * - title
        * - description
        * - price
        * - release_date
        * - is_featured (boolean)
        * - created_by (admin_id)
        * - cover_image
         */
        if(!Schema::hasTable("games")){
            Schema::create('games', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->date('release_date');
                $table->boolean('is_featured')->default(false);
                $table->unsignedBigInteger('created_by');
                $table->unsignedBigInteger('developer_id');
                $table->unsignedBigInteger('publisher_id');
                $table->string('cover_image')->nullable();
                $table->timestamps();
            });
        };
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
