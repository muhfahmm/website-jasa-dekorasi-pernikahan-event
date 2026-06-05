# Quick Start - Session Management

## TL;DR (Too Long; Didn't Read)

### What Was Fixed?
Session management for admin panel sekarang bekerja dengan benar:
- **Before:** Bisa akses login/register setelah login ❌
- **After:** Tidak bisa akses login/register setelah login ✅

### Files Modified
1. `admin/auth/session.php` - Enhanced configuration
2. `admin/auth/login.php` - Fixed redirect path

### How to Test

#### Test 1: Quick Verification
```
1. Clear browser cookies
2. Go to: http://localhost/.../admin/auth/login.php
3. Login with username/password
4. Try to go to login.php again
   → Should redirect to dashboard (NOT show login form)
5. ✅ If yes: Session is working!
```

#### Test 2: Dashboard Protection
```
1. Clear browser cookies
2. Try to access: http://localhost/.../admin/dashboard.php
3. Should redirect to login.php
4. ✅ If yes: Dashboard is protected!
```

#### Test 3: Logout
```
1. Login first
2. Click "Logout" button
3. Try to access dashboard
   → Should redirect to login.php
4. ✅ If yes: Logout works!
```

---

## Files Changed

### session.php
**Added:**
- Session cookie configuration (path, lifetime)
- More robust session startup

**Why:**
- Ensures session persists across page loads
- Cookie accessible from all pages

### login.php
**Changed:**
- Redirect from `../dashboard.php` to `dashboard.php`

**Why:**
- Consistent with other redirects
- Simpler and more reliable

---

## How It Works (Simple Explanation)

### Login Process
```
User fills form
    ↓
Check password
    ↓
Login successful?
    ├─ Yes: Save to session + cookie
    │        Redirect to dashboard
    └─ No: Show error
    
Next time user visits:
    ↓
Browser sends PHPSESSID cookie
    ↓
Server loads session
    ↓
Logged in!
```

### Logout Process
```
User clicks logout
    ↓
Delete session data
    ↓
Delete PHPSESSID cookie
    ↓
Redirect to login
    ↓
Now user is logged out
```

---

## Key Functions

### isLoggedIn()
Check if user is logged in
```php
if (isLoggedIn()) {
    // User is logged in
}
```

### setLoginSession($id, $username)
Save login info to session
```php
setLoginSession(1, 'admin');  // Called after login
```

### requireLogin()
Block page if not logged in
```php
requireLogin();  // At top of admin pages
// If not logged in → redirect to login.php
```

### redirectIfLoggedIn()
Block page if already logged in
```php
redirectIfLoggedIn();  // At top of login.php
// If already logged in → redirect to dashboard.php
```

### destroySession()
Delete all session data (logout)
```php
destroySession();  // Called in logout.php
```

---

## Common Issues & Fixes

| Issue | Solution |
|-------|----------|
| Can still access login after login | Clear cookies, refresh |
| Session lost after refresh | Check PHPSESSID cookie exists |
| Logout doesn't work | Click logout again, clear cookies |
| Can't login | Check database has admin user |
| Redirects not working | Check file paths are correct |

---

## File Locations

```
admin/
├── auth/
│   ├── session.php           ← Session management
│   ├── login.php             ← Login page
│   ├── register.php          ← Register page
│   ├── logout.php            ← Logout page
│   └── test-session.php      ← Debug page
├── dashboard.php             ← Admin dashboard
└── includes/
    └── sidebar.php           ← Sidebar (has logout button)
```

---

## Testing Checklist

- [ ] Login works
- [ ] Can't access login after login
- [ ] Can't access register after login
- [ ] Can access dashboard when logged in
- [ ] Dashboard protected when not logged in
- [ ] Logout works
- [ ] Session persists after refresh
- [ ] Can't go back after logout

---

## Need Help?

### For Debugging
Visit: `admin/auth/test-session.php`
Shows current session state

### For Details
Read: `admin/README_SESSION_MANAGEMENT.md`
Full documentation with examples

### For Verification Steps
Read: `admin/SESSION_FIX_VERIFICATION.md`
Complete testing procedures

---

**Status:** ✅ Ready to use
**Priority:** HIGH - Security feature
**Questions?** Check the documentation files above!
