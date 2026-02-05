import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Theme Settings Functionality
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('theme_file');
    const uploadCard = document.querySelector('.upload-card');
    const previewContainer = document.getElementById('preview_container');
    const previewImage = document.getElementById('preview_image');
    const fileName = document.getElementById('file_name');
    const uploadContent = document.querySelector('.upload-content');
    const themePreview = document.getElementById('theme_preview');
    const previewTheme = document.getElementById('preview_theme');
    const overlaySelect = document.getElementById('theme_overlay');
    const sizeSelect = document.getElementById('theme_bg_size');
    const themeRemove = document.getElementById('theme_remove');
    
    // Form handling
    const profileForm = document.querySelector('form[action*="profile.update"]');
    const saveBtn = document.querySelector('.save-btn');
    const saveToast = document.getElementById('save-toast');

    if (!uploadCard) return;

    // Click to upload
    uploadCard.addEventListener('click', () => fileInput.click());

    // File input change
    fileInput.addEventListener('change', handleFileSelect);

    // Drag and drop
    uploadCard.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadCard.classList.add('drag-over');
    });

    uploadCard.addEventListener('dragleave', () => {
        uploadCard.classList.remove('drag-over');
    });

    uploadCard.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadCard.classList.remove('drag-over');
        fileInput.files = e.dataTransfer.files;
        handleFileSelect();
    });

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (!file) return;

        // Validate file
        const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showErrorToast('Format file tidak didukung. Gunakan JPG, PNG, atau WebP');
            fileInput.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showErrorToast('Ukuran file terlalu besar. Maksimal 5MB');
            fileInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            fileName.textContent = `📁 ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            uploadContent.style.display = 'none';
            previewContainer.style.display = 'block';
            themePreview.classList.remove('d-none');

            // Update preview
            updateThemePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    function updateThemePreview(imageSrc) {
        const bgSize = sizeSelect.value || 'cover';
        const overlay = overlaySelect.value || 'auto';
        
        let overlayGradient;
        if (overlay === 'light') {
            overlayGradient = 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(248,250,252,0.85))';
        } else if (overlay === 'dark') {
            overlayGradient = 'linear-gradient(180deg, rgba(2,6,23,0.55), rgba(2,6,23,0.70))';
        } else {
            overlayGradient = 'linear-gradient(180deg, rgba(2,6,23,0.35), rgba(2,6,23,0.55))';
        }

        previewTheme.style.backgroundImage = `${overlayGradient}, url('${imageSrc}')`;
        previewTheme.style.backgroundSize = bgSize;
        previewTheme.style.backgroundPosition = 'center';
        previewTheme.innerHTML = `<div class="text-center" style="color: ${overlay === 'light' ? '#0f172a' : '#f8fafc'}"><p class="mb-0 fw-semibold">✓ Pratinjau aktif</p></div>`;
    }

    // Update preview on dropdown change
    if (overlaySelect) {
        overlaySelect.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                const reader = new FileReader();
                reader.onload = (e) => updateThemePreview(e.target.result);
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    }

    if (sizeSelect) {
        sizeSelect.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                const reader = new FileReader();
                reader.onload = (e) => updateThemePreview(e.target.result);
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    }

    // Handle remove checkbox
    if (themeRemove) {
        themeRemove.addEventListener('change', () => {
            if (themeRemove.checked) {
                previewContainer.style.display = 'none';
                uploadContent.style.display = 'block';
                themePreview.classList.add('d-none');
                fileInput.value = '';
            }
        });
    }
    
    // Form submission with animation
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            // Only prevent if there are no files and no theme_remove checked
            const hasFile = fileInput && fileInput.files.length > 0;
            const removeChecked = themeRemove && themeRemove.checked;
            
            if (saveBtn && (hasFile || removeChecked)) {
                // Disable button and show loading state
                saveBtn.disabled = true;
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><span class="btn-text">Menyimpan...</span>';
                
                // Reset after form submit
                setTimeout(() => {
                    // Don't reset here - page will refresh after redirect
                }, 100);
            }
        });
        
        // Handle form submission with data validation
        const formElement = profileForm;
        formElement.addEventListener('submit', function(e) {
            // Let the form submit naturally for full page refresh
        });
    }
    
    // Show success toast if there's a session status
    if (document.body.innerHTML.includes("profile-updated")) {
        setTimeout(() => {
            if (saveToast) {
                saveToast.style.display = 'block';
                setTimeout(() => {
                    saveToast.style.opacity = '1';
                }, 10);
                
                // Hide after 4 seconds
                setTimeout(() => {
                    saveToast.style.opacity = '0';
                    setTimeout(() => {
                        saveToast.style.display = 'none';
                    }, 400);
                }, 4000);
            }
        }, 500);
    }
    
    function showErrorToast(message) {
        if (!saveToast) return;
        
        const errorToast = saveToast.cloneNode(true);
        errorToast.innerHTML = `
            <div class="toast-container">
                <div class="toast-content bg-danger text-white px-4 py-3 rounded-3 shadow-lg d-flex align-items-center gap-3" style="min-width: 320px;">
                    <div class="toast-icon" style="font-size: 1.5rem;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">Terjadi Kesalahan</div>
                        <small class="toast-message">${message}</small>
                    </div>
                </div>
            </div>
        `;
        
        errorToast.style.display = 'block';
        errorToast.style.opacity = '1';
        saveToast.parentNode.insertBefore(errorToast, saveToast);
        
        setTimeout(() => {
            errorToast.style.opacity = '0';
            setTimeout(() => {
                errorToast.remove();
            }, 400);
        }, 3500);
    }
});
