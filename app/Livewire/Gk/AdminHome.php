<?php

namespace App\Livewire\Gk;

use App\Gk\Services\AdminDashboardSnapshot;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk-app')]
#[Title('Dashboard')]
final class AdminHome extends Component
{
    public function render()
    {
        $dash = AdminDashboardSnapshot::build(now());

        return view('livewire.gk.admin-home', $dash);
    }
}
