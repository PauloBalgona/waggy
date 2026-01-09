<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Waggy')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pusher-key" content="{{ env('PUSHER_APP_KEY') }}">
    <meta name="pusher-cluster" content="{{ env('PUSHER_APP_CLUSTER') }}">
    <meta name="pusher-host" content="{{ env('PUSHER_HOST', '127.0.0.1') }}">
    <meta name="pusher-port" content="{{ env('PUSHER_PORT', 6001) }}">
    <meta name="pusher-scheme" content="{{ env('PUSHER_SCHEME', 'http') }}">
    <meta name="broadcast-auth-endpoint" content="{{ route('broadcasting.auth') }}">
    <!-- Load CSS synchronously to prevent FOUC -->
    <link href="{{ Vite::asset('resources/css/app.css') }}" rel="stylesheet">
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}" defer></script>
</head>
<body class="@yield('body-class', 'bg-gray-100')">
    @yield('content')
</body>
</html>
