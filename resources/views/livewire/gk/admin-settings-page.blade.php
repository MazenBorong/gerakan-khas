<div class="gk-admin">
    <h1 class="gk-admin__title">Settings</h1>

    <div class="mb-8 max-w-xl space-y-4">
        <h2 class="text-base font-semibold text-zinc-900">Calendar rules</h2>
        <p class="text-sm text-zinc-600">These values apply to booking lead time, WFH capacity checks, and the Malaysia holiday sync URL.</p>
        <form class="grid gap-3" wire:submit.prevent="saveCalendarRules">
            <label class="flex flex-col gap-1 text-sm text-zinc-600">
                Max WFH per day (others on same day)
                <input class="gk-auth-input max-w-[8rem]" type="number" wire:model="max_wfh_per_day" min="1" max="50" required>
            </label>
            @error('max_wfh_per_day') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <label class="flex flex-col gap-1 text-sm text-zinc-600">
                Staff lead time (days ahead)
                <input class="gk-auth-input max-w-[8rem]" type="number" wire:model="lead_days_ahead" min="0" max="365" required>
            </label>
            @error('lead_days_ahead') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <label class="flex flex-col gap-1 text-sm text-zinc-600">
                Malaysia holidays API URL (use <code class="text-xs">%d</code> for year; leave blank for app default)
                <input class="gk-auth-input w-full max-w-lg" type="url" wire:model="malaysia_holidays_url" placeholder="{{ config('gk.malaysia_holidays_url') }}">
            </label>
            @error('malaysia_holidays_url') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <x-gk.ui-button type="submit">Save calendar rules</x-gk.ui-button>
        </form>
    </div>

    <div class="mb-8 max-w-xl space-y-4">
        <h2 class="text-base font-semibold text-zinc-900">New user accounts</h2>
        <p class="text-sm text-zinc-600">Default role when an admin creates a user from <a class="gk-appnav__link font-medium" href="{{ route('gk.admin.users', ['create' => 1]) }}">Users → Add user</a>.</p>
        <form class="grid gap-3" wire:submit.prevent="saveUserDefaults">
            <label class="flex flex-col gap-1 text-sm text-zinc-600">
                Default role
                <select class="gk-auth-input max-w-[12rem]" wire:model="default_new_user_role">
                    <option value="staff">Staff</option>
                    <option value="lead">Lead</option>
                </select>
            </label>
            @error('default_new_user_role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <x-gk.ui-button type="submit">Save user defaults</x-gk.ui-button>
        </form>
    </div>

    <div class="max-w-xl border-t border-zinc-200 pt-6">
        <h2 class="mb-2 text-base font-semibold text-zinc-900">Manage accounts</h2>
        <ul class="list-inside list-disc text-sm text-zinc-600">
            <li><a class="gk-appnav__link font-medium" href="{{ route('gk.admin.users') }}">Users</a> — roles and access</li>
            <li><a class="gk-appnav__link font-medium" href="{{ route('gk.admin.users', ['create' => 1]) }}">Add user</a></li>
        </ul>
    </div>
</div>
