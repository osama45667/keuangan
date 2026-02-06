<section>
    <header class="mb-3">
        <h6 class="mb-1 fw-bold text-uppercase">
            <i class="bi bi-person"></i> Profile Settings
        </h6>
        <p class="text-muted small mb-0">Manage your account and appearance</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div class="mb-2">
            <label for="name" class="form-label small fw-bold">Name</label>
            <input id="name" name="name" type="text" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required autofocus>
            <x-input-error class="mt-1 small" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-bold">Email Address</label>
            <input id="email" name="email" type="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}" required>
            <x-input-error class="mt-1 small" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-info alert-sm py-2 px-3 small">
                        <i class="bi bi-exclamation-circle"></i> Email not verified.
                        <button form="send-verification" class="btn btn-link btn-sm p-0 ms-1">Resend</button>
                    </div>
                </div>
            @endif
        </div>

        <hr class="my-3">

        <!-- Theme Settings Section -->
        <div class="theme-settings">
            <h6 class="text-uppercase mb-3 fw-bold small">
                <i class="bi bi-palette"></i> Theme & Background
            </h6>

            <!-- Upload Area -->
            <div class="upload-card px-3 py-4 rounded-2 mb-3 text-center" style="background: #f8fafc; border: 2px dashed #cbd5e1; cursor: pointer;">
                <input type="file" name="theme_background" class="theme-file-input" id="theme_file" accept="image/jpeg,image/png,image/webp" style="display: none;">
                
                <div id="upload_content">
                    <div class="h5 text-muted mb-1">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <h6 class="small mb-1">Upload Background Image</h6>
                    <p class="text-muted very-small">JPG, PNG, WebP • Max 5MB</p>
                </div>

                <div id="preview_container" style="display: none;">
                    <img id="preview_image" style="max-width: 100%; max-height: 120px; border-radius: 6px;">
                    <p class="text-muted very-small mt-2 mb-0" id="file_name"></p>
                </div>
            </div>
            <x-input-error class="small mb-2" :messages="$errors->get('theme_background')" />

            @if($user->theme_bg_path)
                <div class="alert alert-success alert-sm py-2 px-3 mb-3">
                    <small><i class="bi bi-check-circle"></i> <strong>Background is active</strong></small>
                </div>
            @endif

            <!-- Settings -->
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label for="theme_overlay" class="form-label very-small fw-bold">Overlay</label>
                    <select name="theme_overlay" id="theme_overlay" class="form-select form-select-sm">
                        <option value="auto" @selected($user->theme_overlay === 'auto' || !$user->theme_overlay)>Auto</option>
                        <option value="dark" @selected($user->theme_overlay === 'dark')>Dark</option>
                        <option value="light" @selected($user->theme_overlay === 'light')>Light</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="theme_bg_size" class="form-label very-small fw-bold">Size</label>
                    <select name="theme_bg_size" id="theme_bg_size" class="form-select form-select-sm">
                        <option value="cover" @selected($user->theme_bg_size === 'cover' || !$user->theme_bg_size)>Cover</option>
                        <option value="contain" @selected($user->theme_bg_size === 'contain')>Contain</option>
                        <option value="auto" @selected($user->theme_bg_size === 'auto')>Auto</option>
                    </select>
                </div>
            </div>

            @if($user->theme_bg_path)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="theme_remove" name="theme_remove" value="1">
                    <label class="form-check-label small" for="theme_remove">
                        <i class="bi bi-trash"></i> Remove background
                    </label>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-sm save-btn" style="min-width: 110px;">
                <i class="bi bi-check-circle me-1"></i> <span>Save</span>
            </button>
        </div>

        <!-- Success Toast -->
        <div id="save-toast" class="position-fixed bottom-0 start-50 translate-middle-x mb-3 d-none" style="z-index: 9999;">
            <div class="toast-content bg-success text-white px-3 py-2 rounded-2 shadow d-flex align-items-center gap-2 small">
                <i class="bi bi-check-circle-fill"></i> <span>Saved successfully!</span>
            </div>
        </div>
    </form>
</section>

<style>
/* Compact responsive adjustments */
.very-small { font-size: 0.75rem; }
.alert-sm { font-size: 0.875rem; }

.upload-card {
    transition: all 0.3s ease;
}

.upload-card:hover {
    background-color: #f1f5f9;
    border-color: #94a3b8;
}

.save-btn {
    transition: all 0.2s ease;
}

.save-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

#save-toast {
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Dark overlay mode */
.app-body.has-bg {
    color: #fff;
}

.app-body.has-bg .form-control,
.app-body.has-bg .form-select {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(255, 255, 255, 0.3);
}

.app-body.has-bg .form-control:focus,
.app-body.has-bg .form-select:focus {
    background: white;
    border-color: #2563eb;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    const uploadCard = document.querySelector('.upload-card');
    const fileInput = document.getElementById('theme_file');
    const previewContainer = document.getElementById('preview_container');
    const previewImage = document.getElementById('preview_image');
    const fileName = document.getElementById('file_name');
    const uploadContent = document.getElementById('upload_content');

    if (uploadCard && fileInput) {
        uploadCard.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadCard.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadCard.addEventListener(eventName, () => {
                uploadCard.style.background = '#f1f5f9';
                uploadCard.style.borderColor = '#2563eb';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadCard.addEventListener(eventName, () => {
                uploadCard.style.background = '';
                uploadCard.style.borderColor = '';
            });
        });

        uploadCard.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect();
        });

        fileInput.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = (e) => {
                    uploadContent.style.display = 'none';
                    previewContainer.style.display = 'block';
                    previewImage.src = e.target.result;
                    fileName.textContent = file.name;
                };

                reader.readAsDataURL(file);
            }
        }
    }

    // Form submit
    const form = document.querySelector('form[method="post"][action*="profile.update"]');
    const saveToast = document.getElementById('save-toast');

    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('.save-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';
                submitBtn.disabled = true;
            }
        });
    }

    // Show toast if redirected with success
    @if(session('status'))
        if (saveToast) {
            saveToast.classList.remove('d-none');
            setTimeout(() => saveToast.classList.add('d-none'), 3000);
        }
    @endif
});

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}
</script>
