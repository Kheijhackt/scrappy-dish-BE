<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPreference extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'available_ingredients',
        'dietary_preferences',
        'cuisine_preferences',
        'dish_preferences',
        'available_equipments',
    ];

    // Auto-cast JSON columns to arrays
    protected $casts = [
        'available_ingredients' => 'array',
        'dietary_preferences'   => 'array',
        'cuisine_preferences'   => 'array',
        'dish_preferences'      => 'array',
        'available_equipments'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}