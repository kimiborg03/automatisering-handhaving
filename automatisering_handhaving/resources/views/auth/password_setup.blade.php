@extends('layouts.app')

@section('title', 'Wachtwoord instellen')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/password_setup.css') }}">
@endpush

@section('content')
<div class="password-setup-wrapper">
    <div class="passwordsetupform">
        <h1>Welkom, {{ $user->name }}</h1>
        {{-- show errors --}}
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- form for setting up password --}}
        <form method="POST" action="{{ route('password.setup.submit') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $user->password_setup_token }}">

            <div>
                <label for="password">Nieuw wachtwoord:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div>
                <label for="password_confirmation">Bevestig wachtwoord:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <button type="submit" class="passwordsetupbutton">Wachtwoord instellen</button>
        </form>
    </div>
</div>
@endsection
