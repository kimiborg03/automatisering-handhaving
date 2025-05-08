<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Applicatie')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {font-family: Arial, sans-serif;
        }
    </style>
    @stack('styles')
 
</head>
<body>
    {{-- Include the navbar --}}
    <div class="navbar">
    @include('partials.navbar')
    </div>

    {{-- Content of pagina --}}
    <div class="content">
        @yield('content')
    </div>

    {{-- Include the footer --}}
    @include('partials.footer')
</body>
</html> 