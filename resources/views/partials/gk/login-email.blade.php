<div class="gk-auth-field">
    <span class="gk-auth-label">Email Address</span>
    <input
        class="gk-auth-input @error('email') gk-auth-input--err @enderror"
        type="email"
        wire:model.blur="email"
        autocomplete="username"
        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
    >
    @error('email')
        <p class="gk-auth-err">{{ $message }}</p>
    @enderror
</div>
