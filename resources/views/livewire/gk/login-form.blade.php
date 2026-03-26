<div class="gk-auth-wrap">
    <div class="gk-auth-card">
        <p class="gk-auth-kicker">Welcome to Gerakan Khas</p>
        <h1 class="gk-auth-title">Log In</h1>
        <form wire:submit.prevent="login" class="gk-auth-form">
            @include('partials.gk.login-email')
            @include('partials.gk.login-password')
            <div class="gk-auth-divider"></div>
            <div class="gk-auth-actions">
                <a class="gk-auth-forgot" href="#">Forgot Password?</a>
                <button class="gk-auth-submit" type="submit">Sign In</button>
            </div>
        </form>
    </div>
</div>
