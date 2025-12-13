<section>
    <header>
        <h4 class="fw-bold">{{ __('Update Password') }}</h4>
        <p class="text-muted">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            <!-- You can add error handling here if needed -->
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            <!-- You can add error handling here if needed -->
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            <!-- You can add error handling here if needed -->
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-dark">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p class="text-muted small">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
