@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('title', 'Admin')

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">
            <div class="admin-header">
                <h2>Welkom bij het admin paneel</h2>
                <p>Kies een actie hieronder:</p>
            </div>

            <div class="admin-dashboard">
                <a href="{{ url('/admin/users') }}" class="admin-card text-center">
                    <i class="bi bi-people-fill mb-2" style="font-size:2.5rem;"></i>
                    <h3 class="mt-2">Gebruikers beheren</h3>
                </a>
                <a href="{{ url('/admin/classes') }}" class="admin-card text-center">
                    <i class="bi bi-journal-bookmark-fill mb-2" style="font-size:2.5rem;"></i>
                    <h3 class="mt-2">Klassen beheren</h3>
                </a>
                <a href="{{ url('/admin/register') }}" class="admin-card text-center">
                    <i class="bi bi-person-plus-fill mb-2" style="font-size:2.5rem;"></i>
                    <h3 class="mt-2">Gebruiker registreren</h3>
                </a>
                <a href="{{ route('admin.mailsettings') }}" class="admin-card text-center">
                    <i class="bi bi-envelope-fill mb-2" style="font-size:2.5rem;"></i>
                    <h3 class="mt-2">Mailinstellingen</h3>
                </a>
                <a href="{{ route('admin.add-match') }}" class="admin-card text-center">
                    <i class="bi bi-plus-square-fill mb-2" style="font-size:2.5rem;"></i>
                    <h3 class="mt-2">Wedstrijd toevoegen</h3>
                </a>
            </div>
        </div>
    </div>
@endsection

