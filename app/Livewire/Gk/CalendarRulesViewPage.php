<?php

namespace App\Livewire\Gk;

use App\Gk\Support\GkSettings;
use App\Gk\Support\RoleReader;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk-app')]
#[Title('Calendar rules')]
final class CalendarRulesViewPage extends Component
{
    public int $max_wfh_per_day;

    public int $lead_days_ahead;

    public string $first_bookable_on_or_after;

    /** Effective holiday sync URL (custom or app default). */
    public string $malaysia_holidays_url_effective;

    public bool $malaysia_holidays_url_is_custom;

    public function mount(): void
    {
        $user = Auth::user();
        abort_if(! $user instanceof User, 403);
        if (RoleReader::forUser($user) === 'admin') {
            $this->redirect(route('gk.admin.settings'), navigate: true);

            return;
        }

        $s = GkSettings::record();
        $this->max_wfh_per_day = (int) $s->max_wfh_per_day;
        $this->lead_days_ahead = (int) $s->lead_days_ahead;
        $this->first_bookable_on_or_after = now()->startOfDay()->addDays($this->lead_days_ahead)->toDateString();
        $custom = trim((string) ($s->malaysia_holidays_url ?? ''));
        $this->malaysia_holidays_url_is_custom = $custom !== '';
        $this->malaysia_holidays_url_effective = GkSettings::malaysiaHolidaysUrlTemplate();
    }

    public function render()
    {
        return view('livewire.gk.calendar-rules-view-page');
    }
}
