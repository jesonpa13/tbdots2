<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TB DOTS System Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('image/DOH2_LOGO.png') }}" type="image/x-icon">
    
    <!-- Add Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* General Reset and Body Styling */
        .remember-label {
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            color: #ffeb3b;
            white-space: nowrap;
        }

        .remember-label input {
            margin-right: 8px;
        }

        .remember-label span {
            display: inline-block;
            margin-top: 0;
            white-space: nowrap;
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 10%, rgba(173, 255, 47, 1) 40%, rgba(34, 139, 34, 1) 70%, rgba(255, 255, 224, 1) 100%);
            background-size: 400% 400%; /* Allows the gradient to shift smoothly */
            background-attachment: fixed;
            position: relative;
            animation: gradient 15s ease infinite; /* Adds animation to the gradient */
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('image/certificate_background.png') }}');
            background-position: center;
            background-size: cover;
            filter: blur(10px);
            z-index: 1;
        }

        .login-container {
            display: flex;
            width: 70%;
            max-width: 600px;
            height: 50%;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            z-index: 2;
            padding: 20px;
        }

        .login-form {
            width: 55%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, .8); /* fully opaque */
            color: white;
            backdrop-filter: blur(8px);
            z-index: 3;
        }

        .login-form h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #009933;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .form-group label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #009933;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: white;
            color: black;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #9BEC00;
            box-shadow: 0 0 5px rgba(255, 235, 59, 0.8);
        }

        .form-group .input-error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .submit-btn {
            background-color: #009933;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            width: 100%;
            color: white;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #f39c12;
            transform: scale(1.05);
        }

        .building-image {
            width: 45%;
            background-image: url('{{ asset('image/DOH2_LOGO.png') }}');
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.9;
            backdrop-filter: blur(5px);
            z-index: 2;
            margin-left: 25px;
        }

        .forgot-password {
            color: #009933;
            font-size: 1rem;
            text-decoration: none;
            margin-top: 15px;
            text-align: center;
        }

        .forgot-password:hover {
            color: #f39c12;
        }

        .wave {
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 10s linear infinite;
            opacity: 0.8;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        @keyframes wave {
            2% { transform: translateX(1); }
            25% { transform: translateX(-25%); }
            50% { transform: translateX(-50%); }
            75% { transform: translateX(-25%); }
            100% { transform: translateX(1); }
        }
        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 100% 100%;
            }
            100% {
                background-position: 0% 0%;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }
            .login-form, .building-image {
                width: 100%;
                height: auto;
                backdrop-filter: none;
            }
            .building-image {
                background-size: contain;
            }
        }

        .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -30px;
            position: relative;
            z-index: 2;
            cursor: pointer;
        }

        /* Style for Register Link */
        .register-link {
            display: block;
            text-align: left;
            margin-top: 15px;
            font-size: 1rem;
        }

        .register-link a {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="background-overlay"></div>

    <div class="login-container">
        <div class="login-form">
            <h1>Login to TB DOTS System</h1>

            <!-- Authentication Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    <div class="input-error">{{ $errors->first('email') }}</div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="col-md-4 control-label">Password</label>
                    <div class="col-md-6">
                        <input id="password-field" type="password" class="form-control" name="password">
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    <div class="input-error">{{ $errors->first('password') }}</div>
                </div>

                <!-- Remember Me -->
                <div class="form-group">
                    <label for="remember_me" class="remember-label">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- Forgot Password and Submit -->
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot your password?</a>
                
                <!-- Register Link -->
                <div class="register-link">
                    <p style="color:#009933">Don't have an account? <a href="{{ route('register') }}" class="forgot-password">Register here</a></p>
                </div>

                <button type="submit" class="submit-btn">Log in</button>
            </form>
        </div>

        <div class="building-image"></div>
    </div>

    <div class="wave"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $("#password-field");
                input.attr("type", input.attr("type") === "password" ? "text" : "password");
            });
        });
    </script>
</body>
</html>
