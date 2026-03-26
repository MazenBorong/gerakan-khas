<?php

use App\Gk\Http\Controllers\CalendarJsonController;
use App\Gk\Http\Controllers\PlanEntryController;
use App\Livewire\Gk\AdminHolidaysPage;
use App\Livewire\Gk\AdminHome;
use App\Livewire\Gk\AdminSettingsPage;
use App\Livewire\Gk\AdminUserCreate;
use App\Livewire\Gk\AdminUsersPage;
use App\Livewire\Gk\CalendarRulesViewPage;
use App\Livewire\Gk\LoginForm;
use App\Livewire\Gk\SmartCalendarPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', LoginForm::class)->middleware('guest')->name('gk.home');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('gk.home');
})->middleware('auth')->name('gk.logout');

Route::middleware('auth')->group(function () {
    Route::get('/settings', CalendarRulesViewPage::class)->name('gk.settings');
    Route::get('/calendar', SmartCalendarPage::class)->name('gk.calendar');
    Route::prefix('api/gk')->group(function () {
        Route::get('/calendar/{y}/{m}', [CalendarJsonController::class, 'show'])->name('gk.api.calendar');
        Route::post('/entries', [PlanEntryController::class, 'store'])->name('gk.api.entries.store');
        Route::patch('/entries/{entry}', [PlanEntryController::class, 'update'])->name('gk.api.entries.update');
        Route::delete('/entries/{entry}', [PlanEntryController::class, 'destroy'])->name('gk.api.entries.destroy');
    });
    Route::middleware('gk.role:admin')->prefix('gk/admin')->group(function () {
        Route::get('/', AdminHome::class)->name('gk.admin.home');
        Route::get('/users', AdminUsersPage::class)->name('gk.admin.users');
        Route::get('/users/create', AdminUserCreate::class)->name('gk.admin.users.create');
        Route::get('/holidays', AdminHolidaysPage::class)->name('gk.admin.holidays');
        Route::get('/settings', AdminSettingsPage::class)->name('gk.admin.settings');
    });
});
