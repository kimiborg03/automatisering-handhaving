@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('title', 'Klassen beheren')

@section('content')
<h2>Klassen beheren</h2>
{{-- add class button --}}
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClassModal">
    <i class="bi bi-plus-circle"></i>
</button>
<ul>
    @foreach ($groups as $group)
        <li>
            {{ $group->name }}
            {{-- delete button --}}
            <form method="POST" action="{{ route('admin.classes.deleteclass', $group->id) }}" style="display: inline;" onsubmit="return confirmDelete('{{ $group->name }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i> Verwijderen
                </button>
            </form>
            <!-- edit button -->
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editClassModal-{{ $group->id }}">
                <i class="bi bi-pencil"></i> Bewerken
            </button>
        </li>
    @endforeach
</ul>

{{-- include modals --}}
@include('admin.modals')

@endsection

@push('scripts')
<script>
    // confirm delete message when pressing the "verwijderen" button
    function confirmDelete(className) {
        return confirm(`Weet je zeker dat je klas "${className}" wilt verwijderen?`);
    }
</script>
@endpush