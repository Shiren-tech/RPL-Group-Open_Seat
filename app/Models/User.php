<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserQuest;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'username',
        'pfp',
        'dob',
        'hobby',
        'interests',
        'total_xp',
        'orb_level',
        'role',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'interests' => 'array',
        ];
    }

    public function activities()
    {
        return $this->belongsToMany(
            Activity::class,
            'activity_participants',
            'user_id',
            'activity_id',
            'user_id',
            'activity_id'
        )->withPivot('joined_at', 'status')
        ->withTimestamps();
    }

    public function quests(): HasMany
    {
        return $this->hasMany(UserQuest::class, 'user_id', 'user_id');
    }
}