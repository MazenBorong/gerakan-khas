<?php

namespace App\Livewire\Gk;

use App\Gk\Services\AdminRemovesHoliday;
use App\Gk\Services\AdminStoresHoliday;
use App\Gk\Services\SyncMalaysiaPublicHolidays;
use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Layout('layouts.gk-app')]
#[Title('Holidays')]
final class AdminHolidaysPage extends Component
{
    public string $on_date = '';

    public string $label = '';

    public int $sync_year;

    public function mount(): void
    {
        $this->sync_year = (int) now()->year;
    }

    public function add(): void
    {
        $this->validate([
            'on_date' => ['required', 'date_format:Y-m-d'],
            'label' => ['nullable', 'string', 'max:120'],
        ]);
        $dateStr = $this->on_date;
        $labelPart = $this->label ?: null;
        $year = (int) Carbon::parse($dateStr)->year;
        AdminStoresHoliday::run($dateStr, $labelPart);
        $this->sync_year = $year;
        $this->dispatch(
            'gk-toast',
            icon: 'success',
            headline: 'Holiday saved',
            detail: $labelPart
                ? "Added {$dateStr} ({$labelPart})."
                : "Added {$dateStr}.",
        );
        $this->reset('on_date', 'label');
    }

    public function remove(int $id): void
    {
        $holiday = Holiday::query()->find($id);
        if (! $holiday) {
            $this->dispatch(
                'gk-toast',
                icon: 'error',
                headline: 'Could not remove',
                detail: 'That holiday is no longer in the list.',
            );

            return;
        }
        $summary = $holiday->on_date->toDateString();
        if ($holiday->label) {
            $summary .= ' · '.$holiday->label;
        }
        AdminRemovesHoliday::run($id);
        $this->dispatch(
            'gk-toast',
            icon: 'success',
            headline: 'Holiday removed',
            detail: $summary,
        );
    }

    public function syncMalaysia(): void
    {
        $this->validate([
            'sync_year' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);
        $year = $this->sync_year;
        try {
            $n = SyncMalaysiaPublicHolidays::run($year);
            $this->dispatch(
                'gk-toast',
                icon: 'success',
                headline: 'Malaysia holidays synced',
                detail: $n > 0
                    ? "Imported or updated {$n} public holiday date(s) for {$year}."
                    : "No holiday rows were returned for {$year}.",
            );
        } catch (Throwable) {
            $this->dispatch(
                'gk-toast',
                icon: 'error',
                headline: 'Sync failed',
                detail: 'Could not load holidays from the public API. Try again later.',
            );
        }
    }

    public function render()
    {
        $holidays = Holiday::query()
            ->whereYear('on_date', $this->sync_year)
            ->orderBy('on_date')
            ->get();

        return view('livewire.gk.admin-holidays-page', compact('holidays'));
    }
}
