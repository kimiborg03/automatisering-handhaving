{{-- resources/views/admin/mailsettings.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mailsettings.css') }}">
@endpush

@section('content')
<div class="mailsettings-bg">
    <div class="mailsettings-container">
        <h2 class="mailsettings-title">
            Mailinstellingen
            <span class="mailsettings-info" title="Na opslaan kan er tijdelijk een foutmelding verschijnen. Dit is normaal; bezoek de pagina opnieuw na enkele seconden.">
                &#9432;
            </span>
        </h2>
        @if(session('success'))
            <div class="mailsettings-success">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.mailsettings.update') }}">
            @csrf

            <div class="mb-3">
                <label class="mailsettings-label">MAIL_HOST</label>
                <input type="text" class="mailsettings-input" name="MAIL_HOST" value="{{ $fields['MAIL_HOST'] }}">
            </div>
            <div class="mb-3">
                <label class="mailsettings-label">MAIL_PORT</label>
                <input type="text" class="mailsettings-input" name="MAIL_PORT" value="{{ $fields['MAIL_PORT'] }}">
            </div>
            <div class="mb-3">
                <label class="mailsettings-label">MAIL_USERNAME</label>
                <input type="text" class="mailsettings-input" name="MAIL_USERNAME" value="{{ $fields['MAIL_USERNAME'] }}">
            </div>
            <div class="mb-3">
                <label class="mailsettings-label">MAIL_PASSWORD</label>
                <input type="text" class="mailsettings-input" name="MAIL_PASSWORD" value="{{ $fields['MAIL_PASSWORD'] }}">
            </div>
            <div class="mb-3">
                <label class="mailsettings-label">MAIL_FROM_ADDRESS</label>
                <input type="text" class="mailsettings-input" name="MAIL_FROM_ADDRESS" value="{{ $fields['MAIL_FROM_ADDRESS'] }}">
            </div>
            <div class="mb-3">
                <label class="mailsettings-label">MAIL_FROM_NAME</label>
                <input type="text" class="mailsettings-input" name="MAIL_FROM_NAME" value="{{ $fields['MAIL_FROM_NAME'] }}">
            </div>

            <button type="submit" class="mailsettings-btn">Opslaan</button>
        </form>
        <a href="{{ url('/admin') }}" class="btn btn-secondary back-button">
            <i class="bi bi-arrow-return-left"></i> Terug naar Admin paneel
        </a>
    </div>
</div>
@endsection