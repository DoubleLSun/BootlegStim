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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'banner_url',
        'bio',
        'location',
        'steam_level',
        'status',
        'status_game_id',
        'last_online_at',
    ];

    /**
     * The attributes that should be appended to model arrays.
     * Only virtual/computed accessors belong here.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar',
        'banner',
        'last_seen',
        'status_class',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at'    => 'datetime',
    ];

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
    public function games()
    {
        return $this->belongsToMany(Game::class, 'user_games')
                    ->withPivot(['playtime_minutes', 'last_played_at'])
                    ->withTimestamps();
    }

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
