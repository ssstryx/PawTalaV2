<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Barangay Admin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-900 text-white transition-all duration-300">
            <div class="h-16 flex items-center justify-center border-b border-gray-700">
                <span class="text-xl font-bold">PAWTALA</span>
            </div>

            <nav class="mt-5 flex-1 px-2 space-y-1">
                <a href="{{ route('barangay.dashboard') }}" 
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-white bg-gray-900">
                Dashboard
                </a>

                <a href="{{ route('barangay.users.index') }}" 
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                User Management
                </a>

                <a href="{{ route('barangay.pets.index') }}" 
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                Pet Registration
                </a>

            </nav>
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    
                    <h2 class="ml-4 text-xl font-semibold text-gray-800">
                        {{ $header ?? 'Dashboard' }}
                    </h2>
                </div>

                <div class="flex items-center">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        
                        <button @click="dropdownOpen = !dropdownOpen" 
                                class="relative block flex items-center space-x-2 focus:outline-none">
                            <span class="text-gray-800 font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" 
                            @click="dropdownOpen = false" 
                            class="fixed inset-0 h-full w-full z-10" 
                            style="display: none;"></div>

                        <div x-show="dropdownOpen" 
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-20"
                            style="display: none;">
                            
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container px-6 py-8 mx-auto">
                {{ $slot ?? '' }} 
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>