<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';
    protected $primaryKey = 'activity_id';

    protected $fillable = [
        'user_id',
        'title',
        'date',
        'time',
        'category',
        'description',
        'location',
        'benefits',
        'contact_person',
        'attachment',
    ];

    // Relasi: Aktivitas ini dibuat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'activity_participants', 'activity_id', 'user_id', 'activity_id', 'user_id')
                    ->withPivot('joined_at', 'status')
                    ->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(ActivityReport::class, 'activity_id', 'activity_id');
    }
}