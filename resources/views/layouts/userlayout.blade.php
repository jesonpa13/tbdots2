<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap and Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use the full version -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    /* Background and text color */
    body {
        background: #f7f7f7; /* Light gray for the overall background */
        color: #333333; /* Dark gray for general text */
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Header styling */
    .header {
        background: #4caf50; /* Primary green for the header */
        color: #ffffff; /* White text for contrast */
        padding: 1.5rem 2rem;
        width: 100%;
        position: fixed;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10;
    }

    /* Sidebar styling */
    .sidebar {
        background: #333333; /* Dark gray for sidebar */
        color: #ffffff; /* White text in sidebar */
        width: 280px;
        padding-top: 4rem;
        position: fixed;
        top: 0;
        bottom: 0;
    }
    .sidebar a {
        color: #ffffff; /* White text for links */
        transition: color 0.2s;
    }

    .sidebar a:hover {
        color: #4caf50; /* Green on hover for sidebar links */
    }

    /* Content container */
    .content-container {
        margin-left: 280px; /* Space for sidebar */
        padding-top: 4rem; /* Space for fixed header */
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Main content card */
    .content-card {
        background: #ffffff; /* White background for main content */
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
        flex: 1;
        padding: 2rem;
    }

    /* Footer styling */
    .footer {
        background-color: #4caf50; /* Green for the footer */
        color: #ffffff; /* White text in the footer */
        text-align: center;
        padding: 1rem;
        width: 100%;
        position: relative;
        bottom: 0;
    }
    #current-time {
    font-weight: bold;
    }
    .role-text {
            text-transform: uppercase; 
            font-weight: bold;
            font-size: 0.875rem;
            color: white;
            margin-top: -0.90rem;
            margin-right: 2.5rem;
            text-decoration: underline;
        }
        .button-assessment{
            margin-top: 50px;
        }
        .text-orange{
            color: orange;
        }
</style>

    <script>
        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                hour: 'numeric', minute: 'numeric', second: 'numeric',
                timeZone: 'Asia/Manila', timeZoneName: 'short'
            };
            const dateTimeString = now.toLocaleString('en-US', options);
            document.getElementById('current-time').textContent = `Philippine Standard Time: ${dateTimeString}`;
        }

        setInterval(updateTime, 1000);
        window.onload = updateTime;
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="flex items-center">
            <img src="/image/DOH2_logo.png" alt="DOH2 Logo" class="h-10 mr-2">
            <h1 class="text-2xl font-semibold">TB DOTS SYSTEM</h1>
        </div>
        <div class="flex items-center">
            <span class="date-time mr-4" id="current-time">Loading time...</span>
            <img src="/image/Bagong_logo.png" alt="Bagong Logo" class="h-10 mr-2">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center text-white">
                        {{ Auth::user()->name }}
                        <i class="fas fa-caret-down ml-2"></i>
                    </button>
                    <span class="role-text" style="margin-left:30px;">{{ Auth::user()->user_type }}</span>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')"
                                     class="hover:bg-green-600 hover:text-white focus:bg-green-600 focus:text-white active:bg-green-700 active:text-white">
                        {{ __('Profile') }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                         onclick="event.preventDefault(); this.closest('form').submit();"
                                         class="hover:bg-red-600 hover:text-white focus:bg-red-600 focus:text-white active:bg-red-700 active:text-white">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="p-4 text-center">
        
        </div>
        <nav class="space-y-2">
            <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 transition-colors">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('client.sendrequest') }}" class="flex items-center space-x-2 px-3 py-2 transition-colors">
                <i class="fas fa-file-alt"></i>
                <span>Send Request</span>
            </a>
            <a href="{{ route('application.status') }}" class="flex items-center space-x-2 px-3 py-2 transition-colors">
                <i class="fas fa-hospital"></i>
                <span>Application Status</span>
            </a>
            <a href="{{ route('client.assessment') }}" class="flex items-center space-x-2 px-3 py-2 transition-colors">
                <i class="fas fa-hospital"></i>
                <span>Self Assesment & Tools</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content Container -->
    <div class="content-container">
        <main class="content-card">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="footer">
            <p>&copy; {{ date('Y') }} TBDOTS SYSTEM. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
