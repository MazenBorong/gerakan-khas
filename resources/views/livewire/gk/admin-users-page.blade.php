<div class="gk-admin">
    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
        <h1 class="gk-admin__title mb-0">Users</h1>
        <button type="button" class="gk-appnav__link text-sm font-semibold" wire:click="openCreateModal">Add user</button>
    </div>
    <div class="mb-4 flex flex-wrap items-end gap-3">
        <label class="flex flex-col gap-1 text-sm text-zinc-600">Year
            <input class="gk-auth-input max-w-[6.5rem]" type="number" wire:model.live="creditYear" min="2000" max="2100"></label>
        <label class="flex flex-col gap-1 text-sm text-zinc-600">Month
            <input class="gk-auth-input max-w-[5.5rem]" type="number" wire:model.live="creditMonth" min="1" max="12"></label>
        <span class="pb-2 text-sm font-semibold text-zinc-800">{{ \Carbon\Carbon::create($y, $m, 1)->format('F Y') }}</span>
    </div>
    <div class="gk-table-wrap gk-table-wrap--white">
        <table class="gk-data-table gk-data-table--users">
            <colgroup>
                <col style="width:13%">
                <col style="width:26%">
                <col style="width:8.5rem">
                <col>
                <col class="gk-col-actions">
            </colgroup>
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Calendar credits</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach ($rows as $u)
                    <tr wire:key="u-{{ $u->id }}">
                        <td class="align-top font-medium">{{ $u->name }}</td>
                        <td class="align-top">{{ $u->email }}</td>
                        <td class="align-top">
                            <select wire:key="role-{{ $u->id }}" wire:change="updateRole({{ $u->id }}, $event.target.value)">
                                @foreach (['admin' => 'Admin', 'lead' => 'Lead', 'staff' => 'Staff'] as $val => $lab)
                                    <option value="{{ $val }}" @selected(($meta[$u->id] ?? 'staff') === $val)>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="align-top">
                            @php $lines = $credits[$u->id]['lines'] ?? []; @endphp
                            @if (count($lines))
                                <div class="gk-cred-badges">@foreach ($lines as $row)<span class="gk-cred-badge gk-cred-badge--{{ $row['code'] }}">{{ $row['text'] }}</span>@endforeach</div>
                            @else
                                <span class="text-sm text-zinc-500">No entries this month</span>
                            @endif
                        </td>
                        <td class="align-top">
                            @if ($u->id !== auth()->id())
                                <button class="text-xs text-red-600 underline" type="button" wire:click="remove({{ $u->id }})" wire:confirm="Remove this user?">Remove</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($showCreateModal)
        <div
            class="gk-modal-backdrop"
            wire:click.self="closeCreateModal"
            wire:keydown.escape.window="closeCreateModal"
        >
            <div
                class="gk-modal-panel"
                role="dialog"
                aria-modal="true"
                aria-labelledby="gk-create-user-title"
                tabindex="-1"
            >
                <div class="gk-modal-panel__header">
                    <h2 id="gk-create-user-title" class="gk-modal-panel__title">Create user</h2>
                    <button type="button" class="gk-modal-panel__close" wire:click="closeCreateModal" aria-label="Close">×</button>
                </div>
                <form wire:submit.prevent="saveNewUser">
                    <div class="gk-modal-panel__body grid gap-3">
                        <div class="grid gap-1">
                            <label class="text-xs font-medium text-zinc-600">Name</label>
                            <input class="gk-auth-input" type="text" wire:model.blur="name" autocomplete="name">
                            @error('name') <p class="gk-auth-err">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-1">
                            <label class="text-xs font-medium text-zinc-600">Email</label>
                            <input class="gk-auth-input" type="email" wire:model.blur="email" autocomplete="email">
                            @error('email') <p class="gk-auth-err">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-1">
                            <label class="text-xs font-medium text-zinc-600">Password</label>
                            <input class="gk-auth-input" type="password" wire:model.blur="password" autocomplete="new-password">
                            @error('password') <p class="gk-auth-err">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-1">
                            <label class="text-xs font-medium text-zinc-600">Role</label>
                            <select class="gk-auth-input" wire:model.live="role">
                                <option value="staff">Staff</option>
                                <option value="lead">Lead</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('role') <p class="gk-auth-err">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="gk-modal-panel__footer flex flex-wrap gap-2">
                        <x-gk.ui-button type="submit" wire:loading.attr="disabled">Save</x-gk.ui-button>
                        <x-gk.ui-button type="button" variant="ghost" wire:click="closeCreateModal" wire:loading.attr="disabled">Cancel</x-gk.ui-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
