<div class="gk-admin">
    <h1 class="gk-admin__title">Holidays</h1>
    <div class="mb-4 flex flex-wrap items-end gap-2">
        <label class="flex flex-col gap-1 text-sm text-zinc-600">
            Year
            <input class="gk-auth-input max-w-[7rem]" type="number" wire:model.live="sync_year" min="2000" max="2100">
        </label>
        <x-gk.ui-button type="button" wire:click="syncMalaysia">Sync Malaysia (API)</x-gk.ui-button>
    </div>
    @error('sync_year') <p class="mb-2 text-sm text-red-600">{{ $message }}</p> @enderror
    <form class="mb-4 grid max-w-md gap-2" wire:submit.prevent="add">
        <div class="flex flex-wrap gap-2">
            <input class="gk-auth-input max-w-[11rem]" type="date" wire:model="on_date">
            <input class="gk-auth-input min-w-[10rem] flex-1" type="text" wire:model.blur="label" placeholder="Label (optional)">
        </div>
        @error('on_date') <p class="gk-auth-err">{{ $message }}</p> @enderror
        <x-gk.ui-button type="submit">Add holiday</x-gk.ui-button>
    </form>
    <div class="gk-table-wrap">
        <p class="mb-2 text-sm font-semibold text-zinc-800">Holidays in {{ $sync_year }}</p>
        <table class="gk-data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Label</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($holidays as $h)
                    <tr wire:key="h-{{ $h->id }}">
                        <td>{{ $h->on_date->toDateString() }}</td>
                        <td>{{ $h->label ?? '—' }}</td>
                        <td>
                            <button
                                class="text-xs text-red-600 underline"
                                type="button"
                                wire:click="remove({{ $h->id }})"
                                wire:confirm="Remove this holiday?"
                            >Remove</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-zinc-500">No holidays for {{ $sync_year }} yet. Sync from the API or add one above.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
