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
                <button type="submit" class="loginbutton">Uitloggen</button>
            </form>
        @else
            <!-- Login form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Wachtwoord:</label>
                <input type="password" name="password" id="password" required>
                <div style="text-align: right; margin-bottom: 10px;">
                <a href="#" class="forgot-link" onclick="document.getElementById('forgotModal').style.display='block'; return false;">Wachtwoord vergeten?</a>
                </div>
                <button type="submit" class="loginbutton">Inloggen</button>
            </form>
        @endauth
        {{-- show errors --}}
            @if (session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    </div>
@endsection

<!-- Wachtwoord vergeten Modal -->
<div id="forgotModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
    <div class="modal-content" style="background:#fff; margin:10% auto; padding:30px; border-radius:8px; max-width:400px; position:relative;">
        <span onclick="document.getElementById('forgotModal').style.display='none'" style="position:absolute; top:10px; right:18px; font-size:24px; cursor:pointer;">&times;</span>
        <h4>Wachtwoord vergeten</h4>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="forgot_email" class="form-label">Vul je e-mailadres in</label>
                <input type="email" class="form-control" name="email" id="forgot_email" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Stuur reset-link</button>
        </form>
    </div>
</div>