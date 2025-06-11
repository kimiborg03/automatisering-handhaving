@extends('layouts.app')
 {{-- reset password for new password after requesting a reset link. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/reset.css') }}">
@endpush

@section('content')
<div class="resetform">
    <h2 class="reset-title">Nieuw wachtwoord instellen</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom:0;">
                {{-- show errors --}}
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <label for="password">Nieuw wachtwoord</label>
            <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
            <label for="password_confirmation">Herhaal wachtwoord</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary w-100">Wachtwoord instellen</button>
    </form>
</div>
@endsection