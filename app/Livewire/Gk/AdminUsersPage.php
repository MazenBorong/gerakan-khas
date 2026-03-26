<?php

namespace App\Livewire\Gk;

use App\Gk\Services\AdminRemovesUser;
use App\Gk\Services\AdminUpdatesUserRole;
use App\Gk\Services\AdminUserMonthCredits;
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

    public function mount(): void
    {
        $this->creditYear = (int) now()->year;
        $this->creditMonth = (int) now()->month;
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
