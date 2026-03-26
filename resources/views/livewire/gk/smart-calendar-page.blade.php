@php
    $p = \Carbon\Carbon::create($year, $month, 1)->subMonth();
    $n = \Carbon\Carbon::create($year, $month, 1)->addMonth();
    $monthLabel = \Carbon\Carbon::create($year, $month, 1)->format('F Y');
@endphp
<div class="gk-cal-page{{ $isAdmin ? ' gk-cal-page--admin' : '' }}">
    <x-gk.toolbar
        :label="$monthLabel"
        :team-count="$teamCount"
        :entry-count="$entryCount"
        :py="$p->year"
        :pm="$p->month"
        :ny="$n->year"
        :nm="$n->month"
    />
    <x-gk.legend />
    <div
        id="gk-root"
        class="gk-cal-root"
        data-url="{{ route('gk.api.calendar', ['y' => $year, 'm' => $month]) }}"
        data-store="{{ route('gk.api.entries.store') }}"
        data-entries="{{ url('/api/gk/entries') }}"
        data-year="{{ $year }}"
        data-month="{{ $month }}"
    ></div>
</div>
