<style>
    .icon-box {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.2rem;
    }
    .label-text {
        font-weight: 500;
        font-size: 0.9rem;
        color: #6c757d; /* Bootstrap's text-muted */
    }
</style>

<div class="col">
    <div class="card h-100 shadow-sm p-2">
        <div class="card-body">
            <h5 class="card-title">{{ $match->name }}</h5>

            <p class="card-text mb-1">
                <span class="icon-box bg-light text-primary me-2">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <span class="label-text">Datum:</span>
                {{ \Carbon\Carbon::parse($match->checkin_time)->format('Y-m-d') }}
            </p>

            <p class="card-text mb-1">
                <span class="icon-box bg-light text-danger me-2">
                    <i class="bi bi-geo-alt"></i>
                </span>
                <span class="label-text">Locatie:</span>
                {{ $match->location }}
            </p>

            <p class="card-text mb-1">
                <span class="icon-box bg-light text-success me-2">
                    <i class="bi bi-box-arrow-in-right"></i>
                </span>
                <span class="label-text">Check-in:</span>
                {{ \Carbon\Carbon::parse($match->checkin_time)->format('H:i') }}
            </p>

            <p class="card-text mb-2">
                <span class="icon-box bg-light text-warning me-2">
                    <i class="bi bi-stopwatch"></i>
                </span>
                <span class="label-text">Aftrap:</span>
                {{ \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') }}
            </p>

            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#matchModal"
                data-match='@json($match)' onclick="openMatchModalFromButton(this, {{ $showRuil ? 'true' : 'true' }})">
                Meer
            </button>
        </div>
    </div>
</div>
