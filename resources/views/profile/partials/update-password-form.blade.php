<section>
    <header class="mb-4">
        <h6 class="mb-2 fw-bold text-uppercase d-flex align-items-center gap-2">
            <i class="bi bi-lock"></i>{{ __('Update Password') }}
        </h6>
        <p class="text-muted small mb-0">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control form-control-lg" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-semibold">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control form-control-lg" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control form-control-lg" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle me-2"></i>{{ __('Save') }}
            </button>
            @if (session('status') === 'password-updated')
                <span class="badge bg-success p-2">
                    <i class="bi bi-check-circle me-1"></i>{{ __('Saved.') }}
                </span>
            @endif
        </div>
    </form>
</section>
