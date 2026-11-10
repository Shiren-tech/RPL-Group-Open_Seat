
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - OpenSeat</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

    <div class="login-container">

        <div class="login-left">
            <div class="login-brand">
                <h1>OpenSeat</h1>
                <span>Find your perfect seat.</span>
            </div>

            <div class="login-decoration">
                <div class="decoration-circle circle-one"></div>
                <div class="decoration-circle circle-two"></div>
                <div class="decoration-circle circle-three"></div>

                <div class="nature-content">
                    <span class="nature-label">FRESH NATURE THEME</span>
                    <h2>Your journey<br>starts with<br>OpenSeat.</h2>
                    <p>Create your account and discover your perfect place.</p>
                </div>
            </div>

            <p class="login-copyright">
                © 2026 OpenSeat. All rights reserved.
            </p>
        </div>

        <div class="login-right">
            <div class="login-form-wrapper">

                <div class="login-heading">
                    <span class="welcome-label">GET STARTED</span>
                    <h2>Create Account</h2>
                    <p>Please fill in your details to create an account.</p>
                </div>

                @if ($errors->any())
                    <div class="login-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter your full name"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Choose a username"
                            value="{{ old('username') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input
                            type="date"
                            id="dob"
                            name="dob"
                            value="{{ old('dob') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="hobby">Hobby</label>
                        <input
                            type="text"
                            id="hobby"
                            name="hobby"
                            placeholder="Enter your hobby"
                            value="{{ old('hobby') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Create a password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm your password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-button">
                        Create Account
                    </button>
                </form>

                <div class="login-divider">
                    <span>OR</span>
                </div>

                <p class="register-text">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign In</a>
                </p>

            </div>
        </div>
    </div>

</body>
</html>
