<div class="gk-admin">
    <h1 class="gk-admin__title">Create user</h1>
    <form class="grid max-w-md gap-3" wire:submit.prevent="save">
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
        <div class="flex flex-wrap gap-2">
            <x-gk.ui-button type="submit">Save</x-gk.ui-button>
            <a class="gk-appnav__link self-center text-sm" href="{{ route('gk.admin.users') }}">Cancel</a>
        </div>
    </form>
</div>
