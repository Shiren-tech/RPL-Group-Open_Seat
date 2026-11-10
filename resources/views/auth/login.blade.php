<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - OpenSeat</title>
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
                    <h2>A better place<br>starts with<br>a better seat.</h2>
                    <p>Discover comfort and find the perfect place for you.</p>
                </div>
            </div>

            <p class="login-copyright">
                © 2026 OpenSeat. All rights reserved.
            </p>
        </div>

        <div class="login-right">

            <div class="login-form-wrapper">
                <div class="login-heading">
                    <span class="welcome-label">WELCOME BACK</span>
                    <h2>Sign In</h2>
                    <p>Please enter your details to access your account.</p>
                </div>

                @if ($errors->any())
                    <div class="login-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf

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
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="login-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>

                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="submit" class="login-button">
                        Sign In
                    </button>
                </form>

                <div class="login-divider">
                    <span>OR</span>
                </div>

                <p class="register-text">
                    Don't have an account?
                    <a href="{{ route('register') }}">Sign Up</a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>
