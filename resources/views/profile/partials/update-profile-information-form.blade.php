<section>
    <style>
        .profile-section {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .section-header {
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
            animation: slideInLeft 0.6s ease-out;
        }
        
        .section-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .form-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
            display: block;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control, .form-select {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            width: 100%;
            box-sizing: border-box;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: white;
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .theme-settings {
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%);
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
            border: 1px solid #dbeafe;
            animation: scaleIn 0.5s ease-out;
        }
        
        .theme-settings h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .upload-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .upload-card:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
        }
        
        .upload-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .upload-text h4 {
            margin: 10px 0 5px;
            color: #1e40af;
            font-weight: 600;
        }
        
        .upload-text p {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }
        
        #preview_image {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 1px solid #86efac;
            border-radius: 10px;
            color: #166534;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .settings-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-check {
            padding: 12px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .form-check:hover {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            vertical-align: middle;
        }
        
        .form-check-label {
            cursor: pointer;
            font-weight: 500;
            color: #374151;
            margin: 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        
        .save-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            min-width: 120px;
        }
        
        .save-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }
        
        .save-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .input-error {
            border-color: #ef4444 !important;
        }
        
        .error-message {
            color: #991b1b;
            font-size: 0.875rem;
            margin-top: 6px;
        }
        
        hr {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 25px 0;
        }
    </style>

    <div class="profile-section">
        <div class="section-header">
            <h2><i class="bi bi-person-circle"></i> Profile Settings</h2>
            <p>Manage your account and appearance</p>
        </div>

        <form id="profile-form" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <!-- Basic Information -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="bi bi-person"></i> Your Name
                </label>
                <input id="name" name="name" type="text" class="form-control @error('name') input-error @enderror" 
                       value="{{ old('name', $user->name) }}" required autofocus>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope"></i> Email Address
                </label>
                <input id="email" name="email" type="email" class="form-control @error('email') input-error @enderror" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-2">
                        <i class="bi bi-exclamation-circle"></i> Email not verified.
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: inline;">@csrf</form>
                        <button form="send-verification" class="btn btn-link btn-sm p-0 ms-1">Resend verification</button>
                    </div>
                @endif
            </div>

            <hr>

            <!-- Theme & Background -->
            <div class="theme-settings">
                <h3><i class="bi bi-palette2"></i> Theme & Background</h3>

                <!-- Upload Area -->
                <div class="upload-card" id="upload_card">
                    <input type="file" name="theme_background" id="theme_file" 
                           accept="image/jpeg,image/png,image/webp" style="display: none;">
                    
                    <div id="upload_content">
                        <div class="upload-icon">☁️</div>
                        <div class="upload-text">
                            <h4>Upload Background Image</h4>
                            <p>JPG, PNG, WebP • Maximum 5MB</p>
                        </div>
                    </div>

                    <div id="preview_container" style="display: none;">
                        <img id="preview_image" alt="Preview">
                        <p id="file_name" style="margin-top: 12px; color: #6b7280; font-size: 0.875rem;"></p>
                    </div>
                </div>
                @error('theme_background')
                    <div class="error-message"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror

                @if($user->theme_bg_path)
                    <div class="alert-success">
                        <i class="bi bi-check-circle"></i> <strong>✓ Background is active</strong>
                    </div>
                @endif

                <!-- Settings -->
                <div class="settings-row">
                    <div class="form-group">
                        <label for="theme_overlay" class="form-label">Overlay Mode</label>
                        <select name="theme_overlay" id="theme_overlay" class="form-select">
                            <option value="auto" @selected($user->theme_overlay === 'auto' || !$user->theme_overlay)>Auto</option>
                            <option value="dark" @selected($user->theme_overlay === 'dark')>Dark</option>
                            <option value="light" @selected($user->theme_overlay === 'light')>Light</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="theme_bg_size" class="form-label">Size Mode</label>
                        <select name="theme_bg_size" id="theme_bg_size" class="form-select">
                            <option value="cover" @selected($user->theme_bg_size === 'cover' || !$user->theme_bg_size)>Cover (Fill)</option>
                            <option value="contain" @selected($user->theme_bg_size === 'contain')>Contain (Fit)</option>
                            <option value="auto" @selected($user->theme_bg_size === 'auto')>Auto</option>
                        </select>
                    </div>
                </div>

                @if($user->theme_bg_path)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="theme_remove" name="theme_remove" value="1">
                        <label class="form-check-label" for="theme_remove">
                            <i class="bi bi-trash"></i> Remove background
                        </label>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="submit" class="save-btn" id="save-btn">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
    (function() {
        function initForm() {
            var uploadCard = document.getElementById('upload_card');
            var themeFile = document.getElementById('theme_file');
            var previewImage = document.getElementById('preview_image');
            var fileName = document.getElementById('file_name');
            var uploadContent = document.getElementById('upload_content');
            var previewContainer = document.getElementById('preview_container');
            var profileForm = document.getElementById('profile-form');
            var saveBtn = document.getElementById('save-btn');

            // Upload card click
            if (uploadCard && themeFile) {
                uploadCard.addEventListener('click', function() {
                    themeFile.click();
                });
            }

            // File upload handler
            if (themeFile) {
                themeFile.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            previewImage.src = event.target.result;
                            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                            uploadContent.style.display = 'none';
                            previewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Form submit
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    if (saveBtn) {
                        saveBtn.disabled = true;
                        saveBtn.textContent = 'Saving...';
                    }
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initForm);
        } else {
            initForm();
        }
    })();
    </script>
</section>
