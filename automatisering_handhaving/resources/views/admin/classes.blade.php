@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/classes.css') }}">
@endpush

@section('title', 'Klassen beheren')

@section('content')
    <div class="class-card">
        <h2 class="text-center">Klassenbeheer</h2>
        
        {{-- add class button --}}
        <div class="text-end mb-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClassModal">
                <i class="bi bi-plus-circle"></i>
            </button>
        </div>
        {{-- error message --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        {{-- success message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="class-list">
            {{-- list of classes --}}
            @foreach ($groups as $group)
                <div class="class-item mb-3">
                    <span class="class-name">{{ $group->name }}</span>
                    <div class="button-group">
                        {{-- edit class button --}}
                        <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editClassModal-{{ $group->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        {{-- delete class button --}}
                        <form method="POST" action="{{ route('admin.classes.deleteclass', $group->id) }}" class="d-inline" onsubmit="return confirmDelete('{{ $group->name }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- back to admin panel button --}}
        <a href="{{ url('/admin') }}" class="btn btn-secondary back-button">
        <i class="bi bi-arrow-return-left"></i> Terug naar Admin paneel
        </a>
    </div>
{{-- include modals for adding and editing popups --}}
@include('admin.classes-modals')
@endsection

@push('scripts')
<script>
    // confirm delete message when pressing the "verwijderen" button
    function confirmDelete(className) {
        return confirm(`Weet je zeker dat je klas "${className}" wilt verwijderen?`);
    }
</script>
@endpush
