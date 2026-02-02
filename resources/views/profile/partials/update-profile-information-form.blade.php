<section>
    <header class="mb-3">
        <h5 class="mb-1">{{ __('Profile Information') }}</h5>
        <div class="text-muted">{{ __("Update your account's profile information and email address.") }}</div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="text-muted">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="btn btn-link p-0 align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 mb-0">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <hr class="my-4">

        <h6 class="mb-3">Tema & Latar Belakang</h6>
        <div class="mb-3">
            <label class="form-label">Gambar Latar (jpg/png/webp, max 5MB)</label>
            <input type="file" name="theme_background" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Mode Kontras Otomatis</label>
            <select name="theme_overlay" class="form-select">
                <option value="auto" @selected($user->theme_overlay === 'auto')>Otomatis (disarankan)</option>
                <option value="dark" @selected($user->theme_overlay === 'dark')>Gelap (teks putih)</option>
                <option value="light" @selected($user->theme_overlay === 'light')>Terang (teks gelap)</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ukuran Gambar Latar</label>
            <select name="theme_bg_size" class="form-select">
                <option value="cover" @selected($user->theme_bg_size === 'cover')>Cover (penuhi layar)</option>
                <option value="contain" @selected($user->theme_bg_size === 'contain')>Contain (tampil seluruh gambar)</option>
                <option value="auto" @selected($user->theme_bg_size === 'auto')>Auto</option>
            </select>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="theme_remove" name="theme_remove">
            <label class="form-check-label" for="theme_remove">Hapus gambar latar</label>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary">{{ __('Save') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
