<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    // 1. Nampilin daftar semua aktivitas
    public function index()
    {
        $activities = Activity::with('user')->latest()->get();
        return view('activities.index', compact('activities'));
    }

    // 2. Nampilin form buat bikin aktivitas baru
    public function create()
    {
        return view('activities.create');
    }

    // 3. Nyimpen data aktivitas baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'benefits' => 'required|string',
            'contact_person' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        // Handle file attachment kalau ada yang di-upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Masukin user_id dari yang lagi login
        $validated['user_id'] = Auth::id();

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    // 4. Nampilin detail satu aktivitas
    public function show($id)
    {
        $activity = Activity::with('user')->findOrFail($id);
        return view('activities.show', compact('activity'));
    }
}