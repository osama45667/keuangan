import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Theme Settings Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Refresh cached pages to avoid stale CSRF tokens on mobile back/forward cache
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // Mobile sidebar: use Bootstrap offcanvas when available, fallback otherwise
    const sidebarToggle = document.querySelector('[data-bs-toggle="offcanvas"][data-bs-target="#appSidebar"]');
    const sidebarEl = document.getElementById('appSidebar');
    if (sidebarToggle && sidebarEl) {
        const hasBootstrap = !!(window.bootstrap && window.bootstrap.Offcanvas);

        const toggleSidebar = (force) => {
            const shouldOpen = typeof force === 'boolean' ? force : !sidebarEl.classList.contains('show');
            sidebarEl.classList.toggle('show', shouldOpen);
            sidebarEl.style.visibility = shouldOpen ? 'visible' : 'hidden';
            document.body.classList.toggle('sidebar-open', shouldOpen);
        };

        if (hasBootstrap) {
            // Let Bootstrap handle toggle via data attributes to avoid double-toggle issues.
            sidebarEl.addEventListener('shown.bs.offcanvas', () => {
                document.body.classList.add('sidebar-open');
            });
            sidebarEl.addEventListener('hidden.bs.offcanvas', () => {
                document.body.classList.remove('sidebar-open');
            });

            // Ensure the menu closes when a link is tapped.
            sidebarEl.addEventListener('click', (e) => {
                if (e.target && e.target.closest('a')) {
                    const instance = window.bootstrap.Offcanvas.getInstance(sidebarEl);
                    if (instance) instance.hide();
                }
            });
        } else {
            // Fallback for when Bootstrap JS isn't available.
            sidebarToggle.addEventListener('click', function (e) {
                e.preventDefault();
                toggleSidebar();
            });

            sidebarEl.addEventListener('click', (e) => {
                if (e.target && e.target.closest('a')) {
                    toggleSidebar(false);
                }
            });

            document.addEventListener('click', (e) => {
                if (!document.body.classList.contains('sidebar-open')) return;
                if (sidebarEl.contains(e.target) || sidebarToggle.contains(e.target)) return;
                toggleSidebar(false);
            });
        }
    }

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
    const themeBg = null;
    
    // Form handling
    const profileForm = document.querySelector('form[action*="profile.update"]');
    const saveBtn = profileForm ? profileForm.querySelector('.save-btn') : null;

    if (uploadCard && fileInput) {
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
    }

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
            // Apply preview immediately to page background so user sees change before saving
            try {
                var selectedSize = (sizeSelect && sizeSelect.value) ? sizeSelect.value : 'cover';
                document.body.style.setProperty('--theme-bg-url', 'url(\"' + e.target.result + '\")');
                document.body.style.setProperty('--theme-bg-size', selectedSize);
            } catch (err) {
                // ignore preview application errors
            }
        };
        reader.readAsDataURL(file);
    }

    // Listen for size mode changes and update background immediately
    if (sizeSelect) {
        sizeSelect.addEventListener('change', function() {
            document.body.style.setProperty('--theme-bg-size', this.value);
            console.log('[Theme] Size mode changed to: ' + this.value);
        });
    }

    // Listen for overlay mode changes and update body class
    if (overlaySelect) {
        overlaySelect.addEventListener('change', function() {
            var body = document.querySelector('.app-body');
            if (body) {
                // Remove all overlay classes
                body.classList.remove('theme-overlay-light', 'theme-overlay-dark', 'theme-overlay-auto');
                // Add new class
                body.classList.add('theme-overlay-' + this.value);
                console.log('[Theme] Overlay mode changed to: ' + this.value);
            }
        });
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
