<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Public Safety Office')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/csulogo2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/csulogo2.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="min-h-screen">
        <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-2 w-80 max-w-[calc(100vw-2rem)]"></div>

        @if (session('greeting'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast(@json(session('greeting')));
                });
            </script>
        @endif

        @yield('content')
    </div>

    <x-loading-overlay />

</body>
</html>