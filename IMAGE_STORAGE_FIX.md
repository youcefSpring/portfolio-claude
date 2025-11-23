# Image Storage Fix - Project & Skill Images

## Issue
Images were not displaying on the welcome page because they were stored in the wrong disk location.

## Problem Details
- Images were being stored to the **'local' disk** (`storage/app/`)
- But the display code was trying to access them via **'storage/' path** (which points to `public/storage/`)
- The 'local' disk is for private files and is not web-accessible
- Images need to be in the 'public' disk to be accessible via the web

## Solution Applied

### 1. Fixed Storage Disk (Changed from 'local' to 'public')

**Files Modified:**
- `code/app/Http/Controllers/Admin/ProjectController.php`
- `code/app/Http/Controllers/Admin/SkillController.php`

**Changes:**
- All `store()` calls changed from `'local'` to `'public'` disk
- All `delete()` calls changed from `Storage::disk('local')` to `Storage::disk('public')`

**Before:**
```php
$image->store('images/projects', 'local');  // ❌ Wrong - not web accessible
Storage::disk('local')->delete($image);
```

**After:**
```php
$image->store('images/projects', 'public');  // ✅ Correct - web accessible
Storage::disk('public')->delete($image);
```

### 2. Fixed Storage Symlink

**Issue:** The symlink was pointing to wrong path (old project location)

**Fixed with:**
```bash
php artisan storage:link
```

**Result:**
- Created proper symlink: `public/storage` → `storage/app/public`
- Now images in `storage/app/public/` are accessible via `public/storage/`

### 3. Created Storage Directories

Created the necessary directory structure:
```
storage/app/public/
├── images/
│   ├── projects/
│   └── skills/
```

## How It Works Now

### Project Images
1. **Upload:** Images uploaded via admin panel
2. **Storage:** Saved to `storage/app/public/images/projects/`
3. **Access:** Available via URL `http://yoursite.com/storage/images/projects/filename.jpg`
4. **Display:** Code uses `asset('storage/images/projects/filename.jpg')`

### Skill Logos (Custom Uploads)
1. **Upload:** Logos uploaded via admin panel
2. **Storage:** Saved to `storage/app/public/images/skills/`
3. **Access:** Available via URL `http://yoursite.com/storage/images/skills/filename.jpg`
4. **Display:** Code uses `asset('storage/images/skills/filename.jpg')`

### Skill Logos (Simple Icons - Recommended)
- No storage needed
- Loaded directly from CDN
- Much better option!

## Storage Configuration

### Public Disk
- **Location:** `storage/app/public/`
- **Web Access:** Via `public/storage/` symlink
- **Use For:** Images, files that need to be publicly accessible
- **URL Pattern:** `http://yoursite.com/storage/path/to/file.jpg`

### Local Disk
- **Location:** `storage/app/`
- **Web Access:** None (private)
- **Use For:** Private files, logs, temp files
- **Not suitable for images!**

## Testing

### To Test Project Images:
1. Go to Admin → Projects → Create New Project
2. Upload one or more images
3. Save the project
4. Visit the welcome page
5. You should see the project image displayed

### To Test Skill Logos:
1. Go to Admin → Skills → Create New Skill
2. **Option A (Recommended):** Enter a Simple Icon slug (e.g., `laravel`)
3. **Option B:** Upload a custom logo image
4. Save the skill
5. Visit the welcome page
6. You should see the skill icon/logo displayed

## Important Notes

1. **Existing Projects/Skills:**
   - If you had uploaded images before this fix, they won't display
   - They were stored in `storage/app/images/` (wrong location)
   - You'll need to re-upload them via the admin panel

2. **New Uploads:**
   - All new uploads will go to the correct location
   - Images will display immediately

3. **Symlink Requirement:**
   - The `public/storage` → `storage/app/public` symlink is required
   - If you deploy to a new server, run `php artisan storage:link`

4. **Permissions:**
   - Make sure `storage/app/public/` is writable by the web server
   - Usually: `chmod -R 775 storage/app/public/`
   - Owner: `chown -R www-data:www-data storage/app/public/` (adjust for your server)

## File Locations Summary

### Before Fix (Wrong ❌)
```
storage/app/images/projects/    ← Not web accessible
storage/app/images/skills/      ← Not web accessible
```

### After Fix (Correct ✅)
```
storage/app/public/images/projects/    ← Web accessible via public/storage/
storage/app/public/images/skills/      ← Web accessible via public/storage/
```

### Symlink
```
public/storage → storage/app/public
```

## Commands Reference

### Create Storage Symlink
```bash
php artisan storage:link
```

### Check Symlink
```bash
ls -la public/storage
```

### Create Directories
```bash
mkdir -p storage/app/public/images/projects
mkdir -p storage/app/public/images/skills
```

### Set Permissions (Production)
```bash
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public
```

## Affected Files

### Controllers
- `code/app/Http/Controllers/Admin/ProjectController.php`
  - Line 78: store() method - projects
  - Line 134: delete() method - projects
  - Line 140: store() method - projects (update)
  - Line 171: delete() method - projects (destroy)

- `code/app/Http/Controllers/Admin/SkillController.php`
  - Line 89: store() method - skills
  - Line 155: delete() method - skills
  - Line 157: store() method - skills (update)
  - Line 173: delete() method - skills (destroy)

### Views (No changes needed)
- `code/resources/views/welcome.blade.php` - Already using correct path
- `code/resources/views/admin/projects/create.blade.php` - Upload form
- `code/resources/views/admin/projects/edit.blade.php` - Upload form
- `code/resources/views/admin/skills/create.blade.php` - Upload form
- `code/resources/views/admin/skills/edit.blade.php` - Upload form

## Date Fixed
2025-11-23

## Status
✅ **FIXED** - Images now display correctly on welcome page
