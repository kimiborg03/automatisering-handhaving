@extends('layouts.app')

@section('title', 'Accountpagina')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush

@section('content')


<div class="account-page">
    <div class="account-card">
        <div class="text-center">
            <div class="account-avatar">
                <i class="bi bi-person"></i>
            </div>
            <h3 class="mb-3">Mijn account</h3>
        </div>
        {{-- show relevant information about the user --}}
        <div class="account-info">
            <p><strong>Naam:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Gebruikersnaam:</strong> {{ Auth::user()->username }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Klas:</strong> 
                @php
                    $group = \App\Models\Groups::find(Auth::user()->group_id);
                @endphp
                {{ $group ? $group->name : '-' }}
            </p>
        </div>
        <div class="text-center mt-4">
            {{-- button to form for changing password --}}
            <button class="btn btn-primary" onclick="document.getElementById('passwordModal').style.display='block'">
                Wachtwoord veranderen
            </button>
        </div>
    </div>
</div>

{{-- Include the password change modal --}}
@include('partials.password_modal')
@endsection
