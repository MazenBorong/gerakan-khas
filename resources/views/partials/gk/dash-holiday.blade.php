<section class="gk-dash-section">
    <h2 class="gk-dash-h">Next public holiday</h2>
    @if ($nextHoliday)
        <p class="gk-dash-ph">
            <strong>{{ $nextHoliday->on_date->format('l, j F Y') }}</strong>
            @if ($nextHoliday->label)
                — {{ $nextHoliday->label }}
            @endif
        </p>
    @else
        <p class="gk-dash-empty">No upcoming dates in Holidays. Add or sync from <a class="gk-appnav__link font-medium" href="{{ route('gk.admin.holidays') }}">Holidays</a>.</p>
    @endif
</section>
