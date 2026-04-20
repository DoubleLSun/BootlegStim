<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminGameFlagsAndSelectedPricing extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            if (!Schema::hasColumn('games', 'is_delisted')) {
                $table->boolean('is_delisted')->default(false)->after('is_featured');
            }

            if (!Schema::hasColumn('games', 'use_pricing_tag')) {
                $table->boolean('use_pricing_tag')->default(false)->after('is_delisted');
            }

            if (!Schema::hasColumn('games', 'selected_pricing_id')) {
                $table->unsignedBigInteger('selected_pricing_id')->nullable()->after('use_pricing_tag');
            }
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            if (Schema::hasColumn('games', 'selected_pricing_id')) {
                $table->dropColumn('selected_pricing_id');
            }
            if (Schema::hasColumn('games', 'use_pricing_tag')) {
                $table->dropColumn('use_pricing_tag');
            }
            if (Schema::hasColumn('games', 'is_delisted')) {
                $table->dropColumn('is_delisted');
            }
        });
    }
}
