<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Applicatie')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
    {{-- Include the navbar --}}
    @include('partials.navbar')

    {{-- Content of pagina --}}
    <div class="content">
        @yield('content')
    </div>

    {{-- Include the footer --}}
    @include('partials.footer')
</body>
</html>