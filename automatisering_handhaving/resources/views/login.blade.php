@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('title', 'Login')

@section('content')
    <div class="loginform">
        @auth
            <h1>Je bent al ingelogd</h1>
            <!-- Log out button in case user is logged in -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Uitloggen</button>
            </form>
        @else
            <!-- Login form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Wachtwoord:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Inloggen</button>
            </form>
        @endauth
    </div>
@endsection