<?php

namespace App\Livewire\Gk;

use App\Gk\Support\GkSettings;
use App\Models\GkSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk-app')]
#[Title('Settings')]
final class AdminSettingsPage extends Component
{
    public int $max_wfh_per_day;

    public int $lead_days_ahead;

    public string $malaysia_holidays_url = '';

    public string $default_new_user_role = 'staff';

    public function mount(): void
    {
        $s = GkSettings::record();
        $this->max_wfh_per_day = (int) $s->max_wfh_per_day;
        $this->lead_days_ahead = (int) $s->lead_days_ahead;
        $this->malaysia_holidays_url = (string) ($s->malaysia_holidays_url ?? '');
        $this->default_new_user_role = GkSettings::defaultNewUserRole();
    }

    public function saveCalendarRules(): void
    {
        $this->validate([
            'max_wfh_per_day' => ['required', 'integer', 'min:1', 'max:50'],
            'lead_days_ahead' => ['required', 'integer', 'min:0', 'max:365'],
            'malaysia_holidays_url' => ['nullable', 'string', 'max:512'],
        ]);
        $row = GkSetting::query()->firstOrFail();
        $row->max_wfh_per_day = $this->max_wfh_per_day;
        $row->lead_days_ahead = $this->lead_days_ahead;
        $trimmed = trim($this->malaysia_holidays_url);
        $row->malaysia_holidays_url = $trimmed === '' ? null : $trimmed;
        $row->save();
        GkSettings::forgetCached();
        $this->dispatch(
            'gk-toast',
            icon: 'success',
            headline: 'Calendar rules saved',
            detail: 'WFH limits and booking window are updated for new checks.',
        );
    }

    public function saveUserDefaults(): void
    {
        $this->validate([
            'default_new_user_role' => ['required', 'in:staff,lead'],
        ]);
        $row = GkSetting::query()->firstOrFail();
        $row->default_new_user_role = $this->default_new_user_role;
        $row->save();
        GkSettings::forgetCached();
        $this->dispatch(
            'gk-toast',
            icon: 'success',
            headline: 'User defaults saved',
            detail: 'New accounts created by admins will use this role unless changed.',
        );
    }

    public function render()
    {
        return view('livewire.gk.admin-settings-page');
    }
}
