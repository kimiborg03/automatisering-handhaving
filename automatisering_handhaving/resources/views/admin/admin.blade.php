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
                <a href="{{ url('/admin/users') }}" class="admin-card">
                    <h3>Gebruikers beheren</h3>
                </a>
                <a href="{{ url('/admin/classes') }}" class="admin-card">
                    <h3>Klassen beheren</h3>
                </a>
                <a href="{{ url('/admin/register') }}" class="admin-card">
                    <h3>Gebruiker registreren</h3>
                </a>
                {{-- empty space for layout --}}
                <div class="admin-card admin-card-spacer"></div> 
            
                <a href="{{ url('/admin/add-match') }}" class="admin-card">
                    <h3>Wedstrijd toevoegen</h3>
                </a>
            </div>
        </div>
    </div>
@endsection

