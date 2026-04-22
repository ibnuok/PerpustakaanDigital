<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Laptop')</title>
    <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
    @php($viteAssets = ['resources/css/app.css'])
    @include('partials.vite')
</head>

<body class="bg-gradient-to-br from-slate-900 via-gray-900 to-black min-h-screen text-gray-100">

<div class="min-h-screen flex flex-col">

    <!-- Top Navigation -->
    <nav class="bg-slate-900 shadow-md px-6 py-4 border-b border-slate-700 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="{{ asset('logo-smkn.svg') }}" alt="SMKN 1 Ciomas" class="h-10 w-10">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-indigo-300">
                    @yield('title', 'Sistem Peminjaman Laptop')
                </h1>
                <p class="text-xs text-indigo-200">Sistem Peminjaman Laptop</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-slate-800 rounded-lg border border-slate-700">
                <span>👤</span>
                <span class="text-gray-200">{{ auth()->user()->name }}</span>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 px-4 py-2 rounded-lg font-semibold transition text-white">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1 py-8 px-6 md:px-8">

        @if(session('success'))
            <div id="alert-success" class="mb-4 p-4 bg-green-900/40 text-green-200 border border-green-600 rounded-lg flex justify-between items-center">
                <span>✓ {{ session('success') }}</span>
                <button type="button"
                    onclick="document.getElementById('alert-success').style.display='none'"
                    class="text-green-300 hover:text-green-100 font-bold text-lg">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div id="alert-error" class="mb-4 p-4 bg-red-900/40 text-red-200 border border-red-600 rounded-lg flex justify-between items-start">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button"
                    onclick="document.getElementById('alert-error').style.display='none'"
                    class="text-red-300 hover:text-red-100 font-bold text-lg ml-4 flex-shrink-0">✕</button>
            </div>
        @endif

        @yield('content')

    </main>

</div>

</body>
</html>
