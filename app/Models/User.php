<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'wallet_balance',
        'avatar_url',
        'banner_url',
        'bio',
        'location',
        'steam_level',
        'status',
        'status_game_id',
        'last_online_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at'    => 'datetime',
    ];

    // ---- Library: games the user owns ----
    public function games()
    {
        return $this->belongsToMany(Game::class, 'user_games')
                    ->withPivot(['hours_played', 'is_installed', 'last_played', 'purchased_at'])
                    ->withTimestamps();
    }

    // ---- Orders placed by the user ----
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    // ---- Cart items (if you use a CartItem model) ----
    public function cartItems()
    {
        return $this->hasMany(\App\Models\CartItem::class);
    }

    // ── Relationships ────────────────────────────────────────────────────────

    /** The game the user is currently playing / last played. */
    public function statusGame()
    {
        return $this->belongsTo(Game::class, 'status_game_id');
    }

    /**
     * Games owned by the user.
     * Pivot table: user_games (user_id, game_id, playtime_minutes, last_played_at)
     */

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Safe avatar URL with generated fallback. */
    public function getAvatarAttribute(): string
    {
        return $this->avatar_url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
            . '&size=184&background=0e6d65&color=f4fffd&bold=true';
    }

    /** Banner URL (null = CSS gradient fallback). */
    public function getBannerAttribute(): ?string
    {
        return $this->banner_url;
    }

    /** Human-readable last-seen string. */
    public function getLastSeenAttribute(): string
    {
        if ($this->status === 'online') {
            return 'Online Now';
        }
        if (!$this->last_online_at) {
            return 'Last online a long time ago';
        }
        return 'Last online ' . $this->last_online_at->diffForHumans();
    }

    /** CSS modifier class for status dot. */
    public function getStatusClassAttribute(): string
    {
        if ($this->status === 'online') return 'status--online';
        if ($this->status === 'away')   return 'status--away';
        if ($this->status === 'busy')   return 'status--busy';
        return 'status--offline';
    }
}
