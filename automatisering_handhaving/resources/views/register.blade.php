@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('title', 'Registreren')

@section('content')


    {{-- Form for registration--}}
<div class="registerform">
    <form method="POST" action="{{ route('register') }}">
        <h1>Registratieformulier</h1>
        @csrf
        {{-- show errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="error">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        {{-- field for name --}}
        <label for="name">Volledige naam:</label>
        <input type="text" name="name" required><br>
        {{-- field for username --}}
        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" required><br>
        {{-- field for email --}}
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        {{-- select a group --}}
        <label for="group_id">Groep:</label>
        <select name="group_id" required>
            @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select><br>
        {{-- select a role --}}
        <label for="role">Rol:</label>
        <select name="role" required>
            <option value="gebruiker">Gebruiker</option>
            <option value="admin">Admin</option>
        </select><br>
        {{-- register button --}}
        <button type="submit" class="registerbutton">Account aanmaken</button>
    </form>
</div>
@endsection