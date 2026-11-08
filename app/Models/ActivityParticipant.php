<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityParticipant extends Model
{
    use HasFactory;

    protected $table = 'activity_participants';
    protected $primaryKey = 'activity_participant_id';

    protected $fillable = [
        'user_id',
        'activity_id',
        'joined_at',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Activity
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }
}
