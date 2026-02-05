<section>
    <header class="mb-4">
        <h6 class="mb-2 fw-bold text-uppercase d-flex align-items-center gap-2">
            <i class="bi bi-person"></i>{{ __('Profile Information') }}
        </h6>
        <p class="text-muted small mb-0">{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control form-control-lg" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control form-control-lg" value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <div class="alert alert-info small">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline ms-2">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 mb-0 small">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <hr class="my-4">

        <div class="theme-settings-section">
            <h6 class="text-uppercase mb-4 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-palette"></i>Tema & Latar Belakang
            </h6>

            <div class="row g-3">
                <!-- Upload Section -->
                <div class="col-12">
                    <div class="upload-card border rounded-3 p-4 text-center" style="border: 2px dashed #d1d5db; background: #f9fafb; cursor: pointer; transition: all 0.3s;">
                        <input type="file" name="theme_background" class="form-control theme-file-input" id="theme_file" accept="image/jpeg,image/png,image/webp" style="display: none;">
                        <div class="upload-content">
                            <div class="display-6 text-muted mb-2" id="upload_icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h6 class="mb-2">Pilih Gambar Latar</h6>
                            <p class="text-muted small mb-0">JPG, PNG, WebP (Max 5MB)</p>
                            <small class="text-muted d-block mt-2">Klik atau drag gambar ke sini</small>
                        </div>
                        <div id="preview_container" style="display: none; margin-top: 1rem;">
                            <img id="preview_image" style="max-width: 100%; max-height: 250px; border-radius: 8px;" alt="Preview">
                            <p class="text-muted small mt-2" id="file_name"></p>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('theme_background')" />
                </div>

                <!-- Current Background Display -->
                @if($user->theme_bg_path)
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <strong>Gambar latar aktif</strong><br>
                                <small>Periksa opsi di bawah untuk mengubah atau menghapus</small>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contrast Mode -->
                <div class="col-md-6">
                    <label for="theme_overlay" class="form-label fw-semibold">
                        <i class="bi bi-sun"></i> Mode Kontras Otomatis
                    </label>
                    <select name="theme_overlay" id="theme_overlay" class="form-select form-select-lg theme-overlay-select">
                        <option value="auto" @selected($user->theme_overlay === 'auto' || !$user->theme_overlay)>➡️ Otomatis (disarankan)</option>
                        <option value="dark" @selected($user->theme_overlay === 'dark')>🌙 Gelap (teks putih)</option>
                        <option value="light" @selected($user->theme_overlay === 'light')>☀️ Terang (teks gelap)</option>
                    </select>
                    <small class="text-muted d-block mt-2">Overlay akan menyesuaikan dengan gambar latar Anda</small>
                </div>

                <!-- Background Size -->
                <div class="col-md-6">
                    <label for="theme_bg_size" class="form-label fw-semibold">
                        <i class="bi bi-aspect-ratio"></i> Ukuran Gambar Latar
                    </label>
                    <select name="theme_bg_size" id="theme_bg_size" class="form-select form-select-lg theme-size-select">
                        <option value="cover" @selected($user->theme_bg_size === 'cover' || !$user->theme_bg_size)>📦 Cover (penuhi layar)</option>
                        <option value="contain" @selected($user->theme_bg_size === 'contain')>📐 Contain (tampil seluruh gambar)</option>
                        <option value="auto" @selected($user->theme_bg_size === 'auto')>🔄 Auto</option>
                    </select>
                    <small class="text-muted d-block mt-2">Pilih bagaimana gambar ditampilkan di layar</small>
                </div>

                <!-- Remove Option -->
                @if($user->theme_bg_path)
                    <div class="col-12">
                        <div class="form-check form-check-lg">
                            <input class="form-check-input" type="checkbox" value="1" id="theme_remove" name="theme_remove">
                            <label class="form-check-label" for="theme_remove">
                                <i class="bi bi-trash"></i> Hapus gambar latar
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Matikan semua pengaturan tema dan kembali ke tampilan default</small>
                    </div>
                @endif
            </div>

            <!-- Preview Section -->
            <div id="theme_preview" class="mt-4 d-none">
                <hr>
                <h6 class="mb-3 fw-semibold">

 Pratinjau</h6>
                <div class="theme-preview-container rounded-3 overflow-hidden" style="background: #f1f5f9; min-height: 200px; max-height: 250px; position: relative; border: 1px solid #e2e8f0;">
                    <div id="preview_theme" style="width: 100%; height: 100%; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center;">
                        <div class="text-center">
                            <p class="mb-0 text-muted">Pratinjau akan ditampilkan di sini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 mt-4 mb-3">
            <button class="btn btn-primary btn-lg save-btn" type="submit" style="border-radius: 8px; min-width: 150px;">
                <i class="bi bi-check-circle me-2"></i><span class="btn-text">{{ __('Save') }}</span>
            </button>
        </div>

        <!-- Toast Notification -->
        <div id="save-toast" class="position-fixed bottom-0 start-50 translate-middle-x mb-4" style="z-index: 9999; display: none; transform: translateX(-50%) translateY(100px); opacity: 0; transition: all 0.4s ease;">
            <div class="toast-container">
                <div class="toast-content bg-success text-white px-4 py-3 rounded-3 shadow-lg d-flex align-items-center gap-3" style="min-width: 320px;">
                    <div class="toast-icon" style="font-size: 1.5rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">Tersimpan!</div>
                        <small class="toast-message">Pengaturan profil Anda telah diperbarui</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-3" onclick="this.closest('.position-fixed').style.display='none'"></button>
                </div>
            </div>
        </div>
    </form>
</section>

<style>
#save-toast {
    animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.320, 1);
}

@keyframes slideUp {
    0% {
        transform: translateX(-50%) translateY(100px);
        opacity: 0;
    }
    100% {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
}

.save-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.save-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}

.save-btn:active:not(:disabled) {
    transform: translateY(0);
}

.save-btn:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}

.save-btn .spinner-border {
    display: inline-block;
    vertical-align: middle;
    animation: spinner 0.8s linear infinite;
}

@keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
