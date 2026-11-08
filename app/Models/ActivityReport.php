<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityReport extends Model
{
    use HasFactory;

    protected $table = 'activity_reports';
    protected $primaryKey = 'activity_reports_id';

    protected $fillable = [
        'user_id',
        'activity_id',
        'reason',
        'description',
        'status',
    ];

    // Relasi: Laporan dibuat oleh User siapa
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi: Laporan ini menargetkan Activity yang mana
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }
}