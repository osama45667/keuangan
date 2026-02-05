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
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau WebP');
            fileInput.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB');
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
});
