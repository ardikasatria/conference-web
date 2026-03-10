# ICSSF Conference Web - Architecture & Development Guide

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT (Browser)                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                 ┌────────────┴────────────┐
                 │                         │
        ┌────────▼─────────┐      ┌───────▼───────┐
        │ PUBLIC PAGES     │      │   DASHBOARD   │
        │ (Inertia.js +    │      │    (Blade)    │
        │   React)         │      │               │
        └────────┬─────────┘      └───────┬───────┘
                 │                         │
                 │  Inertia.js Request    │ Traditional Form
                 │                         │
        ┌────────▼────────────────────────▼────────┐
        │                                           │
        │     LARAVEL APPLICATION SERVER           │
        │                                           │
        │  ┌──────────────────────────────────┐    │
        │  │ Routes (web.php)                │    │
        │  │ ├─ / (Inertia → Home.jsx)       │    │
        │  │ ├─ /dashboard (Blade → Role)    │    │
        │  │ ├─ /login (Blade)               │    │
        │  │ └─ /api/* (API endpoints)       │    │
        │  └──────────────────────────────────┘    │
        │                                           │
        │  ┌──────────────────────────────────┐    │
        │  │ Controllers                      │    │
        │  │ ├─ DashboardController           │    │
        │  │ ├─ Auth/LoginController          │    │
        │  │ └─ RoutingController (Inertia)   │    │
        │  └──────────────────────────────────┘    │
        │                                           │
        │  ┌──────────────────────────────────┐    │
        │  │ Models                           │    │
        │  │ ├─ User (with roles)             │    │
        │  │ ├─ Role                          │    │
        │  │ ├─ Registration                  │    │
        │  │ ├─ Submission                    │    │
        │  │ └─ ... (more models)             │    │
        │  └──────────────────────────────────┘    │
        │                                           │
        │  ┌──────────────────────────────────┐    │
        │  │ Middleware                       │    │
        │  │ ├─ Auth                          │    │
        │  │ └─ EnsureUserHasRole            │    │
        │  └──────────────────────────────────┘    │
        │                                           │
        └───────────────┬──────────────────────────┘
                        │
        ┌───────────────▼──────────────────┐
        │                                  │
        │      DATABASE (MySQL)            │
        │                                  │
        │  ├─ users                        │
        │  ├─ roles                        │
        │  ├─ user_roles                   │
        │  ├─ registrations                │
        │  ├─ submissions                  │
        │  ├─ conferences                  │
        │  └─ ... (more tables)            │
        │                                  │
        └──────────────────────────────────┘
```

---

## 🔄 Request Flow

### **Public Page (Inertia.js + React)**

```
1. User visits: http://localhost:8000
           ↓
2. Laravel routes to: RoutingController@index
           ↓
3. Controller returns Inertia response:
   inertia('Home')
           ↓
4. Inertia.js loads React app from resources/js/app.jsx
           ↓
5. React resolves Pages/Home.jsx
           ↓
6. Home.jsx renders React components:
   ├─ Navbar
   ├─ Banner
   ├─ About
   ├─ Timeline
   ├─ Topics
   └─ ... (more sections)
           ↓
7. Vite hot reload watches for changes
           ↓
8. Browser displays fully interactive React page
```

### **Dashboard (Blade + Role-based)**

```
1. User visits: http://localhost:8000/login
           ↓
2. Laravel routes to: LoginController@showLoginForm
           ↓
3. Returns Blade view: auth/login.blade.php
           ↓
4. User submits credentials
           ↓
5. LoginController@login validates and authenticates
           ↓
6. Redirects to: /dashboard (DashboardController@index)
           ↓
7. DashboardController checks user roles:
   ├─ if admin → return view('dashboard.admin')
   ├─ if participant → return view('dashboard.participant')
   ├─ if reviewer → return view('dashboard.reviewer')
           ↓
8. Browser displays role-specific Blade template
           ↓
9. Changes to Blade require page refresh
```

---

## 🔐 Authentication & Authorization Flow

```
┌─────────────────┐
│ User NOT logged │
└────────┬────────┘
         │
         ▼
┌──────────────────────┐
│ Requests public page  │
│ GET / (Inertia)      │
└────────┬─────────────┘
         │ ✓ No auth needed
         ▼
┌──────────────────────┐
│ Requests dashboard   │
│ GET /dashboard/admin │
└────────┬─────────────┘
         │ ✗ Needs auth
         ▼
┌──────────────────────┐
│ Auth middleware       │
│ redirects to /login  │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ User logs in         │
│ POST /login          │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Session created      │
│ user_id stored       │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Redirects to         │
│ /dashboard           │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────────────┐
│ DashboardController          │
│ checks roles via:            │
│ user->hasRole('admin')       │
└────────┬─────────────────────┘
         │
  ┌──────┴──────┐
  │ ✓ has role  │  ✗ no role
  ▼             ▼
show admin    abort(403)
dashboard    Unauthorized
```

---

## 📁 File Organization

### **Frontend Code (React + Inertia)**

```
resources/
└── js/
    ├── app.jsx                           ← Main Inertia.js entry
    │   (Loads React and sets up Inertia)
    │
    ├── Pages/
    │   └── Home.jsx                      ← Public landing page
    │
    └── Components/                       ← Reusable React components
        ├── Navbar.jsx
        ├── Banner.jsx
        ├── About.jsx
        ├── Timeline.jsx
        ├── Topics.jsx
        ├── Keynote.jsx
        ├── Speaker.jsx
        ├── Pricelist.jsx
        ├── Countdown.jsx
        ├── Venue.jsx
        ├── Trip.jsx
        ├── Contact.jsx
        ├── Faq.jsx
        └── Footer.jsx
```

### **Backend Code (Laravel / Blade)**

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php       ← Route dashboard by role
│   │   ├── Auth/
│   │   │   └── LoginController.php       ← Handle login/logout
│   │   ├── RoutingController.php         ← Inertia routing
│   │   └── ... (other controllers)
│   │
│   └── Middleware/
│       └── EnsureUserHasRole.php         ← Check user role
│
├── Models/
│   ├── User.php                          ← User model with role methods
│   ├── Role.php                          ← Role model
│   ├── Registration.php
│   ├── Submission.php
│   └── ... (other models)
│
└── Providers/

resources/views/
├── dashboard/
│   ├── admin.blade.php                   ← Admin dashboard
│   ├── participant.blade.php             ← Participant dashboard
│   └── reviewer.blade.php                ← Reviewer dashboard
│
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── ... (other auth views)
│
└── layouts/
    ├── vertical.blade.php                ← Main layout
    └── partials/                         ← Layout components
        ├── navbar.blade.php
        ├── sidebar.blade.php
        └── ... (other partials)

routes/
├── web.php                               ← All web routes
└── api.php                               ← API routes
```

---

## 🚀 Development Workflow

### **Making Changes to React Components**

```
1. Edit file: resources/js/Components/Navbar.jsx
           ↓
2. Vite watches for changes
           ↓
3. Automatically rebuilds assets
           ↓
4. Browser HMR (Hot Module Replacement)
           ↓
5. Navbar updates in browser without full page reload ✨
```

### **Making Changes to Blade Views**

```
1. Edit file: resources/views/dashboard/admin.blade.php
           ↓
2. Laravel compiles view (no build needed)
           ↓
3. Manually refresh browser (F5)
           ↓
4. Updated view displays
```

### **Making Changes to PHP Code**

```
1. Edit file: app/Http/Controllers/DashboardController.php
           ↓
2. Laravel auto-reloads (powered by php artisan serve)
           ↓
3. Next request uses updated code
```

---

## 🗄️ Database Schema Overview

```
users
├── id
├── name
├── email
├── password
├── email_verified_at
├── timestamps

roles
├── id
├── name (admin, participant, reviewer)
├── display_name
├── description
├── timestamps

user_roles (Junction Table)
├── id
├── user_id (FK → users)
├── role_id (FK → roles)
├── conference_id (FK → conferences)
├── timestamps

registrations
├── id
├── conference_id (FK)
├── user_id (FK)
├── package_id (FK)
├── submission_id (FK)
├── status
├── submission_status
├── payment_status
├── timestamps

submissions
├── id
├── registration_id (FK)
├── title
├── abstract
├── keywords
├── file_path
├── status
├── timestamps

conferences
├── id
├── name
├── start_date
├── end_date
├── description
├── timestamps

... (and more tables for topics, speakers, sessions, etc.)
```

---

## 🔌 API Patterns

```
Public Routes (No Auth)
├── GET / → Home page (Inertia)
├── GET /login → Login form
└── GET /register → Register form

Protected Routes (Auth Required)
├── GET /dashboard → Route to role dashboard
├── GET /dashboard/admin → Admin dashboard (role:admin)
├── GET /dashboard/participant → Participant dashboard (role:participant)
├── GET /dashboard/reviewer → Reviewer dashboard (role:reviewer)
└── POST /logout → Logout

API Routes (Protected)
├── POST /api/registrations
├── GET /api/submissions
├── POST /api/submissions
├── GET /api/reviews
├── POST /api/reviews
└── ... (more endpoints)
```

---

## 🧪 Testing Checklist

**Public Landing Page:**
- [ ] Navbar renders
- [ ] Banner with animations loads
- [ ] All sections visible
- [ ] Responsive on mobile
- [ ] Links work
- [ ] Footer displays

**Authentication:**
- [ ] Login page loads
- [ ] Can login with correct credentials
- [ ] Session created
- [ ] Cannot access /login when logged in
- [ ] Logout works

**Role-Based Access:**
- [ ] Admin user → admin dashboard
- [ ] Participant user → participant dashboard
- [ ] Reviewer user → reviewer dashboard
- [ ] Unauthorized user → 403 error
- [ ] Can't access other role dashboards

**Database:**
- [ ] Migrations run successfully
- [ ] Roles seeded (admin, participant, reviewer)
- [ ] Can create users
- [ ] Can assign roles
- [ ] Relationships work

---

## 🎯 Development Priorities

### Phase 1: Setup & Foundation ✅
- [x] Install dependencies
- [x] Configure database
- [x] Create migrations & seeders
- [x] Setup authentication
- [x] Create role-based dashboards
- [x] Setup Inertia.js + React

### Phase 2: Development
- [ ] Implement API endpoints
- [ ] Create submission forms
- [ ] Build review system
- [ ] Implement payment system
- [ ] Create user profiles

### Phase 3: Polish
- [ ] UI/UX improvements
- [ ] Performance optimization
- [ ] Security hardening
- [ ] Testing (unit, integration)
- [ ] Documentation

---

## 📚 Useful Commands

```bash
# Start everything
make dev

# Database operations
make migrate
make seed
make migrate-fresh

# Utilities
make clear             # Clear caches
make logs             # View logs
make tinker           # Interactive shell
make status           # Check server status

# Build for production
npm run build
```

---

## 🔗 Quick Links

- **Development Guide**: [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md)
- **Quick Start**: [QUICK_START.md](./QUICK_START.md)
- **API Documentation**: [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- **Database Schema**: [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md)
- **Roles & Permissions**: [ROLES_PERMISSIONS.md](./ROLES_PERMISSIONS.md)

---

Happy coding! 🎉
