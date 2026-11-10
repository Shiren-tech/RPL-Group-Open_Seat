<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserQuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ==========================
    // HOMEPAGE
    // ==========================

    public function index()
    {
        return view('welcome');
    }


    // ==========================
    // SHOW PROFILE
    // ==========================

    public function showProfile()
    {
        $user = auth()->user();

        $joinedActivities = $user->quests()->count();

        $completedQuests = $user->quests()
            ->where('status', 'completed')
            ->count();

        return view('profile.index', compact(
            'user',
            'joinedActivities',
            'completedQuests'
        ));
    }

    // ==========================
    // SHOW LOGIN
    // ==========================

    public function showLogin()
    {
        return view('auth.login');
    }


    // ==========================
    // PROCESS LOGIN
    // ==========================

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (auth()->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password yang kamu masukkan salah.'
            ]);
    }


    // ==========================
    // SHOW REGISTER
    // ==========================

    public function showRegister()
    {
        return view('auth.register');
    }


    // ==========================
    // PROCESS REGISTER
    // ==========================

    
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users,username'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'dob' => ['required', 'date'],
        'hobby' => ['required', 'string', 'max:255'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    User::create([
        'name' => $validated['name'],
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'pfp' => '',
        'dob' => $validated['dob'],
        'hobby' => $validated['hobby'],
        'total_xp' => 0,
        'orb_level' => 1,
        'role' => 'customer',
    ]);

    return redirect()->route('login')
        ->with('success', 'Account created successfully. Please sign in.');
}


    // ==========================
    // UPDATE PROFILE
    // ==========================

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],

            'interests' => ['nullable', 'array'],

            'interests.*' => [
                'string',
                Rule::in([
                    'Hiking',
                    'Camping',
                    'Travelling',
                    'Photography',
                    'Cooking',
                    'Sports',
                    'Music',
                    'Gaming',
                ]),
            ],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'interests' => $validated['interests'] ?? [],
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }


    // ==========================
    // CHANGE PASSWORD
    // ==========================

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }


    // ==========================
    // UPDATE PROFILE PICTURE
    // ==========================

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'pfp' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = auth()->user();

        $oldPicture = $user->pfp;

        $newPicture = $request->file('pfp')->store('profile-pictures', 'public');

        $user->update([
            'pfp' => $newPicture,
        ]);

        if ($oldPicture && Storage::disk('public')->exists($oldPicture)) {
            Storage::disk('public')->delete($oldPicture);
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }


    // ==========================
    // PROCESS LOGOUT
    // ==========================

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }


    // ==========================
    // APPLY QUEST
    // ==========================

    public function applyQuest(Request $request)
    {
        $validated = $request->validate([
            'quest_id' => ['required', 'integer', 'in:1,2,3,4,5,6'],
        ]);

        $user = auth()->user();

        $quest = UserQuest::firstOrCreate(
            [
                'user_id' => $user->user_id,
                'quest_id' => $validated['quest_id'],
            ],
            [
                'status' => 'applied',
            ]
        );

        if ($quest->status === 'completed') {
            return response()->json([
                'message' => 'Quest already completed.',
            ], 409);
        }

        return response()->json([
            'message' => 'Quest applied successfully.',
        ]);
    }


    // ==========================
    // COMPLETE QUEST
    // ==========================

    public function completeQuest(Request $request)
    {
        $validated = $request->validate([
            'quest_id' => ['required', 'integer', 'in:1,2,3,4,5,6'],
        ]);

        $rewards = [
            1 => 100,
            2 => 150,
            3 => 120,
            4 => 80,
            5 => 100,
            6 => 150,
        ];

        $questId = $validated['quest_id'];
        $reward = $rewards[$questId];

        return DB::transaction(function () use ($questId, $reward) {

            $authenticatedUser = auth()->user();

            $user = User::where('user_id', $authenticatedUser->user_id)
                ->lockForUpdate()
                ->firstOrFail();

            $quest = UserQuest::where('user_id', $user->user_id)
                ->where('quest_id', $questId)
                ->lockForUpdate()
                ->first();

            if (!$quest) {
                return response()->json([
                    'message' => 'You must apply for this quest first.',
                ], 403);
            }

            if ($quest->status === 'completed') {
                return response()->json([
                    'message' => 'Quest already completed.',
                    'total_xp' => $user->total_xp,
                    'orb_level' => $user->orb_level,
                ], 409);
            }

            $quest->update([
                'status' => 'completed',
            ]);

            $user->total_xp += $reward;

            $user->orb_level = (int) floor($user->total_xp / 500) + 1;

            $user->save();

            return response()->json([
                'message' => 'Quest completed successfully!',
                'reward' => $reward,
                'total_xp' => $user->total_xp,
                'orb_level' => $user->orb_level,
            ]);
        });
    }

    // ==========================
    // GET USER QUEST PROGRESS
    // ==========================

    public function questProgress()
    {
        $user = auth()->user();

        $userQuests = $user->quests()->get();

        return response()->json([
            'xp' => (int) $user->total_xp,

            'applied' => $userQuests
                ->pluck('quest_id')
                ->map(fn ($id) => (int) $id)
                ->values(),

            'completed' => $userQuests
                ->where('status', 'completed')
                ->pluck('quest_id')
                ->map(fn ($id) => (int) $id)
                ->values(),

            'orb_level' => (int) $user->orb_level,
        ]);
}
}