<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','BCrypt')</title>

    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100">

    <!-- Header -->
    <header class="p-4 border-b border-slate-800">
        <div class="container flex items-center justify-between mx-auto">
            <a href="{{ route('home') }}" class="text-xl"><span class="text-2xl font-bold text-blue-500">B</span>Crypt</a>
            <nav class="flex items-center gap-4">
                <a href="{{ route('home') }}">Market</a>
                @auth
                <a href="{{ route('watchlist.index') }}">Watchlist</a>
                @endauth
                @guest
                <a href="{{ route('login') }}">Login</a>
                @endguest
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container p-6 mx-auto">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="p-6 text-center text-slate-400">
        <div class="flex justify-center w-full h-20 gap-5 mx-auto">
            <p>Data provided by</p>
            <a href="https://www.coingecko.com/" target="_blank"><image src="{{ asset('images/CG-Wordmark@2x-2.png') }}" alt="" class="h-10 w-50" /></a>
        </div>
        © {{ date('Y') }} BCrypt
    </footer>

    
    @yield('scripts')
    
</body>
</html>
