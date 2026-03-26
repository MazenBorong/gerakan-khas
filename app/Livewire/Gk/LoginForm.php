<?php

namespace App\Livewire\Gk;

use App\Gk\Auth\LoginAction;
use App\Gk\Auth\LoginValidation;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.gk')]
#[Title('Log In')]
final class LoginForm extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $showPassword = false;

    protected function rules(): array
    {
        return LoginValidation::rules();
    }

    protected function messages(): array
    {
        return LoginValidation::messages();
    }

    protected function validationAttributes(): array
    {
        return LoginValidation::attributes();
    }

    public function login(): void
    {
        $this->email = Str::lower(trim($this->email));
        $this->validate();
        LoginAction::attempt($this->email, $this->password);
        session()->regenerate();
        $this->redirectIntended(route('gk.calendar'), false);
    }

    public function render()
    {
        return view('livewire.gk.login-form');
    }
}
