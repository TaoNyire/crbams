```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | CRB Asset Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7fb;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* LEFT SIDE */

        .login-brand {
            width: 52%;
            background: linear-gradient(135deg, #0b1f3a 0%, #123d68 55%, #1769aa 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 50%;
            top: -180px;
            right: -150px;
        }

        .login-brand::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 50%;
            bottom: -180px;
            left: -100px;
        }

        .brand-content {
            max-width: 600px;
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .brand-content h1 {
            font-size: 42px;
            line-height: 1.15;
            margin: 0 0 20px;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .brand-content p {
            font-size: 17px;
            line-height: 1.7;
            color: rgba(255,255,255,0.78);
            margin: 0;
            max-width: 500px;
        }

        .system-features {
            display: flex;
            gap: 30px;
            margin-top: 45px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: rgba(255,255,255,0.85);
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* RIGHT SIDE */

        .login-panel {
            width: 48%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #ffffff;
        }

        .login-box {
            width: 100%;
            max-width: 430px;
        }

        .login-header {
            margin-bottom: 35px;
        }

        .login-header h2 {
            margin: 0 0 10px;
            font-size: 30px;
            color: #172033;
            font-weight: 700;
        }

        .login-header p {
            margin: 0;
            color: #718096;
            font-size: 15px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #344054;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            height: 50px;
            border: 1px solid #d9e0ea;
            border-radius: 10px;
            padding: 0 15px;
            font-size: 15px;
            color: #172033;
            background: #ffffff;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #1769aa;
            box-shadow: 0 0 0 4px rgba(23,105,170,0.1);
        }

        .password-input {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #667085;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .password-toggle:hover {
            color: #1769aa;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667085;
            font-size: 14px;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #1769aa;
        }

        .forgot-password {
            color: #1769aa;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            height: 51px;
            border: none;
            border-radius: 10px;
            background: #1769aa;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 5px 14px rgba(23,105,170,0.2);
        }

        .login-button:hover {
            background: #12588e;
            transform: translateY(-1px);
            box-shadow: 0 7px 18px rgba(23,105,170,0.25);
        }

        .login-footer {
            margin-top: 35px;
            padding-top: 22px;
            border-top: 1px solid #edf0f5;
            text-align: center;
            color: #98a2b3;
            font-size: 12px;
            line-height: 1.6;
        }

        .security-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            margin-top: 8px;
            color: #667085;
        }

        /* MOBILE */

        @media (max-width: 900px) {

            .login-brand {
                display: none;
            }

            .login-panel {
                width: 100%;
                min-height: 100vh;
                padding: 25px;
            }

            .login-box {
                max-width: 450px;
            }
        }

        @media (max-width: 480px) {

            .login-panel {
                padding: 20px;
            }

            .login-header h2 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- BRANDING PANEL -->

    <div class="login-brand">

        <div class="brand-content">

            <div class="brand-logo">
                CRB
            </div>

            <h1>
                Asset Management
                System
            </h1>

            <p>
                Centralized management and monitoring of organizational
                assets, equipment and resources.
            </p>

            <div class="system-features">

                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <span>Asset Tracking</span>
                </div>

                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <span>Secure Access</span>
                </div>

                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <span>Reports</span>
                </div>

            </div>

        </div>

    </div>


    <!-- LOGIN PANEL -->

    <div class="login-panel">

        <div class="login-box">

            <div class="login-header">

                <h2>Welcome back</h2>

                <p>
                    Sign in to access the CRB Asset Management System.
                </p>

            </div>


            <!-- SESSION STATUS -->

            @if (session('status'))

                <div class="alert alert-error">
                    {{ session('status') }}
                </div>

            @endif


            <!-- VALIDATION ERRORS -->

            @if ($errors->any())

                <div class="alert alert-error">

                    {{ $errors->first() }}

                </div>

            @endif


            <form method="POST" action="{{ route('login') }}">

                @csrf


                <!-- EMAIL -->

                <div class="form-group">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email Address
                    </label>

                    <div class="input-wrapper">

                        <input
                            id="email"
                            class="form-input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email address"
                        >

                    </div>

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <div class="input-wrapper">

                        <input
                            id="password"
                            class="form-input password-input"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                            id="passwordToggle"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <!-- OPTIONS -->

                <div class="form-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>Remember me</span>

                    </label>


                    @if (Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="forgot-password"
                        >
                            Forgot password?
                        </a>

                    @endif

                </div>


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    class="login-button"
                >
                    Sign In
                </button>

            </form>


            <!-- FOOTER -->

            <div class="login-footer">

                <div>
                    CRB Asset Management System
                </div>

                <div class="security-note">
                    <span>🔒</span>
                    <span>Authorized personnel only</span>
                </div>

            </div>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password = document.getElementById('password');
    const toggle = document.getElementById('passwordToggle');

    if (password.type === 'password') {

        password.type = 'text';
        toggle.textContent = 'Hide';

    } else {

        password.type = 'password';
        toggle.textContent = 'Show';

    }

}

</script>

</body>
</html>
```
