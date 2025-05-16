<div class="col">
    <div class="card h-100 shadow-sm p-2">
        <div class="card-body">
            <h5 class="card-title">{{ $match->name }}</h5>
            <p class="card-text mb-1"><strong>Datum:</strong>
                {{ \Carbon\Carbon::parse($match->checkin_time)->format('Y-m-d') }}</p>
            <p class="card-text mb-1"><strong>Locatie:</strong> {{ $match->location }}</p>
            <p class="card-text mb-1"><strong>Check-in:</strong>
                {{ \Carbon\Carbon::parse($match->checkin_time)->format('H:i') }}</p>
            <p class="card-text mb-2"><strong>Aftrap:</strong>
                {{ \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') }}</p>

<button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#matchModal"
    data-match='@json($match)' onclick="openMatchModalFromButton(this, {{ $showRuil ? 'true' : 'true' }})">
    Meer
</button>

        </div>
    </div>
</div>

{{-- <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#matchModal"
    onclick='openMatchModal(@json($match), $match->deadline->isPast())'>
    Meer
</button> --}}