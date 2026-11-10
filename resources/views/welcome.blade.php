<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">   

    <title>OPEN SEAT - Adventure Together</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animation.css') }}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

    <!-- NAVBAR -->
    <header class="navbar">

        <div class="logo">
            <span class="logo-icon">O</span>
            <span>OPEN SEAT</span>
        </div>

        <nav>
            <a href="#" class="active">Explore</a>
            <a href="#quests">Quests</a>
            <a href="#progress">My Progress</a>
            <a href="#community">Community</a>
        </nav>

        <!-- AUTHENTICATION NAVIGATION -->
        @auth
            <div class="user-info">

                <div class="point-badge">
                    ✦ <span id="points">0</span> XP
                </div>

                <a href="{{ route('profile') }}" class="avatar" title="My Profile">
                    <svg    xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"></path>

                    </svg>
                </a>

            </div>
        @else
            <div class="auth-nav">

                <a href="{{ route('login') }}" class="nav-login">
                    Sign In
                </a>

                <a href="{{ route('register') }}" class="nav-signup">
                    Sign Up
                </a>

            </div>
        @endauth

    </header>


    <main class="container">

        <!-- HERO BANNER -->
        <section class="hero">

            <img
                id="heroImage"
                src="https://images.unsplash.com/photo-1472396961693-142e6e269027?w=1800"
                alt="Adventure Landscape"
            >

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="eyebrow">
                    YOUR NEXT ADVENTURE STARTS HERE
                </span>

                <h1>
                    FIND YOUR PEOPLE.<br>
                    FILL YOUR <span>MOMENTS.</span>
                </h1>

                <p>
                    Discover new adventures, meet new people,
                    and create unforgettable memories together.
                </p>

                <a href="#quests" class="primary-button">
                    Explore Activities →
                </a>

            </div>

            <div class="slider-dots" id="sliderDots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>

        </section>


        <!-- WELCOME -->
        <section class="welcome">

            <div>
                <span class="eyebrow">WELCOME TO OPEN SEAT</span>

                <h2>Ready for your next adventure?</h2>

                <p>
                    Find activities, meet people, and make memories together.
                </p>
            </div>

        </section>


        <!-- AVAILABLE QUESTS -->
        <section class="quests-section" id="quests">

            <div class="section-heading">

                <div>
                    <span class="eyebrow">DISCOVER SOMETHING NEW</span>
                    <h2>Available Quests</h2>
                </div>

                <div class="filters">

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search quests..."
                    >

                    <select id="categoryFilter">
                        <option value="All">All Categories</option>
                        <option value="Hiking">Hiking</option>
                        <option value="Camping">Camping</option>
                        <option value="Travelling">Travelling</option>
                        <option value="Picnic">Picnic</option>
                    </select>

                </div>

            </div>

            <!-- QUEST CARDS -->
            <div class="quest-grid" id="questGrid"></div>

        </section>


        <!-- MY PROGRESS -->
        <section class="progress-section" id="progress">

            <span class="eyebrow">YOUR JOURNEY</span>

            <h2>My Adventure Progress</h2>

            <div class="progress-grid">

                <div class="progress-card">
                    <span class="progress-icon">✦</span>
                    <h3 id="totalXP">0</h3>
                    <p>Total XP</p>
                </div>

                <div class="progress-card">
                    <span class="progress-icon">♧</span>
                    <h3 id="totalApplied">0</h3>
                    <p>Joined Activities</p>
                </div>

                <div class="progress-card">
                    <span class="progress-icon">🌱</span>
                    <h3 id="orbLevel">1</h3>
                    <p>Orb Level</p>
                </div>

            </div>

        </section>


        <!-- ORB -->
        <section class="orb-section">

            <div class="orb-info">

                <span class="eyebrow">YOUR COMPANION</span>

                <h2>Grow Your Orb</h2>

                <p>
                    Complete quests and collect XP to evolve your Orb.
                </p>

                <div class="xp-bar">
                    <div id="xpProgress"></div>
                </div>

                <small id="xpText">0 / 500 XP</small>

            </div>

            <div class="orb-image">

                <div>🌱</div>

                <h3 id="orbName">Seed Orb</h3>

                <p>
                    Level <span id="orbLevelText">1</span>
                </p>

            </div>

        </section>


        <!-- FOOTER -->
        <footer id="community">

            <h2>OPEN SEAT</h2>

            <p>Find your people. Fill your moments.</p>

            <small>© 2026 Open Seat</small>

        </footer>

    </main>


    <!-- QUEST DETAIL MODAL -->
    <div class="modal" id="questModal">

        <div class="modal-content">

            <button class="close-button" id="closeModal">×</button>

            <img id="modalImage" src="" alt="Quest">

            <div class="modal-body">

                <span id="modalCategory" class="eyebrow"></span>

                <h2 id="modalTitle"></h2>

                <p id="modalDescription"></p>

                <div id="modalDetails"></div>

                <button id="applyButton" class="primary-button">
                    Apply Now
                </button>

                <button id="completeButton" class="complete-button" hidden>
                    Complete Quest (Demo)
                </button>

            </div>

        </div>

    </div>


    <!-- JAVASCRIPT -->
    <script src="{{ asset('js/quests.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>