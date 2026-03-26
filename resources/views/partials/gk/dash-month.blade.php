<section class="gk-dash-section">
    <h2 class="gk-dash-h">This month — {{ $monthLabel }}</h2>
    <p class="gk-dash-sub">Total entries on the team calendar (all people, all days).</p>
    <div class="gk-dash-stats">
        @foreach (config('gk.statuses') as $code => $label)
            <div class="gk-dash-stat">
                <div class="gk-dash-stat__val">{{ $monthStats[$code] ?? 0 }}</div>
                <div class="gk-dash-stat__lab">{{ $label }}</div>
            </div>
        @endforeach
    </div>
</section>
