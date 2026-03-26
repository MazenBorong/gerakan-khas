@props(['label', 'teamCount' => 0, 'entryCount' => 0, 'py', 'pm', 'ny', 'nm'])
<header class="gk-cal-chrome">
    <div class="gk-cal-chrome__row">
        <div class="gk-cal-chrome__lead">
            <h2 class="gk-cal-chrome__title">
                <span class="gk-cal-chrome__title-text">{{ $label }}</span>
                <span class="gk-cal-chrome__badge">{{ $entryCount }} entries</span>
            </h2>
            <p class="gk-cal-chrome__meta">{{ $teamCount }} people</p>
        </div>
        <div class="gk-cal-chrome__side">
            <div class="gk-cal-chrome__seg" aria-hidden="true"><span class="is-active">Team grid</span></div>
            <nav class="gk-cal-chrome__nav" aria-label="Month">
                <a class="gk-cal-chrome__nav-btn" href="{{ route('gk.calendar', ['y' => $py, 'm' => $pm]) }}" aria-label="Previous month"><span aria-hidden="true">‹</span></a>
                <a class="gk-cal-chrome__nav-btn" href="{{ route('gk.calendar', ['y' => $ny, 'm' => $nm]) }}" aria-label="Next month"><span aria-hidden="true">›</span></a>
            </nav>
        </div>
    </div>
</header>
