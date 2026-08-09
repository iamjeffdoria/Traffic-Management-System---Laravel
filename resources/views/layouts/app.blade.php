<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Traffic Management System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="min-h-screen">
        <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-2 w-80 max-w-[calc(100vw-2rem)]"></div>
        @yield('content')
    </div>

</body>
</html>