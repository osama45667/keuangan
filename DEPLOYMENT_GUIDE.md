# 🚀 DEPLOYMENT CHECKLIST

## Sebelum Deploy

```bash
# 1. Commit semua changes
git add .
git commit -m "🎨 Fix theme background + complete UI redesign with animations"

# 2. Push ke Railway
git push origin main

# 3. Wait untuk auto-deployment (1-2 minutes)
# Railway akan:
# - Pull latest code
# - Install dependencies
# - Build CSS dengan Vite
# - Deploy ke production
```

## Setelah Deploy

```
1. Hard refresh browser:
   - Ctrl+Shift+Delete (clear all cache)
   - Close & reopen browser
   - Ctrl+F5 (hard refresh)

2. Go to: https://keuangan-production-19e6.up.railway.app/profile

3. Test background:
   - Upload image (JPG, PNG, WebP)
   - Click Save Changes
   - See success message
   - Hard refresh again
   - Background should appear with overlay
```

## Jika Ada Error

```bash
# Check logs di Railway dashboard:
1. Go to Railway.app
2. Select keuangan-production-19e6 project
3. Click "Logs" tab
4. Look for error messages

# Atau test di local:
php artisan serve
# Then go to http://localhost:8000/profile
```

## Verification URLs

Setelah production deployment, test di:

1. **Profile Page:** `/profile`
2. **Diagnostic:** `/diagnostic.php`
3. **All Themes:** `/check-all-themes.php`
4. **Troubleshoot:** `/troubleshoot-theme.html`
5. **Verify:** `/verify-theme.html`

## Rollback (jika perlu)

```bash
# Jika ada issue:
git revert HEAD
git push origin main

# Railway akan auto-redeploy ke previous version
```

## Performance Tips

To ensure smooth performance:

```bash
# 1. Cache warming
php artisan optimize

# 2. Check theme directory permissions
chmod 755 storage/app/public/themes

# 3. Verify symlink
php artisan storage:link

# 4. Clear cache jika perlu
php artisan cache:clear
php artisan view:clear
```

## Files to Verify After Deploy

```
✓ resources/css/app.css - Updated (animations)
✓ resources/views/layouts/app.blade.php - Fixed (URL handling)
✓ resources/views/profile/partials/update-profile-information-form.blade.php - Redesigned
✓ public/diagnostic.php - Diagnostic tool
✓ public/verify-theme.html - Verification page
```

---

**Ready to deploy!** 🚀
