# Session Management Documentation

## Overview
Session management untuk admin panel telah diperbaiki untuk memastikan:
- ✓ User sudah login tidak bisa akses login/register page lagi
- ✓ Session persist antara page reload
- ✓ Logout berfungsi dengan baik
- ✓ Cookie session ter-konfigurasi dengan baik

## Implementation Details

### Session Configuration (`admin/auth/session.php`)
```php
// Session di-configure dengan settings:
- session.cookie_path: '/'          (Accessible dari semua halaman)
- session.cookie_lifetime: 86400    (24 jam)
- session.gc_maxlifetime: 86400     (24 jam)
```

### Session Functions

#### 1. `isLoggedIn()`
Mengecek apakah admin sudah login dengan verify `$_SESSION['admin_id']`

```php
if (isLoggedIn()) {
    // User sudah login
    $admin_id = $_SESSION['admin_id'];
    $username = $_SESSION['admin_username'];
}
```

#### 2. `setLoginSession($admin_id, $username)`
Set session setelah user berhasil login/register

```php
// Dipanggil dari login.php dan register.php
setLoginSession($user['id'], $user['username']);
```

#### 3. `requireLogin()`
Redirect ke login jika belum login (digunakan di `dashboard.php`)

```php
// Dari dashboard.php:
requireLogin();  // Jika belum login, redirect ke login.php
```

#### 4. `redirectIfLoggedIn()`
Redirect ke dashboard jika sudah login (digunakan di `login.php` dan `register.php`)

```php
// Dari login.php dan register.php:
redirectIfLoggedIn();  // Jika sudah login, redirect ke dashboard.php
```

#### 5. `destroySession()`
Destroy session saat logout

```php
// Dari logout.php:
destroySession();  // Clear session dan cookies
```

## File Flow

```
LOGIN FLOW:
┌─────────────────┐
│  login.php      │
│  (user input)   │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│ redirectIfLoggedIn()     │ ← Check: jika sudah login, redirect ke dashboard
│ (if sudah login → skip)  │
└────────┬────────────────┘
         │
         ▼ (form POST)
┌─────────────────────────┐
│ Query database          │
│ password_verify()       │
└────────┬────────────────┘
         │
         ├─ Jika benar ──────┐
         │                   │
         ▼ Jika salah        ▼
    $error        setLoginSession()
                         │
                         ▼
                  header('Location: dashboard.php')
                         │
                         ▼
                  ┌─────────────────────────┐
                  │  dashboard.php          │
                  │  requireLogin()         │
                  │  (session aktif ✓)      │
                  └─────────────────────────┘

LOGOUT FLOW:
┌──────────────────┐
│  logout.php      │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ destroySession() │
└────────┬─────────┘
         │
         ▼
  header('Location: login.php')
         │
         ▼
┌──────────────────────────┐
│  login.php               │
│  redirectIfLoggedIn()    │
│  (session kosong ✓)      │
└──────────────────────────┘
```

## Testing

### Test 1: Session Persistence
1. Buka `admin/auth/test-session.php?test_login=1`
2. Output akan show `isLoggedIn() = FALSE` karena session baru
3. Refresh halaman
4. Output akan show `isLoggedIn() = TRUE` - session sudah persist!

### Test 2: Login Block
1. Login dengan username/password
2. Coba akses `login.php` langsung - harus redirect ke `dashboard.php`
3. Coba akses `register.php` langsung - harus redirect ke `dashboard.php`

### Test 3: Logout
1. Dari dashboard, click tombol "Logout"
2. Session harus ter-destroy
3. Sekarang bisa akses `login.php` dan `register.php` lagi

### Test 4: Dashboard Protection
1. Logout terlebih dahulu
2. Coba akses `dashboard.php` langsung - harus redirect ke `login.php`

## Session Data Structure

```php
// Saat login berhasil:
$_SESSION = [
    'admin_id' => 1,                    // ID dari tb_admin
    'admin_username' => 'testadmin',    // Username
    'login_time' => 1234567890          // Unix timestamp saat login
]

// Saat logout:
$_SESSION = []  // Kosong
```

## Troubleshooting

### Masalah: Session tidak persist setelah reload
**Solusi:**
- Pastikan `session_start()` dipanggil di awal file sebelum ada output
- Pastikan tidak ada `header()` sebelum `session_start()`
- Check browser cookie: DevTools → Application → Cookies → cari PHPSESSID

### Masalah: Bisa akses login.php meskipun sudah login
**Solusi:**
- Verify bahwa `redirectIfLoggedIn()` dipanggil di line pertama `login.php` setelah require
- Check output buffering, pastikan tidak ada echo sebelum header redirect
- Test di `test-session.php` untuk verify session data

### Masalah: Logout tidak bekerja
**Solusi:**
- Verify `logout.php` memiliki `require_once 'session.php'`
- Check apakah `destroySession()` ter-call dengan benar
- Browser cache mungkin issue, clear cache dan cookies browser

## Best Practices

1. **Selalu panggil `session_start()` di awal** sebelum require session.php
2. **Gunakan `requireLogin()` di semua halaman admin** yang butuh authentication
3. **Gunakan `redirectIfLoggedIn()` di login/register page** untuk prevent double login
4. **Sanitize session data** dengan `htmlspecialchars()` saat display di HTML
5. **Implement session timeout** jika diinginkan (optional enhancement)

## Future Enhancements

- [ ] Session timeout handler (30 min inactivity)
- [ ] Multi-device login (prevent simultaneous sessions)
- [ ] Session activity logging
- [ ] Remember me functionality
- [ ] 2FA (Two Factor Authentication)

---

Last Updated: 2024
Documentation for: Jasa Dekorasi Pernikahan Admin Panel
