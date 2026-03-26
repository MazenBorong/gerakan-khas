<?php

namespace App\Livewire\Gk;

use App\Gk\Support\RoleReader;
use App\Models\PlanEntry;
use App\Models\PlanMonth;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk-app')]
#[Title('Calendar')]
final class SmartCalendarPage extends Component
{
    public int $year;

    public int $month;

    public function mount(): void
    {
        $this->year = (int) request('y', now()->year);
        $this->month = (int) request('m', now()->month);
    }

    public function render()
    {
        $pmId = PlanMonth::query()
            ->where('year', $this->year)
            ->where('month', $this->month)
            ->value('id');
        $entryCount = $pmId ? PlanEntry::query()->where('plan_month_id', $pmId)->count() : 0;
        $teamCount = User::query()->count();

        $actor = request()->user();
        abort_if(! $actor instanceof User, 403);

        return view('livewire.gk.smart-calendar-page', [
            'year' => $this->year,
            'month' => $this->month,
            'isAdmin' => RoleReader::forUser($actor) === 'admin',
            'teamCount' => $teamCount,
            'entryCount' => $entryCount,
        ]);
    }
}
