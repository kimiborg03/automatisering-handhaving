@extends('layouts.app')

@section('title', 'Match Registraties')

@push('styles')
    <style>
        .content {
            background-color: #56C65B;
            min-height: calc(100vh - 100px);
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 2rem;
        }

        h2 {
            text-align: center;
        }
        td {
            background-color: #fff !important;
        }
    </style>
@endpush
@section('content')
<div class="container">
    <h2>{{ $match->name }}</h2>
    <div class="text-center">
        <a href="{{ route('matches.exportExcel', $match->id) }}" class="btn btn-success mb-3">
            <i class="bi bi-file-earmark-excel"></i> Download Excel
        </a>
    </div>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Naam</th>
                <th>Klas</th>
                <th>Aanwezig?</th>
            </tr>
        </thead>
        <tbody>
        @foreach($userDetails as $user)
            @php
            // Get the presence status for all users
                $presence = collect($users)->firstWhere('user_id', $user->id)['presence'] ?? false;
            @endphp
            <tr>
                {{-- field for user name --}}
                <td>{{ $user->name }}</td>

                {{-- field for user class --}}
                <td>{{ $groups[$user->group_id] ?? '-' }}</td>

                {{-- field for presence --}}
                <td class="text-center">
                    <input type="checkbox"
                        class="presence-checkbox"
                        data-user-id="{{ $user->id }}"
                        data-match-id="{{ $match->id }}"
                        {{ $presence ? 'checked' : '' }}>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add event listener to all presence checkboxes
            document.querySelectorAll('.presence-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const userId = this.dataset.userId;
                    const matchId = this.dataset.matchId;

                    // Check if the checkbox is checked or not
                    const presence = this.checked ? 1 : 0;

                    // Send the presence update to the server
                    fetch(`/matches/${matchId}/presence`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        // Include user ID and presence status in the request body
                        body: JSON.stringify({
                            user_id: userId,
                            presence: presence
                        })
                    })
                    // Handle the response
                    .then(response => response.json())
                    .then(data => {
                        console.log('Aanwezigheid bijgewerkt:', data); // Log the response
                    })
                    .catch(error => {
                        console.error('Fout bij updaten aanwezigheid:', error);
                        alert('Er is iets misgegaan bij het bijwerken van de aanwezigheid.'); // Show an error message
                    });
                });
            });
        });
    </script>
@endpush
@endsection
