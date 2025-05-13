<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>@yield('title', 'Applicatie')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {font-family: Arial, sans-serif;
        }
    </style>
    @stack('styles')
    @stack('scripts')
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