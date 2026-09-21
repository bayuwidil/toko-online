<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online Modern</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Navbar Minimalis -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <a href="/" class="text-2xl font-black text-indigo-600 tracking-tighter">
                    STORE.
                </a>
                
                <!-- Menu Kanan (Cart & Login) -->
                <div class="flex items-center space-x-6">
                    <livewire:floating-cart />
                    <a href="/admin" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Akun</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama (Otomatis dirender oleh Livewire) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 py-8 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} Toko Online. All rights reserved.</p>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>