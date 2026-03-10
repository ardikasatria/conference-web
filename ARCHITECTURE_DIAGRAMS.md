# System Architecture Diagrams & Quick Reference

## 🔄 Complete Request/Response Cycle

```
Browser Request
       │
       ├─→ GET /                           ├─→ GET /dashboard
       │   (Public Landing Page)           │   (Dashboard)
       │                                   │
       ├─→ Laravel Router (routes/web.php)
       │                                   
       ├─────────────────────────────────┬─────────────────┐
       │                                 │                 │
       ▼                                 ▼                 ▼
   RoutingController            DashboardController    Auth Check
   │                            │                      │
   │ inertia('Home')            ├─→ Check Auth ✓      ├─→ Not logged in?
   │                            │                     │   Redirect /login
   │                            ├─→ Check Role:       │
   │                            │   - admin?          
   │                            │   - participant?    
   │                            │   - reviewer?       
   │                            │                     
   └─→ Inertia Response         ├─→ Return Blade View
       │                        │   dashboard/admin.blade.php
       ├─→ Load React App       │   OR
       │   (app.jsx)            │   dashboard/participant.blade.php
       │                        │   OR
       ├─→ Resolve Page         │   dashboard/reviewer.blade.php
       │   (Pages/Home.jsx)     │
       │                        └─→ HTML Response
       ├─→ Load Components      
       │   (14 components)      
       │                        
       ├─→ Vite HMR Connection
       │   (hot.js)             
       │                        
       └─→ Render in Browser
           │
           ├─ Navbar
           ├─ Banner (with animations)
           ├─ About
           ├─ Timeline
           ├─ Topics
           ├─ Keynote
           ├─ Speaker
           ├─ Pricelist
           ├─ Countdown
           ├─ Venue
           ├─ Trip
           ├─ Contact
           ├─ Faq
           └─ Footer
```

---

## 🔐 Role-Based Access Control Flow

```
User Requests: GET /dashboard/admin

       ↓
       
Auth Middleware
├─ User logged in? NO → Redirect /login
└─ User logged in? YES ↓

Route Protection: middleware('role:admin')

EnsureUserHasRole Middleware
├─ Get user roles from user_roles pivot table
├─ Check if user has 'admin' role
│
├─ Role found? YES ↓
│                └─→ Proceed to controller
│
└─ Role NOT found? ↓
                   └─→ abort(403) Access Denied
                       Show error page

DashboardController@index
├─ Check user->hasRole('admin')
├─ Return view('dashboard.admin')
└─→ User sees admin dashboard
```

---

## 📊 Data Flow: Dashboard Stats

```
Admin opens dashboard
       ↓
Laravel renders: dashboard/admin.blade.php
       ↓
Blade template has:
├─ {{ $registrationCount }} stats card 1
├─ {{ $submissionCount }} stats card 2
├─ {{ $reviewCount }} stats card 3
└─ {{ $revenueTotal }} stats card 4
       ↓
Controller should pass data:
       ↓
DashboardController@showAdminDashboard()
{
    $registrations = Registration::where('conference_id', 1)->count();
    $submissions = Submission::count();
    $reviews = Review::where('status', 'completed')->count();
    $revenue = Registration::sum('payment_amount');
    
    return view('dashboard.admin', [
        'registrationCount' => $registrations,
        'submissionCount' => $submissions,
        'reviewCount' => $reviews,
        'revenueTotal' => $revenue,
    ]);
}
       ↓
render: resources/views/dashboard/admin.blade.php
       ↓
Browser displays populated dashboard
```

---

## 🗂️ File Organization Map

```
conference-web/
│
├── PUBLIC CONFIG FILES
│   ├── .env (database, app settings) ← NOT COMMITTED
│   ├── .gitignore
│   ├── vite.config.js (Vite + React setup)
│   ├── tailwind.config.js (custom theme)
│   ├── postcss.config.js (CSS plugins)
│   ├── package.json (npm dependencies)
│   ├── composer.json (PHP dependencies)
│   ├── phpunit.xml (testing config)
│   │
│   └── DOCUMENTATION
│       ├── README.md
│       ├── DEVELOPMENT_GUIDE.md ← START HERE
│       ├── QUICK_START.md ← QUICK REFERENCE
│       ├── DEVELOPMENT_ARCHITECTURE.md ← DIAGRAMS (THIS FILE)
│       ├── PROJECT_STATUS.md ← COMPLETION REPORT
│       ├── API_DOCUMENTATION.md
│       ├── DATABASE_SCHEMA.md
│       ├── ROLES_PERMISSIONS.md
│       ├── REVIEWER_SYSTEM.md
│       ├── SUBMISSION_PAYMENT_FLOW.md
│       └── And other docs...
│
├── SCRIPTS (Automation)
│   ├── setup.sh (automated initial setup)
│   ├── dev-server.sh (start Vite + Laravel)
│   └── Makefile (30+ development commands)
│
├── APP APPLICATION CODE
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php (role routing)
│   │   │   ├── RoutingController.php (Inertia routing)
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php (auth handling)
│   │   │   │   └── RegisterController.php
│   │   │   └── ... (other controllers)
│   │   │
│   │   └── Middleware/
│   │       ├── EnsureUserHasRole.php (role check)
│   │       └── Authenticate.php (auth middleware)
│   │
│   ├── Models/ (Database models)
│   │   ├── User.php (with roles relationship)
│   │   ├── Role.php
│   │   ├── Registration.php (FIXED)
│   │   ├── Submission.php
│   │   ├── Review.php
│   │   ├── Conference.php
│   │   └── ... (other models)
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── ... (other providers)
│   │
│   ├── Services/ (Business logic)
│   │   └── ... (service classes)
│   │
│   └── Jobs/ (Queue jobs)
│       └── ... (async jobs)
│
├── ROUTES (URL routing)
│   ├── web.php (public + dashboard routes)
│   ├── api.php (API endpoints - to be built)
│   └── console.php (CLI commands)
│
├── RESOURCES (Frontend assets)
│   ├── js/
│   │   ├── app.jsx (Inertia.js bootstrap)
│   │   ├── bootstrap.js (app setup)
│   │   │
│   │   ├── Pages/
│   │   │   └── Home.jsx (landing page)
│   │   │
│   │   └── Components/ (React components)
│   │       ├── Navbar.jsx
│   │       ├── Banner.jsx
│   │       ├── About.jsx
│   │       ├── Timeline.jsx
│   │       ├── Topics.jsx
│   │       ├── Keynote.jsx
│   │       ├── Speaker.jsx
│   │       ├── Pricelist.jsx
│   │       ├── Countdown.jsx
│   │       ├── Venue.jsx
│   │       ├── Trip.jsx
│   │       ├── Contact.jsx
│   │       ├── Faq.jsx
│   │       └── Footer.jsx
│   │
│   ├── css/
│   │   ├── app.css (Tailwind + custom styles + animations)
│   │   └── ... (other stylesheets)
│   │
│   └── views/ (Blade templates)
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── ... (other auth pages)
│       │
│       ├── dashboard/ (role dashboards)
│       │   ├── admin.blade.php (admin view)
│       │   ├── participant.blade.php (participant view)
│       │   └── reviewer.blade.php (reviewer view)
│       │
│       ├── layouts/
│       │   ├── vertical.blade.php (main layout)
│       │   └── partials/ (layout components)
│       │
│       └── ... (other views)
│
├── DATABASE
│   ├── migrations/ (23 files)
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_roles_table.php
│   │   ├── 2024_01_01_000003_create_user_roles_table.php (pivot)
│   │   ├── 2024_01_01_000004_create_registrations_table.php
│   │   ├── 2024_01_01_000005_create_submissions_table.php
│   │   ├── ... (and more)
│   │
│   ├── seeders/
│   │   ├── DatabaseSeeder.php (main seeder)
│   │   ├── RoleSeeder.php (role seeding)
│   │   └── ... (other seeders)
│   │
│   └── factories/ (model factories for testing)
│       ├── UserFactory.php
│       └── ... (other factories)
│
├── CONFIG (Configuration files)
│   ├── app.php (app config)
│   ├── auth.php (auth config)
│   ├── database.php (database config)
│   ├── cache.php (cache config)
│   ├── Queue.php (queue config)
│   ├── filesystems.php (storage config)
│   └── ... (other configs)
│
├── BOOTSTRAP (Framework bootstrap)
│   ├── app.php (server bootstrap + middleware registration)
│   ├── providers.php (service provider bootstrap)
│   └── cache/ (cached config)
│
├── STORAGE (Runtime files - not committed)
│   ├── app/ (application files)
│   ├── logs/ (application logs)
│   └── framework/ (framework files)
│
├── TESTS (Test suite)
│   ├── Unit/ (unit tests)
│   ├── Feature/ (integration tests)
│   ├── TestCase.php (base test class)
│   └── ... (other test files)
│
├── PUBLIC (Web root)
│   ├── index.php (application entry point)
│   ├── robots.txt (SEO)
│   ├── build/ (Vite compiled assets)
│   └── images/ (static images)
│
├── VENDOR (Composer packages - not committed)
│   └── ... (PHP dependencies)
│
└── NODE_MODULES (npm packages - not committed)
    └── ... (JavaScript dependencies)
```

---

## 🌐 URL Route Map

```
PUBLIC ROUTES (No Authentication Required)
├─ GET /                           → Home landing page (Inertia → Home.jsx)
├─ GET /login                      → Login form (Blade)
├─ GET /register                   → Registration form (Blade)
└─ GET /about                      → About page (optional)

PROTECTED ROUTES (Authentication Required)
├─ POST /login                     → Login submission
├─ POST /logout                    → Logout
│
├─ GET /dashboard                  → Role redirect (DashboardController@index)
│   ├─→ Admin user → /dashboard/admin
│   ├─→ Participant user → /dashboard/participant
│   └─→ Reviewer user → /dashboard/reviewer
│
├─ GET /dashboard/admin            → Admin dashboard (needs role:admin)
├─ GET /dashboard/participant      → Participant dashboard (needs role:participant)
├─ GET /dashboard/reviewer         → Reviewer dashboard (needs role:reviewer)
│
└─ ... (more protected routes)

API ROUTES (To Be Implemented)
├─ GET /api/registrations          → List registrations
├─ POST /api/registrations         → Create registration
├─ GET /api/submissions            → List submissions
├─ POST /api/submissions           → Create submission
├─ GET /api/reviews                → List reviews
├─ POST /api/reviews               → Create review
└─ ... (more endpoints)
```

---

## 💾 Database Table Relationships

```
users (table)
├─ id (PK)
├─ name
├─ email
├─ password
├─ timestamps
│
└─ Relations:
   ├─ hasMany(Registration)
   ├─ belongsToMany(Role) via user_roles
   └─ hasMany(Submission)

roles (table)
├─ id (PK)
├─ name ('admin', 'participant', 'reviewer')
├─ display_name
├─ description
├─ timestamps
│
└─ Relations:
   └─ belongsToMany(User) via user_roles

user_roles (pivot table)
├─ id (PK)
├─ user_id (FK → users.id)
├─ role_id (FK → roles.id)
├─ conference_id (FK → conferences.id)
├─ timestamps
│
└─ Purpose: Many-to-many with conference scoping

registrations (table)
├─ id (PK)
├─ conference_id (FK)
├─ user_id (FK)
├─ package_id (FK)
├─ status
├─ payment_status
├─ timestamps
│
└─ Relations:
   ├─ belongsTo(Conference)
   ├─ belongsTo(User)
   └─ hasMany(Submission)

submissions (table)
├─ id (PK)
├─ registration_id (FK)
├─ title
├─ abstract
├─ keywords
├─ file_path
├─ status
├─ timestamps
│
└─ Relations:
   ├─ belongsTo(Registration)
   └─ hasMany(Review)

conferences (table)
├─ id (PK)
├─ name
├─ start_date
├─ end_date
├─ description
├─ timestamps
│
└─ Relations:
   └─ hasMany(Registration)
```

---

## 🚀 Development Command Reference

```bash
# Quick Start
make dev                 # Start both servers (Vite + Laravel)
make help               # Show all available commands

# Installation
make install            # Install all dependencies
make setup              # Full setup (install + migrate + seed)
make fresh-install      # Clean install from scratch

# Database
make migrate            # Run migrations
make migrate-fresh      # Reset database and run migrations
make seed               # Run seeders
make seed-roles         # Seed only roles

# Development
make serve              # Start Laravel server (port 8000)
make vite               # Start Vite server (port 5173)
make watch              # Watch for changes (webpack style)

# Utilities
make clear              # Clear all caches
make logs               # View application logs
make tinker             # Start Laravel Tinker (interactive shell)
make test               # Run tests
make queue              # Start queue worker
make db                 # Connect to database

# Debugging
make status             # Check server status
make config             # Show app configuration
make routes             # List all routes
```

---

## 🔍 Component Dependency Chart

```
Home.jsx
├─ Navbar
│  └─ (Links to /login, /register)
│
├─ Banner
│  └─ (Hero section)
│
├─ About
│  └─ (Conference info)
│
├─ Timeline
│  └─ (Submission timeline)
│
├─ Topics
│  └─ (Conference topics)
│
├─ Keynote
│  ├─ (Keynote speakers)
│  └─ (Uses images)
│
├─ Speaker
│  └─ (Speaker section)
│
├─ Pricelist
│  └─ (Pricing tiers)
│
├─ Countdown
│  └─ (Real-time counter)
│
├─ Venue
│  └─ (Location info)
│
├─ Trip
│  └─ (Transportation)
│
├─ Contact
│  └─ (Contact info + social links)
│
├─ Faq
│  ├─ (FAQ accordion)
│  └─ (Lucide ChevronDown icon)
│
└─ Footer
   └─ (Copyright + links)

Shared Dependencies:
├─ Tailwind CSS (styling)
├─ Lucide React (icons)
├─ Swiper (carousel/sliders)
└─ Custom fonts (Space Grotesk, Plus Jakarta Sans, DM Sans)
```

---

## 🔧 Middleware Stack

```
Route Request
       │
       ├─→ Laravel Middleware Pipeline
       │   ├─ Middleware/Trustproxies.php
       │   ├─ Middleware/CheckForMaintenanceMode.php
       │   ├─ Middleware/ValidatePostSize.php
       │   ├─ Middleware/TrimStrings.php
       │   ├─ Middleware/ConvertEmptyStringsToNull.php
       │   ├─ Middleware/EncryptCookies.php
       │   ├─ Middleware/AddQueuedCookiesToResponse.php
       │   ├─ Middleware/StartSession.php
       │   ├─ Middleware/ShareErrorsFromSession.php
       │   ├─ Middleware/VerifyCsrfToken.php (if POST)
       │   ├─ Middleware/SubstituteBindings.php
       │   │
       │   └─ Route Specific Middleware:
       │       ├─ Authenticate.php (auth:web)
       │       ├─ EnsureUserHasRole.php (role:admin, etc.)
       │       └─ ... (other route middleware)
       │
       └─→ Controller
           └─→ Response
```

---

## 📈 Development Progress Timeline

```
Phase 1: Setup & Foundation ✅ COMPLETED
├─ Install dependencies
├─ Configure database
├─ Create migrations & seeders
├─ Setup authentication
├─ Create role-based middleware
└─ Setup Inertia.js + React

Phase 2: Frontend Implementation ✅ COMPLETED
├─ Create Vite config
├─ Setup Tailwind CSS
├─ Create 14 React components
├─ Create Home.jsx landing page
├─ Create app.css with animations
└─ Setup responsive design

Phase 3: Backend & Dashboards ✅ COMPLETED
├─ Create DashboardController
├─ Create 3 dashboard Blade views
├─ Create LoginController
├─ Deploy role-based access control
├─ Setup database seeders
└─ Configure routes

Phase 4: Documentation & Automation ✅ COMPLETED
├─ Create DEVELOPMENT_GUIDE.md
├─ Create QUICK_START.md
├─ Create setup.sh script
├─ Create dev-server.sh script
├─ Create Makefile
├─ Create DEVELOPMENT_ARCHITECTURE.md
└─ Create PROJECT_STATUS.md

Phase 5: Active Development 🟡 NEXT
├─ Implement API endpoints
├─ Create forms & validation
├─ Integrate payment system
├─ Setup email notifications
├─ Implement file uploads
├─ Add search & filtering
├─ Write tests
└─ Deploy to production
```

---

## 💡 Architecture Decision Justifications

| Decision | Reason | Trade-off |
|----------|--------|-----------|
| **Inertia.js** | Server rendering saves API complexity | Can't use without Laravel server |
| **React** for frontend | Modern, component-based, large ecosystem | Adds JS build step |
| **Blade** for dashboard | Preserves Adminto theme, less migration | Two different templating systems |
| **Vite** | Fast HMR, modern tooling | Different from Laravel Mix (if used before) |
| **Role-based middleware** | Single auth point, easy to understand | All routes check roles manually |
| **MySQL** | Reliable, industry standard | Schema design matters more |
| **Eloquent ORM** | Type-safe, powerful relationships | Learning curve for SQL-focused devs |

---

## 🎯 Success Criteria

✅ **Achieved:**
- Landing page renders properly
- Authentication system works
- Role-based access control functions
- Dashboard routing correct
- Development automation scripts work
- Comprehensive documentation exists

🟡 **In Progress:**
- API endpoints
- Form handling
- Database population

❌ **Not Started:**
- Payment integration
- Email system
- File uploads
- Testing suite
- Production deployment

---

**This diagram file references all other documentation. Start with QUICK_START.md for fastest setup!**
