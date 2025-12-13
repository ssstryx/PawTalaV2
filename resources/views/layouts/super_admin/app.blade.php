<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pawtala') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                background-color: #FAEBCF; /* Custom background color */
                font-family: 'Nunito', sans-serif; /* New global font */
                overflow-x: hidden;
            }
            /* Custom class for the new button color */
            .btn-custom-color {
                background-color: #E59500;
                border-color: #E59500;
                color: #fff; /* Ensure text is white for contrast */
            }
            .btn-custom-color:hover {
                background-color: #d18700; /* Slightly darker on hover */
                border-color: #d18700;
            }
    
            /* --- SIDEBAR --- */
            .sidebar {
                width: 260px;
                background-color: #4E342E; /* New dark brown color */
                min-height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                transition: all 0.3s;
                display: flex;
                flex-direction: column;
            }
            
            .sidebar.collapsed {
                width: 70px; /* Smaller width when collapsed */
                overflow: hidden; /* Hide text */
            }
    
            /* Sidebar Logo Area */
            .sidebar-brand {
                height: 64px;
                display: flex;
                align-items: center; /* Ensure vertical centering */
                padding-left: 24px;
                color: #fff; /* White text for contrast */
                font-family: 'Fredoka One', cursive; /* New font for the brand */
                font-size: 1.5rem; /* Increased font size */
                letter-spacing: 0.05em;
                /* text-transform: uppercase; Removed this line */
                border-bottom: 1px solid rgba(255,255,255,0.1); /* Lighter border */
                justify-content: space-between;
                padding-right: 15px;
            }
            
            .sidebar.collapsed .sidebar-brand {
                padding-left: 0;
                padding-right: 0; /* Remove padding */
                justify-content: center; /* Center content horizontally */
                align-items: center; /* Center content vertically */
            }
            
            .sidebar.collapsed .sidebar-brand span {
                display: none;
            }
            
            .sidebar.collapsed .sidebar-brand .btn {
                padding: 0; /* Remove button padding to let brand handle it */
            }
            
            .sidebar-brand .btn {
                background-color: transparent; /* Transparent background for the button */
                border: none;
            }
            .sidebar-brand .btn i { /* Targeting the icon within the button */
                color: #fff !important; /* White color for the icon */
            }
    
            /* Links */
            .nav-link {
                color: rgba(255,255,255,0.8); /* Lighter text for contrast */
                padding: 12px 24px;
                font-weight: 600; /* Bolder font */
                display: flex;
                align-items: center;
                border-left: 4px solid transparent; /* Marker line */
                transition: all 0.2s;
            }
            
            .sidebar.collapsed .nav-link {
                padding: 12px 0; /* Adjust padding for icon only */
                justify-content: center; /* Center icon */
            }
    
            .nav-link:hover {
                color: #fff;
                background-color: rgba(255,255,255,0.1); /* Lighter shade for hover */
            }
    
            /* Active State (The Blue Box) */
            .nav-link.active {
                background-color: rgba(255,255,255,0.2); /* Even lighter shade for active */
                color: #fff;
                border-radius: 0; /* Remove border-radius */
                margin-right: 0;
                border-left: 4px solid #fff; /* White border for active */
            }
            
            .sidebar.collapsed .nav-link.active {
                margin-right: 0;
                border-radius: 0;
            }
            
            .nav-link i {
                margin-right: 12px;
                font-size: 1.1rem;
                color: #fff; /* White color for icons */
            }
            
            .sidebar.collapsed .nav-link i {
                margin-right: 0; /* Remove margin when collapsed */
            }
            
            .sidebar.collapsed .nav-link span {
                display: none; /* Hide text */
            }
    
            /* --- MAIN CONTENT WRAPPER --- */
            .main-content {
                margin-left: 260px; /* Pushes content right so it doesn't overlap */
                width: calc(100% - 260px);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                transition: all 0.3s;
            }        
        .main-content.collapsed {
            margin-left: 70px; /* Adjust margin for collapsed sidebar */
            width: calc(100% - 70px); /* Adjust width for collapsed sidebar */
        }

        /* Header */
        .top-navbar {
            height: 64px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Changed to flex-end as button is removed */
            padding: 0 30px;
        }
    </style>
</head>
<body>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div>
                <span>PawTala</span>
                <i class="bi bi-paw-fill ms-2"></i>
            </div>
            <button class="btn btn-dark btn-sm" id="toggleSidebar"><i class="bi bi-list fs-4 text-white"></i></button>
        </div>
        
        <div class="d-flex flex-column py-4">
            <a href="{{ url('/super-admin/activity-logs') }}" class="nav-link {{ request()->is('super-admin/activity-logs') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> <span>Activity Logs</span>
            </a>
            
            <a href="{{ route('super.users') }}" class="nav-link {{ request()->routeIs('super.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>User Management</span>
            </a>
            
            <a href="#" class="nav-link">
                <i class="bi bi-bar-chart-line"></i> <span>Reports</span>
            </a>
        </div>
    </nav>

    <div class="main-content">
        
        <header class="top-navbar">
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="fw-bold me-2">{{ Auth::user()->name }}</span>
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
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('collapsed');
        });
    </script>
</body>
</html>