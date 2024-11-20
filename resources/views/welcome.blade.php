<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TB DOTS System Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('image/DOH2_LOGO.png') }}" type="image/x-icon">
    <style>
        /* Background and Basic Styles */
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            background: linear-gradient(315deg, rgba(255, 255, 255, 1) 3%, rgba(173, 255, 47, 1) 38%, rgba(34, 139, 34, 1) 68%, rgba(255, 255, 224, 1) 98%);
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: white;
            text-align: center;
            position: relative;
        }
        .nav-bar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .nav-bar a:hover {
            background: rgba(255, 255, 255, 0.3);
        }


        /* Background Overlay */
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('image/DOH1.png') }}') no-repeat center center;
            background-size: cover;
            opacity: 0.2;
            z-index: 1;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            text-align: center;
            position: relative;
            z-index: 2;
            padding: 0 20px;
        }

        /* Centered Content Container */
        .content {
            max-width: 800px;
            padding: 20px;
            text-shadow: 0px 4px 8px rgba(0, 0, 0, 0.7);
            animation: fadeIn 2s ease forwards;
        }

        /* Header Logos Styling */
        .header-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .header-logos img {
            height: 100px;
            max-width: 120px;
            filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.4));
            transition: transform 0.3s ease;
        }

        /* Hover Animation for Logos */
        .header-logos img:hover {
            transform: scale(1.1);
        }

        /* Page Title and Description */
        .title {
            font-size: 2.8rem;
            font-weight: 700;
            color: #f7fafc;
            margin: 20px 0 10px;
        }

        .subtitle {
            font-size: 3.5rem;
            font-weight: 700;
            color: #f7fafc;
            margin: 20px 0 10px;
        }

        /* Authentication Links Styling - Top Right Position */
        .auth-links {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 3;
        }

        .auth-links a {
            background-color: #4CAF50;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 25px;
            margin: 0 10px;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        /* Hover Animation for Links */
        .auth-links a:hover {
            color: #ffeb3b;
            transform: scale(1.1); /* Enlarge links on hover */
        }

        /* Additional Information Section */
        .description {
            font-size: .8rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.95);
            margin-top: 1px;
        }

        /* Wave Section */
        .wave {
            background: rgb(255 255 255 / 75%);
            border-radius: 1000% 1000% 0 0;
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 5s -9s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 1;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        .wave:nth-of-type(2) {
            bottom: -1.25em;
            animation: wave 18s linear reverse infinite;
            opacity: 0.8;
        }

        .wave:nth-of-type(3) {
            bottom: -2.5em;
            animation: wave 20s -1s reverse infinite;
            opacity: 0.9;
        }
        

        /* Keyframe Animation for Wave Motion */
        @keyframes wave {
            2% {
                transform: translateX(1);
            }

            25% {
                transform: translateX(-25%);
            }

            50% {
                transform: translateX(-50%);
            }

            75% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(1);
            }
        }
         /* Fade In Animation */
         @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Background Overlay Image -->
    <div class="background-overlay"></div>

    <div class="nav-bar">
        
        <div class="auth-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <!-- Centered Content -->
    <div class="container">
        <div class="content">
            <div class="header-logos">
                <img src="{{ asset('image/Bagong_logo.png') }}" alt="Bagong Pilipinas Logo">
                <img src="{{ asset('image/DOH2_Logo.png') }}" alt="DOH Logo">
            </div>
            <h1 class="title">TB DOTS System</h1>
            <p class="subtitle">Department of Health - Center for Health Development Soccsksargen Region</p>
            <div class="description">
                <p>Welcome to the TB DOTS System, supporting tuberculosis care and control. Our system is an initiative led by the Department of Health (DOH) - Center for Health Development Soccsksargen Region. We are dedicated to providing accessible and quality TB services, especially in challenging times such as the COVID-19 pandemic.</p>
                <p>In collaboration with regional healthcare teams, this system enables efficient tracking, support, and resources for individuals affected by TB. We are committed to ending TB in the Philippines by harmonizing research, resources, and care for the health and well-being of our communities.</p>
            </div>
        </div>
    </div>

    <!-- Wave Effect at Bottom -->
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>

    
</body>
</html>
