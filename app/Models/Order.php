<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'payment_method',
        'subtotal',
        'discount',
        'wallet_applied',
        'total',
        'status',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'discount'       => 'decimal:2',
        'wallet_applied' => 'decimal:2',
        'total'          => 'decimal:2',
    ];

    // ---- Relationships ----

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'order_games')
                    ->withPivot('price_paid')
                    ->withTimestamps();
    }
}
