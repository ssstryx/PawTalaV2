<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pawtala') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #FAEBCF;
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background-color: #4E342E;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        /* COLLAPSED STATE (Matches your reference) */
        .sidebar.collapsed {
            width: 70px;
            overflow: hidden;
        }

        /* Sidebar Logo Area */
        .sidebar-brand {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between; /* Pushes button to the right */
            padding: 0 15px 0 24px;
            color: #fff;
            font-family: 'Fredoka One', cursive;
            font-size: 1.5rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Hide Text/Logo when Collapsed */
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .sidebar-brand .fa-paw {
            display: none;
        }

        .sidebar.collapsed .sidebar-brand {
            padding: 0;
            justify-content: center;
        }

        /* Hamburger Button Styling */
        #toggleSidebar {
            background: transparent;
            border: none;
            padding: 0;
        }
        #toggleSidebar i {
            color: #fff;
        }

        /* NAV LINKS */
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap; /* Prevents text wrapping */
        }

        /* Link Hover */
        .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }

        /* Active Link */
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: #fff;
            border-left: 4px solid #fff;
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            min-width: 20px; /* Ensures icon stays centered when collapsed */
        }

        /* Collapsed Link Styles */
        .sidebar.collapsed .nav-link {
            padding: 12px 0;
            justify-content: center;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        
        /* Collapsed Content State */
        .main-content.collapsed {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* TOP NAVBAR */
        .top-navbar {
            height: 64px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Align profile to right */
            padding: 0 30px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; }
            .sidebar.collapsed { margin-left: 0; width: 260px; } /* Mobile open state */
            .main-content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center">
                <span>PawTala</span>
                <i class="fa-solid fa-paw ms-2 text-white"></i>
            </div>
            {{-- HAMBURGER MENU BUTTON (Inside Sidebar) --}}
            <button class="btn btn-sm" id="toggleSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <div class="d-flex flex-column py-4">
            <a href="{{ route('user.home') }}" class="nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i> <span>Home</span>
            </a>
            <a href="{{ route('user.pets') }}" class="nav-link {{ request()->routeIs('user.pets') ? 'active' : '' }}">
                <i class="bi bi-heart-fill"></i> <span>Pets</span>
            </a>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <header class="top-navbar">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="fw-bold me-2">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <div class="p-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- JAVASCRIPT TOGGLE LOGIC --}}
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('collapsed');
        });
    </script>
</body>
</html>