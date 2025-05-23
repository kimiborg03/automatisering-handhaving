@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="#">
@endpush

<style>
    .content {
        background-color: #56C65B;
        padding: 20px;
    }
    .back-button {
        margin-top: 5px
    }

    #groups.form-check-group {
        max-height: 180px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
    }
</style>

@section('title', 'Wedstrijd toevoegen ')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm" style="padding: 26px; border-radius: 10px;">
                <div class="card-body">
                    {{-- success message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="card-title text-center mb-4">Wedstrijd Toevoegen</h3>

                    <form method="POST" action="{{ route('matches.store') }}" novalidate>
                        @csrf

                        {{-- name match field --}}
                        <div class="mb-3">
                            <label for="name-match" class="form-label">Naam wedstrijd</label>
                            <input type="text" class="form-control @error('name-match') is-invalid @enderror" name="name-match" id="name-match" value="{{ old('name-match') }}" required>
                            @error('name-match')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- location field --}}
                        <div class="mb-3">
                            <label for="location" class="form-label">Locatie</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" id="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- date field --}}
                        <div class="mb-3">
                            <label for="date" class="form-label">Datum</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- check-in-time field --}}
                        <div class="mb-3">
                            <label for="check-in-time" class="form-label">Verzamelen tijdstip</label>
                            <input type="time" class="form-control @error('check-in-time') is-invalid @enderror" name="check-in-time" id="check-in-time" value="{{ old('check-in-time') }}" required>
                            @error('check-in-time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- kick-off-time field --}}
                        <div class="mb-3">
                            <label for="kick-off-time" class="form-label">Aftrap tijdstip</label>
                            <input type="time" class="form-control @error('kick-off-time') is-invalid @enderror" name="kick-off-time" id="kick-off-time" value="{{ old('kick-off-time') }}" required>
                            @error('kick-off-time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- category field --}}
                        <div class="mb-3">
                            <label for="category" class="form-label">Categorie</label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" id="category" required>
                                <option value="">-- Selecteer een categorie --</option>
                                <option value="AZ-Alkmaar" {{ old('category') == 'AZ-Alkmaar' ? 'selected' : '' }}>AZ-Alkmaar</option>
                                <option value="Jong-AZ" {{ old('category') == 'Jong-AZ' ? 'selected' : '' }}>Jong-AZ</option>
                                <option value="AZ-Vrouwen" {{ old('category') == 'AZ-Vrouwen' ? 'selected' : '' }}>AZ-Vrouwen</option>
                                <option value="Overige" {{ old('category') == 'Overige' ? 'selected' : '' }}>Overige</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- field for selecting groups --}}
                        <div id="groups" class="form-check-group mb-3">
                            @foreach ($groups as $group)
                                <div class="form-check">
                                    <input class="form-check-input @error('groups') is-invalid @enderror" type="checkbox" name="groups[]" value="{{ $group->id }}" id="group{{ $group->id }}"
                                        {{ is_array(old('groups')) && in_array($group->id, old('groups')) ? 'checked' : '' }}>
                                        {{-- badge with users count --}}
                                <label class="form-check-label d-flex justify-content-between w-100" for="group{{ $group->id }}">
                                    <span>{{ $group->name }}</span>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-people-fill me-1"></i>{{ $group->users_count }}
                                    </span>
                                </label>

                                </div>
                            @endforeach

                            @error('groups')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- limit field --}}
                        <div class="mb-3">
                            <label for="Limit" class="form-label">Aantal</label>
                            <input type="number" class="form-control @error('Limit') is-invalid @enderror" id="Limit" name="Limit" value="{{ old('Limit') }}" required>
                            @error('Limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- comment field --}}
                        <div class="mb-3">
                            <label for="comment" class="form-label">Opmerking</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" name="comment" id="comment" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- submit form button --}}
                        <button type="submit" class="btn btn-primary w-100">Wedstrijd toevoegen</button>
                    </form>

                    {{-- return button to the admin panel --}}
                    <a href="{{ url('/admin') }}" class="btn btn-secondary back-button mt-3">
                        <i class="bi bi-arrow-return-left"></i> Terug naar Admin paneel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
    const checkbox = document.getElementById('extraCheckbox');
    const container = document.getElementById('extraInputContainer');

    if (checkbox && container) {
        checkbox.addEventListener('change', () => {
            container.style.display = checkbox.checked ? 'block' : 'none';
        });
    }
</script>
@endsection
