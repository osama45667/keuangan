import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Theme Settings Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Safe element selection with null checks
    const fileInput = document.getElementById('theme_file');
    const uploadCard = document.getElementById('upload_card'); // Changed from class to ID
    const previewContainer = document.getElementById('preview_container');
    const previewImage = document.getElementById('preview_image');
    const fileName = document.getElementById('file_name');
    const uploadContent = document.getElementById('upload_content'); // Changed from class to ID
    const overlaySelect = document.getElementById('theme_overlay');
    const sizeSelect = document.getElementById('theme_bg_size');
    const themeRemove = document.getElementById('theme_remove');
    
    // Form handling
    const profileForm = document.querySelector('form[action*="profile.update"]');
    const saveBtn = profileForm ? profileForm.querySelector('.save-btn') : null;

    // Only proceed if upload card exists
    if (!uploadCard || !fileInput) return;

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
            if (previewImage) previewImage.src = e.target.result;
            if (fileName) fileName.textContent = `📁 ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            if (uploadContent) uploadContent.style.display = 'none';
            if (previewContainer) previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Handle remove checkbox
    if (themeRemove) {
        themeRemove.addEventListener('change', () => {
            if (themeRemove.checked) {
                if (previewContainer) previewContainer.style.display = 'none';
                if (uploadContent) uploadContent.style.display = 'block';
                fileInput.value = '';
            }
        });
    }
    
    // Form submission with animation
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            if (saveBtn) {
                saveBtn.disabled = true;
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
                
                // Let form submit naturally
            }
        });
    }
    
    function showErrorToast(message) {
        // Simple error notification
        const div = document.createElement('div');
        div.style.cssText = 'position:fixed; top:20px; right:20px; background:#ef4444; color:white; padding:16px 24px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.15); z-index:9999; animation:slideInRight 0.3s ease-out;';
        div.textContent = message;
        document.body.appendChild(div);
        
        setTimeout(() => {
            div.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => div.remove(), 300);
        }, 3500);
    }
});
