<?php

namespace App\Livewire\Gk;

use App\Gk\Services\AdminRegistersUser;
use App\Gk\Support\AdminUserFormRules;
use App\Gk\Support\GkSettings;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk-app')]
#[Title('Create user')]
final class AdminUserCreate extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'staff';

    public function mount(): void
    {
        $this->role = GkSettings::defaultNewUserRole();
    }

    public function save(): void
    {
        $this->validate(AdminUserFormRules::rules(), AdminUserFormRules::messages());
        AdminRegistersUser::run($this->name, $this->email, $this->password, $this->role);
        $this->redirectRoute('gk.admin.users', navigate: false);
    }

    public function render()
    {
        return view('livewire.gk.admin-user-create');
    }
}
