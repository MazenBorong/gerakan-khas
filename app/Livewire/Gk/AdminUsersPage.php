<?php

namespace App\Livewire\Gk;

use App\Gk\Services\AdminRegistersUser;
use App\Gk\Services\AdminRemovesUser;
use App\Gk\Services\AdminUpdatesUserRole;
use App\Gk\Services\AdminUserMonthCredits;
use App\Gk\Support\AdminUserFormRules;
use App\Gk\Support\GkSettings;
use App\Models\User;
use App\Models\UserMeta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk-app')]
#[Title('Users')]
final class AdminUsersPage extends Component
{
    public int $creditYear;

    public int $creditMonth;

    public bool $showCreateModal = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'staff';

    public function mount(): void
    {
        $this->creditYear = (int) now()->year;
        $this->creditMonth = (int) now()->month;
        $this->role = GkSettings::defaultNewUserRole();
        if (request()->boolean('create')) {
            $this->openCreateModal();
        }
    }

    public function openCreateModal(): void
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
    }

    public function saveNewUser(): void
    {
        $this->validate(AdminUserFormRules::rules(), AdminUserFormRules::messages());
        AdminRegistersUser::run($this->name, $this->email, $this->password, $this->role);
        $this->dispatch(
            'gk-toast',
            icon: 'success',
            headline: 'User created',
            detail: $this->email.' can sign in now.',
        );
        $this->closeCreateModal();
    }

    private function resetCreateForm(): void
    {
        $this->reset(['name', 'email', 'password']);
        $this->role = GkSettings::defaultNewUserRole();
    }

    public function updateRole(int $userId, string $role): void
    {
        AdminUpdatesUserRole::run($userId, $role);
    }

    public function remove(int $userId): void
    {
        AdminRemovesUser::run($userId);
    }

    public function render()
    {
        $rows = User::query()->orderBy('name')->get();
        $meta = UserMeta::query()->whereIn('user_id', $rows->pluck('id'))->pluck('role', 'user_id');
        $y = max(2000, min(2100, $this->creditYear));
        $m = max(1, min(12, $this->creditMonth));
        $credits = AdminUserMonthCredits::build($y, $m, $rows);

        return view('livewire.gk.admin-users-page', compact('rows', 'meta', 'credits', 'y', 'm'));
    }
}
