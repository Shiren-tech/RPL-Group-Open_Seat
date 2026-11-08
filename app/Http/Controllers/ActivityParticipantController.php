<?php

namespace App\Http\Controllers;

use App\Models\ActivityParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityParticipantController extends Controller
{
    // Aksi ketika user klik tombol "Join / Ikuti"
    public function join(Request $request, $activity_id)
    {
        $userId = Auth::id();

        // Cek apakah user sudah pernah join sebelumnya
        $existing = ActivityParticipant::where('user_id', $userId)
                                       ->where('activity_id', $activity_id)
                                       ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah terdaftar di kegiatan ini!');
        }

        // Daftarkan user ke kegiatan
        ActivityParticipant::create([
            'user_id' => $userId,
            'activity_id' => $activity_id,
            'joined_at' => Carbon::now(),
            'status' => 'joined', // Bisa 'joined', 'pending', dll.
        ]);

        return back()->with('success', 'Berhasil bergabung ke kegiatan!');
    }

    // Aksi ketika user batal / keluar dari kegiatan
    public function leave($activity_id)
    {
        $userId = Auth::id();

        ActivityParticipant::where('user_id', $userId)
                             ->where('activity_id', $activity_id)
                             ->delete();

        return back()->with('success', 'Kamu telah keluar dari kegiatan ini.');
    }
}