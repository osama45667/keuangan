# 🎨 Panduan Tema & Latar Belakang Keuangan

## ✅ Fitur Yang Sudah Diimplementasikan

### 1. Upload Gambar Latar
- Drag & drop support
- Validasi format (JPG, PNG, WebP)
- Max 5MB
- Preview sebelum save

### 2. Mode Kontras (Overlay)
- **Otomatis (default)**: Overlay abu-abu (disarankan)
- **Gelap**: Overlay gelap untuk background terang
- **Terang**: Overlay terang untuk background gelap

### 3. Ukuran Gambar
- **Cover**: Isi layar penuh (default)
- **Contain**: Tampilkan gambar utuh
- **Auto**: Ukuran asli gambar

### 4. Animasi Profesional
- Toast notification saat save
- Loading spinner pada button
- Slide-up animation
- Auto-hide after 4 seconds

---

## 🚀 Cara Menggunakan

### Step 1: Upload Gambar
1. Buka halaman **Profile** (`/profile`)
2. Scroll ke section "Tema & Latar Belakang"
3. Klik upload box atau drag gambar ke sana
4. Lihat preview gambar

### Step 2: Atur Setting
1. Pilih **Mode Kontras** (Otomatis/Gelap/Terang)
2. Pilih **Ukuran Gambar** (Cover/Contain/Auto)
3. Review preview di bawah

### Step 3: Simpan
1. Klik tombol **Save**
2. Lihat loading spinner
3. Tunggu toast notification "Tersimpan!"
4. Page akan refresh dengan tema baru

### Step 4: Verifikasi
Setelah page refresh:
- Background image harus terlihat di belakang konten
- Overlay gradient terlihat di atas gambar (gelap/terang)
- Konten tetap readable dengan semi-transparent background

---

## 🔍 Testing Step-by-Step

### Test 1: Static Background Test
```
Buka: http://localhost/theme-debug.html
```
✓ Jika image terlihat di background = CSS approach bekerja

### Test 2: Database Check
```
Jalankan: php check_theme.php
```
✓ Lihat output untuk verify:
- Path tersimpan di database
- URL correct
- File exists

### Test 3: Debug Page
```
Buka: http://localhost/debug/theme (login terlebih dahulu)
```
✓ Lihat:
- Background URL yang digunakan
- CSS variables (jika ada)
- Preview rendering

### Test 4: DevTools Inspection (F12)
Buka DevTools dan inspect:

1. **Inspect Body Element**
   ```
   <body class="app-body has-bg theme-overlay-dark" 
         style="background-image: url(...); ...">
   ```
   Pastikan ada:
   - ✓ Class "has-bg"
   - ✓ Inline style dengan background-image
   - ✓ background-color: #0f172a

2. **Computed Styles**
   Cari "background-image" di tab Styles:
   ```
   background-image: url('...')
   background-size: contain
   background-position: center
   background-attachment: fixed
   background-repeat: no-repeat
   ```

3. **Z-Index Stack**
   Verifikasi:
   ```
   body::before (overlay) → z-index: 5
   app-shell → z-index: 100
   app-main → z-index: 100 (rgba bgd)
   app-navbar → z-index: 101
   ```

---

## ⚠️ Jika Background Tidak Terlihat

### 1. Clear Browser Cache
```
Ctrl + Shift + Delete (Windows)
Cmd + Shift + Delete (Mac)
```
Pilih:
- ☑ Cookies and other site data
- ☑ Cached images and files

### 2. Check Network
Buka DevTools → Network tab:
```
1. Refresh page
2. Cari file gambar (theme...png)
3. Status harus 200 OK
4. Size harus > 0 KB
```

### 3. Test Different Browser
- [ ] Google Chrome
- [ ] Firefox
- [ ] Edge
- [ ] Safari (jika di Mac)

### 4. Check URL Accessibility
Buka URL gambar di browser bar:
```
http://localhost/storage/themes/a4vFxXZxINVJZ6jW22g3Fx1ZoT8sB1ULU1gAf3Z4.png
```
Harus muncul gambar, bukan error/blank

### 5. Verify PHP Logic
Jalankan:
```bash
php check_theme.php
```

Pastikan output menunjukkan:
- User punya theme_bg_path
- File exists: YES ✓
- URL correct

### 6. Check CSS Loading
DevTools → Sources tab:
```
1. Cari file: app.css (atau resources/css/app.css)
2. Status harus 200 OK
3. Content harus ada (tidak empty)
```

---

## 🔧 Cara Reset/Hapus Tema

### Via UI:
1. Buka Profile
2. Check "Hapus gambar latar"
3. Klik Save
4. Gambar akan dihapus, kembali ke default gradient

### Via Database:
```sql
UPDATE users SET 
  theme_bg_path = NULL,
  theme_overlay = 'auto',
  theme_bg_size = 'cover'
WHERE id = 1;
```

---

## 📊 Expected CSS Rendering

```
LAYER -∞: Body background-image (fixed, no-repeat)
       └─ Style: background-image: url(...)
       
LAYER 5: Body::before overlay gradient  
       └─ Style: linear-gradient(180deg, ...)
       
LAYER 100: app-shell + app-main (semi-transparent)
        └─ Background: rgba(255, 255, 255, 0.88)
        └─ Backdrop-filter: blur(8px)
        
LAYER 101: app-navbar (top)
        └─ Background: rgba(248, 250, 252, 0.95)
        └─ Backdrop-filter: blur(10px)
```

---

## 📝 Checklist Fitur

- [x] Upload gambar
- [x] Pilih kontras mode
- [x] Pilih ukuran gambar
- [x] Save & redirect
- [x] Toast notification
- [x] Loading spinner
- [x] Preview before save
- [x] Hapus tema
- [x] Mobile responsive
- [ ] Background muncul (TBD - testing required)

---

## 🎯 Langkah Next

Jika semua test di atas OK tapi background masih tidak muncul:

1. **Hubungi support** dengan screenshot:
   - DevTools Console (F12)
   - DevTools Styles computed
   - URL gambar (test bisa diakses)

2. **Provide info:**
   - Browser & version
   - Output: `php check_theme.php`
   - Screenshot error (jika ada)

---

**Created: February 5, 2026**
**Last Updated: February 5, 2026**
