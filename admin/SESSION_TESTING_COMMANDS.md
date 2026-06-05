# Session Management - Testing Commands Reference

## Quick Test URLs

### 1. Login Page
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/login.php
```

### 2. Register Page
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/register.php
```

### 3. Dashboard Page
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/dashboard.php
```

### 4. Logout
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/logout.php
```

### 5. Session Debug Tool
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/test-session.php
```

### 6. Session Debug with Test Login
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/test-session.php?test_login=1
```

---

## Browser Developer Tools Checks

### Check Session Cookie

**Chrome/Edge/Firefox:**
1. Open DevTools: `F12`
2. Go to: **Application** tab (Chrome) or **Storage** tab (Firefox)
3. Left sidebar: **Cookies**
4. Select domain: `localhost`
5. Look for: **PHPSESSID** cookie

**What to check:**
- ✅ **Before login:** PHPSESSID should NOT exist
- ✅ **After login:** PHPSESSID should exist
- ✅ **After logout:** PHPSESSID should be gone or expired

---

### Check Network Requests

**Steps:**
1. Open DevTools: `F12`
2. Go to: **Network** tab
3. Perform action (login, navigate, logout)
4. Look at requests to see cookies

**What to check:**
- ✅ **Set-Cookie header:** After login
- ✅ **Cookie header:** In every request when logged in
- ✅ **Request headers:** Should contain `Cookie: PHPSESSID=...`

---

### View Session Data (PHP)

**Using debug page:**
```
Visit: http://localhost/.../admin/auth/test-session.php
Look for:
- session_status() should be 2 (active)
- isLoggedIn() should be FALSE (before login)
- After login: Should show admin_id and admin_username
```

---

## Test Case Commands

### Test Case 1: Fresh Login

**Steps:**
```bash
# 1. Clear all browser cookies and data
#    DevTools → Application → Clear Storage

# 2. Visit login page
visit: http://localhost/.../admin/auth/login.php

# 3. Check DevTools - Cookies tab
#    Should NOT have PHPSESSID

# 4. Enter credentials and submit form
username: admin
password: password

# 5. Should redirect to dashboard
#    Check URL in address bar

# 6. Check DevTools - Cookies tab
#    Should NOW have PHPSESSID
```

---

### Test Case 2: Protected Login Page

**Steps:**
```bash
# Prerequisites: Must be logged in from Test Case 1

# 1. Try to visit login page directly
visit: http://localhost/.../admin/auth/login.php

# Expected: Should redirect to dashboard
#           Login form should NOT appear

# 2. Check DevTools - Console
#    No errors should appear
```

---

### Test Case 3: Protected Register Page

**Steps:**
```bash
# Prerequisites: Must be logged in

# 1. Try to visit register page directly
visit: http://localhost/.../admin/auth/register.php

# Expected: Should redirect to dashboard
#           Register form should NOT appear
```

---

### Test Case 4: Dashboard Protection

**Steps:**
```bash
# Prerequisites: Must NOT be logged in
#               Clear cookies first!

# 1. Try to visit dashboard directly
visit: http://localhost/.../admin/dashboard.php

# Expected: Should redirect to login page
#           Dashboard content should NOT appear

# 2. Check address bar
#    Should show login.php URL
```

---

### Test Case 5: Session Persistence

**Steps:**
```bash
# Prerequisites: Must be logged in

# 1. Visit dashboard
visit: http://localhost/.../admin/dashboard.php

# 2. Check: Sidebar shows username ✓

# 3. Refresh page (F5 or Ctrl+R)
#    Username should still appear

# 4. Navigate to different page
#    Click "Kategori" menu item

# 5. Refresh that page
#    Should still be logged in

# Expected: Session persists across all navigations
```

---

### Test Case 6: Logout Function

**Steps:**
```bash
# Prerequisites: Must be logged in

# 1. Click "Logout" button in sidebar
click: Logout button (bottom of sidebar)

# 2. Should redirect to login page
#    Check URL shows login.php

# 3. Check DevTools - Cookies tab
#    PHPSESSID should be GONE

# 4. Try to access dashboard directly
visit: http://localhost/.../admin/dashboard.php

# Expected: Should redirect to login page
#           Cannot go back to dashboard
```

---

## Debug Commands (Browser Console)

### Check Cookie Value

```javascript
// In browser console (F12 → Console tab)
// Get PHPSESSID value
document.cookie

// Output example:
// "PHPSESSID=abc123def456ghi789"
```

### Check Session Status

```javascript
// Check if PHPSESSID exists
function hasSessionCookie() {
    return document.cookie.includes('PHPSESSID');
}

hasSessionCookie()
// Returns: true (logged in) or false (not logged in)
```

---

## MySQL Database Checks

### View Admin Users

```sql
-- Check if admin user exists
SELECT id, username FROM tb_admin;

-- Expected output:
-- | 1 | admin      |
-- | 2 | testadmin  |
```

### Check Admin Table Structure

```sql
-- View tb_admin table schema
DESCRIBE tb_admin;

-- Expected columns:
-- | id       | int(11)  | NOT NULL | AUTO_INCREMENT |
-- | username | varchar  | NOT NULL | UNIQUE         |
-- | password | varchar  | NOT NULL |                |
```

### Test Login Query

```sql
-- Find admin by username
SELECT id, username, password FROM tb_admin 
WHERE username = 'admin';

-- Check password (bcrypt hash)
-- Password: $2y$10$...
```

---

## Server-Side Debugging

### View Session Files

```bash
# Location of session files (Linux/Mac)
/tmp/sess_*

# Or set custom session path in PHP
php -i | grep session.save_path
```

### Check PHP Error Log

```bash
# Windows (XAMPP):
C:/xampp/apache/logs/error.log
C:/xampp/php/logs/php_error.log

# Linux:
/var/log/php.log
/var/log/apache2/error.log
```

---

## Automated Testing Script (for future)

### Pseudo-Code
```php
<?php
// test-session-automated.php

class SessionTest {
    function testLoginRedirect() {
        // 1. Clear session
        // 2. Visit login
        // 3. Login with credentials
        // 4. Check redirect to dashboard
        // 5. Assert: location = dashboard.php
    }
    
    function testLoginBlockage() {
        // 1. Login first
        // 2. Visit login page
        // 3. Assert: redirect to dashboard
        // 4. Assert: no login form in response
    }
    
    function testDashboardProtection() {
        // 1. Clear session
        // 2. Visit dashboard
        // 3. Assert: redirect to login
    }
    
    function testLogout() {
        // 1. Login first
        // 2. Click logout
        // 3. Check session empty
        // 4. Check cookie gone
    }
}
?>
```

---

## Troubleshooting Commands

### If Can't Login

```bash
# 1. Check database connection
# In dashboard.php, add debug:
var_dump($conn);

# 2. Check admin user exists
# Use MySQL command above

# 3. Check password hashing
# Verify password_verify() works:
php -r "echo password_verify('password', '\$2y\$10\$...');"
```

### If Session Doesn't Persist

```bash
# 1. Check session storage
php -i | grep session.save_path

# 2. Check file permissions (should be writable)
ls -la /tmp/sess_*

# 3. Check session.ini settings
php -i | grep -i session

# 4. Clear browser cache completely
# DevTools → Application → Clear Storage
```

### If Can Still Access Login After Login

```bash
# 1. Hard refresh browser (Ctrl+Shift+R)

# 2. Clear cookies completely
# DevTools → Application → Cookies → Clear All

# 3. Close browser completely and reopen

# 4. Test in incognito/private mode

# 5. Check redirectIfLoggedIn() is called
# Add debug in login.php:
// echo "redirectIfLoggedIn called"; exit;
```

---

## Performance Check

### Session Query Time

```bash
# Check how long session takes
php -r "
\$start = microtime(true);
session_start();
\$end = microtime(true);
echo 'Session start time: ' . (\$end - \$start) . ' seconds';
"

# Expected: < 0.001 seconds
```

### Database Query Time

```sql
-- Check authentication query speed
SELECT id, username, password FROM tb_admin WHERE username = 'admin';

-- Should be fast (< 1ms)
-- EXPLAIN to see if using index
EXPLAIN SELECT id, username, password FROM tb_admin WHERE username = 'admin';
```

---

## Load Testing (Future Reference)

### Test Multiple Sessions

```bash
# Using Apache Bench
ab -n 100 -c 10 http://localhost/.../admin/dashboard.php

# Using wrk
wrk -t4 -c100 -d30s http://localhost/.../admin/dashboard.php

# Should handle 100+ concurrent sessions
```

---

## Reference Documentation

| Document | Purpose |
|----------|---------|
| README_SESSION_MANAGEMENT.md | Comprehensive guide |
| SESSION_FIX_VERIFICATION.md | Test procedures |
| QUICK_START_SESSION.md | Quick reference |
| SESSION_MANAGEMENT_FLOW.md | Flow diagrams |
| test-session.php | Debug tool |

---

## Summary: What to Test

### ✅ Critical Tests (Do These First)
1. [ ] Login works
2. [ ] Can't access login after login
3. [ ] Can't access register after login
4. [ ] Can access dashboard when logged in
5. [ ] Logout works

### ✅ Extended Tests (Verify Robustness)
1. [ ] Dashboard protected when not logged in
2. [ ] Session persists after refresh
3. [ ] Session persists across pages
4. [ ] Multiple pages work
5. [ ] Navigation menu works

### ✅ Edge Cases (Advanced Testing)
1. [ ] Can't go back after logout
2. [ ] Incognito mode works
3. [ ] Different browsers work
4. [ ] Mobile browser works
5. [ ] Cookie storage works

---

**Last Updated:** 2024
**Quick Reference:** Yes
**For:** QA Testers & Developers
**Status:** Ready for Testing
