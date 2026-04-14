<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---- orders ----
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference', 20)->unique();
            $table->string('payment_method', 30); // card | paypal | mobile
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('wallet_applied', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status', 20)->default('pending'); // pending | completed | refunded
            $table->timestamps();
        });

        // ---- order_games (pivot) ----
        Schema::create('order_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_paid', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['order_id', 'game_id']);
        });

        // ---- user_games (library pivot) ----
        // Only create if it doesn't already exist in your project.
        if (!Schema::hasTable('user_games')) {
            Schema::create('user_games', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('game_id')->constrained()->cascadeOnDelete();
                $table->decimal('hours_played', 8, 2)->default(0);
                $table->boolean('is_installed')->default(false);
                $table->timestamp('last_played')->nullable();
                $table->timestamp('purchased_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'game_id']);
            });
        }

        // ---- Add wallet_balance to users if missing ----
        if (!Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('wallet_balance', 10, 2)->default(0)->after('email');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_games');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('user_games');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'wallet_balance')) {
                $table->dropColumn('wallet_balance');
            }
        });
    }
};