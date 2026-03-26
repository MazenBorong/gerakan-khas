<div class="gk-admin gk-admin--dash">
    <h1 class="gk-admin__title">Dashboard</h1>
    <p class="gk-dash-asof">{{ $todayLabel }}</p>
    @include('partials.gk.dash-today')
    @include('partials.gk.dash-month')
    @include('partials.gk.dash-holiday')
    @include('partials.gk.dash-links')
</div>
