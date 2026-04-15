<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'release_date',
        'is_featured',
        'created_by',
        'developer_id',
        'publisher_id',
        'cover_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
        'is_featured'  => 'boolean',
        'price'        => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * Game hasMany GameMedia.
     */
    public function media()
    {
        return $this->hasMany(GameMedia::class);
    }
}
