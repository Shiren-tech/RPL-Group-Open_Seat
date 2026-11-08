<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; // Important because of your migration!

    protected $fillable = [
        'name',
        'username',
        'pfp',
        'dob',
        'hobby',
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
        ];
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_participants', 'user_id', 'activity_id', 'user_id', 'activity_id')
                    ->withPivot('joined_at', 'status')
                    ->withTimestamps();
    }
}