<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Moja Aplikacja' }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @media (max-width: 768px) {
            .flex-col-mobile {
                flex-direction: column;
            }
            .w-full-mobile {
                width: 100%;
            }
            .mb-4-mobile {
                margin-bottom: 1rem;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        
        <main class="bg-gray-100 py-20">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>