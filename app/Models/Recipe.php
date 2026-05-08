<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'ingredients_used',
        'steps',
        'cook_time_minutes',
        'difficulty',
        'servings',
        'cuisine_tags',
        'dish_tags',
        'general_tags',
        'nutrition_notes',
    ];

    protected function casts(): array
    {
        return [
            'ingredients_used' => 'array',
            'steps' => 'array',
            'cuisine_tags' => 'array',
            'dish_tags' => 'array',
            'general_tags' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
