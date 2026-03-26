<div class="gk-admin">
    <h1 class="gk-admin__title">Calendar rules</h1>
    <p class="mb-6 max-w-xl text-sm text-zinc-600">
        These values are set by an administrator. This page is view only.
    </p>

    <dl class="max-w-xl space-y-4 rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">Max WFH per day</dt>
            <dd class="mt-1 text-base font-semibold text-zinc-900">{{ $max_wfh_per_day }}</dd>
            <dd class="mt-1 text-sm text-zinc-600">How many people can be on WFH on the same working day (others on the team).</dd>
        </div>
        <div class="border-t border-zinc-100 pt-4">
            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">Staff lead time</dt>
            <dd class="mt-1 text-base font-semibold text-zinc-900">{{ $lead_days_ahead }} calendar days ahead</dd>
            <dd class="mt-1 text-sm text-zinc-600">
                Staff must book at least this many full calendar days after today. Earliest bookable day from today:
                <span class="font-medium text-zinc-800">{{ $first_bookable_on_or_after }}</span>.
            </dd>
        </div>
        <div class="border-t border-zinc-100 pt-4">
            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500">Malaysia public holidays sync</dt>
            <dd class="mt-1 break-all font-mono text-sm text-zinc-800">{{ $malaysia_holidays_url_effective }}</dd>
            <dd class="mt-1 text-sm text-zinc-600">
                @if ($malaysia_holidays_url_is_custom)
                    Custom URL configured for syncing holidays into the calendar.
                @else
                    Using the application default URL (no custom override).
                @endif
            </dd>
        </div>
    </dl>
</div>
