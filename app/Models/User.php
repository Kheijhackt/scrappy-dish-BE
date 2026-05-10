<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'google_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->preferences()->create([
                'user_id' => $user->id,
                'available_ingredients' => ['salt', 'pepper', 'water', 'oil', 'eggs', 'rice'],
                'dietary_preferences' => [],
                'cuisine_preferences' => [],
                'dish_preferences' => [],
                'available_equipments' => ['knife', 'bowl', 'pan', 'spatula'],
            ]);
        });

        static::deleting(function (User $user) {
            $user->tokens()->delete();
        });
    }
}
