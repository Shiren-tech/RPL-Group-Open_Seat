<?php

namespace App\Http\Controllers;

use App\Models\ActivityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityReportController extends Controller
{
    // Menyimpan laporan baru yang dikirim user
    public function store(Request $request, $activity_id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        ActivityReport::create([
            'user_id' => Auth::id(),
            'activity_id' => $activity_id,
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'pending', // Default status laporan baru
        ]);

        return back()->with('success', 'Laporan berhasil dikirim. Tim kami akan segera meninjaunya.');
    }
}
