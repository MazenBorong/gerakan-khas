@props(['navRole' => 'staff'])
@php
    $here = fn (...$n) => request()->routeIs(...$n);
    $gkUser = auth()->user();
    $gkRoleKey = $gkUser ? \App\Gk\Support\RoleReader::forUser($gkUser) : 'staff';
    $gkRoleLabel = match ($gkRoleKey) {
        'admin' => 'Admin',
        'lead' => 'Lead',
        default => 'Staff',
    };
@endphp
<nav class="gk-appnav flex flex-wrap items-center gap-2 border-b px-3 py-2 sm:px-4 sm:py-3">
    <div class="gk-appnav__items flex flex-wrap items-center gap-1">
        @if ($navRole === 'admin')
            <a class="gk-appnav__link{{ $here('gk.admin.home') ? ' gk-appnav__link--active' : '' }}" href="{{ route('gk.admin.home') }}">Dashboard</a>
        @endif
        <a class="gk-appnav__link{{ $here('gk.calendar') ? ' gk-appnav__link--active' : '' }}" href="{{ route('gk.calendar') }}">Calendar</a>
        @if ($navRole === 'admin')
            <a class="gk-appnav__link{{ $here('gk.admin.users', 'gk.admin.users.create') ? ' gk-appnav__link--active' : '' }}" href="{{ route('gk.admin.users') }}">Users</a>
            <a class="gk-appnav__link{{ $here('gk.admin.holidays') ? ' gk-appnav__link--active' : '' }}" href="{{ route('gk.admin.holidays') }}">Holidays</a>
            <a class="gk-appnav__link{{ $here('gk.admin.settings') ? ' gk-appnav__link--active' : '' }}" href="{{ route('gk.admin.settings') }}">Settings</a>
        @else
            <a class="gk-appnav__link{{ $here('gk.settings') ? ' gk-appnav__link--active' : '' }}" href="{{ route('gk.settings') }}">Settings</a>
        @endif
    </div>
    <span class="gk-appnav__spacer flex-1"></span>
    @if ($gkUser)
        <div class="gk-appnav__user flex min-w-0 max-w-[11rem] flex-col items-end text-right text-xs leading-tight sm:max-w-none sm:flex-row sm:items-center sm:gap-2 sm:text-sm">
            <span class="gk-appnav__user-name">{{ $gkUser->name }}</span>
            <span class="gk-appnav__user-role">{{ $gkRoleLabel }}</span>
        </div>
    @endif
    <form method="post" action="{{ route('gk.logout') }}" class="inline">
        @csrf
        <x-gk.ui-button type="submit" variant="ghost">Logout</x-gk.ui-button>
    </form>
</nav>
