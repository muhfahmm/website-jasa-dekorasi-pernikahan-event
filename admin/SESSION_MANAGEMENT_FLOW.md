# Session Management Flow Diagram

## System Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                    ADMIN PANEL SESSION FLOW                       │
└──────────────────────────────────────────────────────────────────┘

DATABASE (tb_admin)
├── id
├── username
└── password (hashed)

PHP SESSION ($_SESSION)
├── admin_id          (user ID from db)
├── admin_username    (username from db)
└── login_time        (Unix timestamp)

BROWSER COOKIE
└── PHPSESSID         (unique session identifier)
```

---

## Flow Diagram 1: First Time User

```
┌─────────────────────────────────────────┐
│ User opens login.php (first time)      │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ redirectIfLoggedIn() is called          │
│ isLoggedIn() checks $_SESSION           │
└──────────┬──────────────────────────────┘
           │
           ├─ Session exists? ──NO──┐
           │                        │
           └─ Session exists? ──YES─┤
                                    │
                  (Case: First time) │
                                    ▼
                    ┌────────────────────────────┐
                    │ Show Login Form             │
                    │ admin_id not in session ✓  │
                    └────────────────────────────┘
                                    │
                                    ▼
                    ┌────────────────────────────┐
                    │ User fills form & submits  │
                    │ POST /login.php            │
                    └────────┬───────────────────┘
                             │
                             ▼
                    ┌────────────────────────────┐
                    │ Verify credentials        │
                    │ ├─ Check username exists  │
                    │ └─ Verify password       │
                    └────────┬───────────────────┘
                             │
                    ┌────────┴────────┐
                    │                 │
                    ▼                 ▼
        ✗ Invalid        ✓ Valid
        Credentials      Credentials
         │               │
         ▼               ▼
    Show Error    setLoginSession()
                  ├─ $_SESSION['admin_id'] = 1
                  ├─ $_SESSION['admin_username'] = 'admin'
                  └─ $_SESSION['login_time'] = time()
                        │
                        ▼
                  header('Location: dashboard.php')
                        │
                        ▼
                  ┌────────────────────────────┐
                  │ Browser sends request      │
                  │ + PHPSESSID cookie         │
                  └────────┬───────────────────┘
                           │
                           ▼
                  ┌────────────────────────────┐
                  │ Server receives cookie     │
                  │ session_start() loads data │
                  │ admin_id found in session  │
                  └────────┬───────────────────┘
                           │
                           ▼
                  ┌────────────────────────────┐
                  │ DASHBOARD LOADS ✓          │
                  │ User sees:                 │
                  │ ├─ Sidebar                 │
                  │ ├─ Menu items              │
                  │ ├─ Dashboard content       │
                  │ └─ Username in sidebar     │
                  └────────────────────────────┘
```

---

## Flow Diagram 2: Already Logged In User

```
┌─────────────────────────────────────────┐
│ User tries to go to login.php again     │
│ (PHPSESSID cookie already exists)       │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ Browser sends PHPSESSID cookie          │
│ Server loads session data               │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ redirectIfLoggedIn() is called          │
│ isLoggedIn() = true ✓                   │
│ (admin_id found in $_SESSION)           │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ header('Location: ../dashboard.php')    │
│ exit() - Stop execution                 │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ LOGIN FORM NEVER SHOWN ✓                │
│ User redirected to dashboard            │
│ No access to login.php or register.php  │
└─────────────────────────────────────────┘
```

---

## Flow Diagram 3: Logout

```
┌─────────────────────────────────────────┐
│ User is logged in (in dashboard)        │
│ Click "Logout" button                   │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ Link: href="auth/logout.php"            │
│ Browser requests logout.php             │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ logout.php executes                     │
│ require_once 'session.php'              │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ destroySession() is called              │
│ ├─ session_unset()                      │
│ │  (Clear $_SESSION data)               │
│ ├─ session_destroy()                    │
│ │  (Delete session file)                │
│ └─ setcookie(PHPSESSID, '', expired)    │
│    (Delete browser cookie)              │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ header('Location: login.php')           │
│ Browser navigates to login              │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ login.php loads                         │
│ No PHPSESSID cookie sent (was deleted)  │
│ $_SESSION is empty                      │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ LOGIN FORM SHOWN ✓                      │
│ User must login again                   │
└─────────────────────────────────────────┘
```

---

## Flow Diagram 4: Dashboard Protection

```
┌─────────────────────────────────────────┐
│ User tries to access dashboard.php      │
│ without login (no PHPSESSID cookie)     │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ requireLogin() is called at top          │
│ isLoggedIn() checks $_SESSION           │
│ admin_id NOT found                      │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ header('Location: ../auth/login.php')   │
│ exit() - Stop execution                 │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ DASHBOARD NEVER LOADS ✓                 │
│ User redirected to login                │
│ No access to protected content          │
└─────────────────────────────────────────┘
```

---

## Flow Diagram 5: Session Persistence

```
┌─────────────────────────────────────────┐
│ User is logged in on page 1             │
│ (Kategori page)                         │
│ Session: admin_id = 1                   │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ User navigates to page 2                │
│ (Paket page - click link)               │
│ Browser sends PHPSESSID cookie          │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ Server loads session from cookie        │
│ session_start() finds PHPSESSID file    │
│ $_SESSION restored from file            │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ requireLogin() checks isLoggedIn()       │
│ admin_id found = true                   │
│ Page loads successfully ✓               │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│ User still logged in                    │
│ Session persisted! ✓                    │
│ Can navigate multiple pages              │
└─────────────────────────────────────────┘
```

---

## Summary: Key Protection Points

```
┌────────────────────────────────────────────────────────────┐
│              SECURITY CHECKPOINTS                          │
├────────────────────────────────────────────────────────────┤
│                                                             │
│ 1. LOGIN PAGE                                              │
│    └─ redirectIfLoggedIn()                                │
│       ├─ Already login? → Dashboard                       │
│       └─ Not login? → Show form                           │
│                                                             │
│ 2. REGISTER PAGE                                           │
│    └─ redirectIfLoggedIn()                                │
│       ├─ Already login? → Dashboard                       │
│       └─ Not login? → Show form                           │
│                                                             │
│ 3. DASHBOARD PAGE                                          │
│    └─ requireLogin()                                       │
│       ├─ Logged in? → Load page                           │
│       └─ Not login? → Login page                          │
│                                                             │
│ 4. LOGOUT                                                  │
│    └─ destroySession()                                    │
│       ├─ Clear session                                    │
│       ├─ Clear cookies                                    │
│       └─ Redirect to login                                │
│                                                             │
└────────────────────────────────────────────────────────────┘
```

---

## Technical Stack

```
┌─────────────────────────────────────────┐
│          TECHNICAL COMPONENTS            │
├─────────────────────────────────────────┤
│                                         │
│ CLIENT (Browser)                        │
│ ├─ PHPSESSID Cookie (persistent)        │
│ └─ HTML Forms (login/register)          │
│                                         │
│ NETWORK                                 │
│ ├─ HTTP Headers (Set-Cookie)            │
│ └─ Cookie in every request              │
│                                         │
│ SERVER (PHP)                            │
│ ├─ session.php (manage sessions)        │
│ ├─ login.php (authentication)           │
│ ├─ register.php (user creation)         │
│ ├─ logout.php (session cleanup)         │
│ └─ dashboard.php (protected page)       │
│                                         │
│ DATA                                    │
│ ├─ Database: tb_admin table             │
│ ├─ Server: /tmp/sess_* files            │
│ └─ Browser: PHPSESSID cookie            │
│                                         │
└─────────────────────────────────────────┘
```

---

## Key Points to Remember

### ✅ What Session Management Does
1. Identifies logged-in users
2. Persists login across page loads
3. Prevents unauthorized access
4. Securely logs users out

### ✅ How It Works
1. User logs in → Session created
2. Browser receives PHPSESSID cookie
3. User navigates → Cookie sent with each request
4. Server validates session → Page loads or redirects

### ✅ Security Features
1. Password hashed (bcrypt)
2. Session ID random & unique
3. Cookie path restricted
4. Session timeout configured
5. SQL injection prevented (prepared statements)

### ✅ Testing Points
- [ ] Login → Can't access login page again
- [ ] Dashboard protected → Must login first
- [ ] Logout → Session cleared
- [ ] Refresh → Session persists
- [ ] Different pages → Stays logged in

---

**Last Updated:** 2024
**Documentation Level:** Complete
**Audience:** Developers & QA
**Reference:** admin/README_SESSION_MANAGEMENT.md
