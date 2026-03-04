<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kikundi Management System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            Kikundi
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('dashboard') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ url('/members') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('members*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Members
                        </a>
                        <a href="{{ url('/investments') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('investments*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Investments
                        </a>
                        <a href="{{ url('/loans') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('loans*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Loans
                        </a>
                        <a href="{{ url('/repayments') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('repayments*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Repayments
                        </a>
                        <a href="{{ url('/reports') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('reports*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Reports
                        </a>
                        <a href="{{ url('/settings') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('settings*') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} text-sm font-medium">
                            Settings
                        </a>
                    </div>
                </div>

                <!-- Logout -->
                <div class="hidden sm:flex items-center">
                    <span class="text-sm text-gray-500 mr-3">{{ Auth::user()->name }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500" id="mobile-menu-button">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ url('/dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('dashboard') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Dashboard
                </a>
                <a href="{{ url('/members') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('members*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Members
                </a>
                <a href="{{ url('/investments') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('investments*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Investments
                </a>
                <a href="{{ url('/loans') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('loans*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Loans
                </a>
                <a href="{{ url('/repayments') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('repayments*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Repayments
                </a>
                <a href="{{ url('/reports') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('reports*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Reports
                </a>
                <a href="{{ url('/settings') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('settings*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Settings
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
