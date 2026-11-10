<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile - Open Seat</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animation.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        [hidden] {
            display: none !important;
        }

        .profile-setting-item {
            cursor: pointer;
        }

        .profile-picture {
            overflow: hidden;
        }

        .profile-picture img,
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .edit-profile-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.65);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .edit-profile-content {
            position: relative;
            background: #fff;
            color: #222;
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 16px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .edit-profile-content h2 {
            margin-bottom: 8px;
        }

        .edit-profile-content > p {
            color: #777;
            margin-bottom: 18px;
        }

        .edit-profile-content label {
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
        }

        .edit-profile-content input:not([type="file"]),
        .edit-profile-content select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .edit-profile-content select[multiple] {
            min-height: 130px;
        }

        .modal-close {
            position: absolute;
            top: 12px;
            right: 16px;
            border: none;
            background: none;
            font-size: 26px;
            cursor: pointer;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .modal-actions button {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .modal-actions button[type="submit"] {
            background: #dcebd1;
            color: #173c26;
            font-weight: 700;
        }

        .picture-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 15px auto;
            background: #e8f0df;
        }

        .picture-upload {
            display: block;
            width: 100%;
            padding: 12px;
            border: 1px dashed #aaa;
            border-radius: 8px;
            margin-top: 15px;
            cursor: pointer;
            text-align: center;
        }

        .picture-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .picture-actions button {
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .password-message {
            margin-top: 15px;
            font-size: 13px;
        }

        .setting-icon {
            flex-shrink: 0;
        }

        .profile-alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .profile-alert.success {
            background: #e5f5e8;
            color: #216b32;
        }

        .profile-alert.error {
            background: #fde8e8;
            color: #a12626;
        }

        .form-error {
            color: #c62828;
            font-size: 13px;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .edit-profile-content {
                padding: 25px 20px;
            }
        }
    </style>
</head>


<body class="profile-page">

    <!-- NAVBAR -->
    <header class="navbar">

        <div class="logo">
            <span class="logo-icon">O</span>
            <span>OPEN SEAT</span>
        </div>

        <nav>
            <a href="{{ url('/') }}">Explore</a>
            <a href="{{ url('/#quests') }}">Quests</a>
            <a href="{{ url('/#progress') }}">My Progress</a>
            <a href="{{ url('/#community') }}">Community</a>
        </nav>

        <div class="user-info">
            <a href="{{ route('profile') }}" class="profile-avatar">
                @if ($user->pfp)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($user->pfp) }}"
                         alt="Profile Picture">
                @else
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>
                    </svg>
                @endif
            </a>
        </div>

    </header>


    <main class="profile-container">

        <!-- PAGE HEADING -->
        <section class="profile-heading">
            <span class="eyebrow">YOUR PERSONAL SPACE</span>
            <h1>My Profile</h1>
            <p>Manage your account and explore your adventure journey.</p>
        </section>


        <!-- SUCCESS AND ERROR MESSAGES -->
        @if (session('success'))
            <div class="profile-alert success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="profile-alert error">
                <strong>Please check the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- PROFILE CARD -->
        <section class="profile-card">

            <div class="profile-main">

                <div class="profile-picture" id="profilePictureDisplay">

                    @if ($user->pfp)
                        <img
                            id="profilePictureImage"
                            src="{{ \Illuminate\Support\Facades\Storage::url($user->pfp) }}"
                            alt="Profile Picture">
                    @else
                        <svg id="defaultProfileIcon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>
                        </svg>

                        <img id="profilePictureImage" src=""
                            alt="Profile Picture" hidden>
                    @endif

                </div>

                <div class="profile-details">

                    <span class="eyebrow">ADVENTURE MEMBER</span>

                    <h2 id="profileName">{{ $user->name }}</h2>

                    <p id="profileEmail">{{ $user->email }}</p>

                    <small>
                        Member since
                        {{ $user->created_at?->format('F Y') ?? '—' }}
                    </small>

                </div>

            </div>

            <button class="profile-edit-button"
                type="button"
                onclick="openEditProfile()">
                Edit Profile
            </button>

        </section>


        <!-- STATISTICS -->
        <section class="profile-stats">

            <!-- TOTAL XP -->
            <div class="profile-stat-card">
                <span class="profile-stat-icon">✦</span>
                <h3>{{ $user->total_xp ?? 0 }}</h3>
                <p>Total XP</p>
            </div>

            <!-- JOINED ACTIVITIES -->
            <div class="profile-stat-card">
                <span class="profile-stat-icon">♧</span>
                <h3>{{ $joinedActivities }}</h3>
                <p>Joined Activities</p>
            </div>

            <!-- COMPLETED QUESTS -->
            <div class="profile-stat-card">
                <span class="profile-stat-icon">✓</span>
                <h3>{{ $completedQuests }}</h3>
                <p>Completed Quests</p>
            </div>

            <!-- ORB LEVEL -->
            <div class="profile-stat-card">
                <span class="profile-stat-icon">🌱</span>
                <h3>Level {{ $user->orb_level ?? 1 }}</h3>
                <p>Orb Level</p>
            </div>

        </section>

        <!-- ADVENTURE PROGRESS -->
        <section class="profile-orb-card">

            <div class="profile-orb-info">

                <span class="eyebrow">YOUR COMPANION</span>

                <h2>Your Adventure Progress</h2>

                <p>
                    Keep exploring and completing quests
                    to help your Orb grow.
                </p>

                @php
                    $totalXP = $user->total_xp ?? 0;
                    $currentXP = $totalXP % 500;
                    $progressPercentage = ($currentXP / 500) * 100;
                @endphp

                <div class="profile-xp-bar">
                    <div style="width: {{ $progressPercentage }}%;"></div>
                </div>

                <small>{{ $currentXP }} / 500 XP to next level</small>

            </div>

            <div class="profile-orb-display">

                <div class="profile-orb-image">🌿</div>

                @php
                    $orbLevel = $user->orb_level ?? 1;

                    if ($orbLevel >= 10) {
                        $orbName = 'Radiant Orb';
                    } elseif ($orbLevel >= 5) {
                        $orbName = 'Bloom Orb';
                    } else {
                        $orbName = 'Seed Orb';
                    }
                @endphp

                <h3>{{ $orbName }}</h3>
                <p>Level {{ $orbLevel }}</p>

            </div>

        </section>


        <!-- MY ACTIVITIES -->
        <section class="profile-activities">

            <div class="profile-section-heading">

                <div>
                    <span class="eyebrow">YOUR JOURNEY</span>
                    <h2>My Activities</h2>
                </div>

                <a href="{{ url('/') }}#quests">Explore More →</a>

            </div>


            <div class="profile-activity-grid">

                <div class="profile-activity-card">

                    <div class="activity-image activity-hiking">
                        <span>🥾</span>
                    </div>

                    <div class="activity-content">
                        <span class="activity-category">HIKING</span>
                        <h3>Mountain Hiking Adventure</h3>
                        <p>📍 Mount Bromo</p>

                        <div class="activity-bottom">
                            <span class="activity-status status-completed">
                                Completed
                            </span>
                            <span>+100 XP</span>
                        </div>
                    </div>

                </div>


                <div class="profile-activity-card">

                    <div class="activity-image activity-camping">
                        <span>🏕️</span>
                    </div>

                    <div class="activity-content">
                        <span class="activity-category">CAMPING</span>
                        <h3>Forest Camping Weekend</h3>
                        <p>📍 Puncak, Bogor</p>

                        <div class="activity-bottom">
                            <span class="activity-status status-joined">
                                Joined
                            </span>
                            <span>+150 XP</span>
                        </div>
                    </div>

                </div>


                <div class="profile-activity-card">

                    <div class="activity-image activity-travel">
                        <span>⛰️</span>
                    </div>

                    <div class="activity-content">
                        <span class="activity-category">TRAVELLING</span>
                        <h3>Explore Hidden Places</h3>
                        <p>📍 Bandung</p>

                        <div class="activity-bottom">
                            <span class="activity-status status-joined">
                                Joined
                            </span>
                            <span>+120 XP</span>
                        </div>
                    </div>

                </div>

            </div>

        </section>


        <!-- ACCOUNT SETTINGS -->
        <section class="profile-settings">

            <span class="eyebrow">ACCOUNT</span>
            <h2>Account Settings</h2>

            <div class="profile-setting-list">

                <div class="profile-setting-item"
                    role="button"
                    tabindex="0"
                    onclick="openEditProfile()">

                    <div>
                        <span class="setting-icon">👤</span>
                        <div>
                            <h3>Personal Information</h3>
                            <p>Manage your name, email, and interests.</p>
                        </div>
                    </div>

                    <span>→</span>

                </div>


                <div class="profile-setting-item"
                    role="button"
                    tabindex="0"
                    onclick="openChangePassword()">

                    <div>
                        <span class="setting-icon">🔒</span>
                        <div>
                            <h3>Change Password</h3>
                            <p>Update your account password.</p>
                        </div>
                    </div>

                    <span>→</span>

                </div>


                <div class="profile-setting-item"
                    role="button"
                    tabindex="0"
                    onclick="openProfilePicture()">

                    <div>
                        <span class="setting-icon">📷</span>
                        <div>
                            <h3>Profile Picture</h3>
                            <p>Upload or change your profile photo.</p>
                        </div>
                    </div>

                    <span>→</span>

                </div>

            </div>

        </section>


        <!-- LOGOUT -->
        <section class="profile-logout">

            <div>
                <h3>Want to leave?</h3>
                <p>You can safely log out of your account.</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="profile-logout-button">
                    Log Out
                </button>
            </form>

        </section>


        <!-- FOOTER -->
        <footer class="profile-footer">
            <h2>OPEN SEAT</h2>
            <p>Find your people. Fill your moments.</p>
            <small>© 2026 Open Seat</small>
        </footer>

    </main>


    <!-- EDIT PROFILE MODAL -->
    <div id="editProfileModal" class="edit-profile-modal" hidden>

        <div class="edit-profile-content">

            <button type="button"
                onclick="closeEditProfile()"
                class="modal-close">
                &times;
            </button>

            <h2>Edit Profile</h2>
            <p>Update your personal information.</p>

            <form id="editProfileForm"
                action="{{ route('profile.update') }}"
                method="POST">

                @csrf
                @method('PUT')

                <label for="editName">Full Name</label>

                <input
                    type="text"
                    id="editName"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    maxlength="255"
                    required
                >

                <label for="editEmail">Email Address</label>

                <input
                    type="email"
                    id="editEmail"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                >

                <label for="editInterests">Your Interests</label>

                <select id="editInterests" name="interests[]" multiple>
                    @foreach ([
                        'Hiking',
                        'Camping',
                        'Travelling',
                        'Photography',
                        'Cooking',
                        'Sports',
                        'Music',
                        'Gaming'
                    ] as $interest)

                        <option value="{{ $interest }}"
                            @selected(in_array(
                                $interest,
                                old('interests', $user->interests ?? [])
                            ))>
                            {{ $interest }}
                        </option>

                    @endforeach
                </select>

                <small>
                    Hold Ctrl (Windows) to select multiple interests.
                </small>

                <div class="modal-actions">

                    <button type="button" onclick="closeEditProfile()">
                        Cancel
                    </button>

                    <button type="submit">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- CHANGE PASSWORD MODAL -->
    <div id="changePasswordModal" class="edit-profile-modal" hidden>

        <div class="edit-profile-content">

            <button type="button"
                onclick="closeChangePassword()"
                class="modal-close">
                &times;
            </button>

            <h2>Change Password</h2>
            <p>Update your account password.</p>

            <form id="changePasswordForm"
                action="{{ route('profile.password') }}"
                method="POST">

                @csrf
                @method('PUT')

                <label for="currentPassword">Current Password</label>

                <input
                    type="password"
                    id="currentPassword"
                    name="current_password"
                    placeholder="Enter current password"
                    autocomplete="current-password"
                    required
                >

                <label for="newPassword">New Password</label>

                <input
                    type="password"
                    id="newPassword"
                    name="new_password"
                    placeholder="Enter new password"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <label for="confirmPassword">Confirm New Password</label>

                <input
                    type="password"
                    id="confirmPassword"
                    name="new_password_confirmation"
                    placeholder="Confirm new password"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <div class="modal-actions">

                    <button type="button" onclick="closeChangePassword()">
                        Cancel
                    </button>

                    <button type="submit">
                        Update Password
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- PROFILE PICTURE MODAL -->
    <div id="profilePictureModal" class="edit-profile-modal" hidden>

        <div class="edit-profile-content">

            <button type="button"
                onclick="closeProfilePicture()"
                class="modal-close">
                &times;
            </button>

            <h2>Profile Picture</h2>
            <p>Choose a photo for your profile.</p>

            <form id="profilePictureForm"
                action="{{ route('profile.picture') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                @if ($user->pfp)
                    <img
                        id="picturePreview"
                        class="picture-preview"
                        src="{{ \Illuminate\Support\Facades\Storage::url($user->pfp) }}"
                        alt="Picture Preview">
                @else
                    <img
                        id="picturePreview"
                        class="picture-preview"
                        src=""
                        alt="Picture Preview"
                        hidden>
                @endif

                <label for="pictureInput" class="picture-upload">
                    Choose Image
                </label>

                <input
                    type="file"
                    id="pictureInput"
                    name="pfp"
                    accept="image/png,image/jpeg,image/webp"
                    hidden
                    required
                >

                <p id="pictureMessage" class="password-message">
                    JPG, PNG, or WEBP. Maximum 2 MB.
                </p>

                <div class="picture-actions">

                    <button type="button" onclick="closeProfilePicture()">
                        Cancel
                    </button>

                    <button type="submit">
                        Save Picture
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- JAVASCRIPT -->
    <script>

        // ==========================
        // EDIT PROFILE
        // ==========================

        function openEditProfile() {
            document.getElementById('editProfileModal').hidden = false;
        }

        function closeEditProfile() {
            document.getElementById('editProfileModal').hidden = true;
        }


        // ==========================
        // CHANGE PASSWORD
        // ==========================

        function openChangePassword() {
            document.getElementById('changePasswordForm').reset();
            document.getElementById('changePasswordModal').hidden = false;
        }

        function closeChangePassword() {
            document.getElementById('changePasswordModal').hidden = true;
        }


        // ==========================
        // PROFILE PICTURE
        // ==========================

        function openProfilePicture() {
            document.getElementById('profilePictureModal').hidden = false;
        }

        function closeProfilePicture() {
            document.getElementById('profilePictureModal').hidden = true;
        }


        // ==========================
        // IMAGE PREVIEW
        // ==========================

        document.getElementById('pictureInput').addEventListener('change', function(event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            const message = document.getElementById('pictureMessage');

            if (!allowedTypes.includes(file.type)) {
                message.textContent = 'Please select a JPG, PNG, or WEBP image.';
                message.style.color = 'red';
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                message.textContent = 'Image size must not exceed 2 MB.';
                message.style.color = 'red';
                this.value = '';
                return;
            }

            const preview = document.getElementById('picturePreview');

            preview.src = URL.createObjectURL(file);
            preview.hidden = false;

            message.textContent = 'Image selected successfully.';
            message.style.color = 'green';

        });


        // ==========================
        // CLOSE MODALS BY CLICKING OUTSIDE
        // ==========================

        document.querySelectorAll('.edit-profile-modal').forEach(modal => {

            modal.addEventListener('click', function(event) {

                if (event.target === this) {
                    this.hidden = true;
                }

            });

        });


        // ==========================
        // CLOSE MODALS WITH ESCAPE
        // ==========================

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                document.querySelectorAll('.edit-profile-modal').forEach(modal => {
                    modal.hidden = true;
                });

            }

        });


        // ==========================
        // OPEN MODAL AGAIN IF VALIDATION FAILS
        // ==========================

        @if ($errors->has('name') || $errors->has('email') || $errors->has('interests') || $errors->has('interests.*'))
            document.getElementById('editProfileModal').hidden = false;
        @endif

        @if ($errors->has('current_password') || $errors->has('new_password'))
            document.getElementById('changePasswordModal').hidden = false;
        @endif

        @if ($errors->has('pfp'))
            document.getElementById('profilePictureModal').hidden = false;
        @endif

    </script>

</body>
</html>