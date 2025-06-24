<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Memuat CSS dari Vite --}}
    @vite(['resources/css/app.css'])

    {{-- Memuat Alpine.js secara langsung di <head> menggunakan defer --}}
    {{-- Atribut 'defer' memastikan skrip dieksekusi setelah dokumen HTML selesai di-parse, --}}
    {{-- namun sebelum event DOMContentLoaded, sehingga siap digunakan oleh skrip lain. --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        {{-- Memasukkan navigasi --}}
        @include('layouts.navigation')

        <!-- Header Halaman (Jika ada) -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Konten Halaman Utama -->
        <main>
            @yield('content')
        </main>
    </div>

    {{-- Memuat JS aplikasi dari Vite sebelum akhir body --}}
    @vite(['resources/js/app.js'])

    {{-- 
        @stack('scripts') akan merender skrip apa pun yang di-push dari view anak.
        Ini adalah tempat yang tepat untuk logika JavaScript spesifik halaman,
        karena akan dieksekusi setelah semua library utama (seperti Alpine.js) dimuat.
    --}}
    @stack('scripts')
</body>
</html>
