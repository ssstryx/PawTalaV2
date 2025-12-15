<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <linkpreconnect href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-900 text-white transition-all duration-300 ease-in-out flex flex-col fixed h-full z-30">
            <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-gray-900">
                <span :class="sidebarOpen ? 'block' : 'hidden'" class="text-xl font-bold tracking-wider">PawTala</span>
                <span :class="sidebarOpen ? 'hidden' : 'block'" class="text-xl font-bold">PT</span>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                
                <a href="{{ route('super.activity_logs') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('super.activity_logs') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span :class="sidebarOpen ? 'block' : 'hidden'" class="ml-3 font-medium">Activity Logs</span>
                </a>

                <a href="{{ route('super.users') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('super.users') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span :class="sidebarOpen ? 'block' : 'hidden'" class="ml-3 font-medium">User Management</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span :class="sidebarOpen ? 'block' : 'hidden'" class="ml-3 font-medium">Reports</span>
                </a>

            </nav>
        </aside>

        <div :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="flex-1 flex flex-col transition-all duration-300 ease-in-out min-h-screen">
            
            <header class="bg-white shadow h-16 flex items-center justify-between px-6 z-10">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>

            <main class="flex-1 p-8 bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>