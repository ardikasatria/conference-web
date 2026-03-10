# 🚀 Quick Start - ICSSF Conference Web

## TL;DR (30 Detik Setup)

```bash
cd /Users/ardikasatria/haki/conference-web

# Option 1: Automated setup
bash setup.sh

# Option 2: Manual setup
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

---

## 🎯 Development Workflow

### **Terminal 1: Vite Dev Server** (React Hot Reload)
```bash
npm run dev
```
✅ Automatically rebuilds React components  
🔗 Runs on http://localhost:5173

### **Terminal 2: Laravel Dev Server**
```bash
php artisan serve
```
✅ Serves application & API  
🔗 Runs on http://localhost:8000

---

## 🌐 Access Points

| Url | Purpose | Type |
|-----|---------|------|
| http://localhost:8000 | Public landing page | Inertia.js + React |
| http://localhost:8000/dashboard | Dashboard (auto-route) | Blade |
| http://localhost:8000/dashboard/admin | Admin Dashboard | Blade |
| http://localhost:8000/dashboard/participant | Participant Dashboard | Blade |
| http://localhost:8000/dashboard/reviewer | Reviewer Dashboard | Blade |
| http://localhost:8000/login | Login Page | Blade |
| http://localhost:5173 | Vite HMR Server | Internal |

---

## 📋 First Time Setup Checklist

- [ ] Database created: `icssf_conference`
- [ ] `.env` configured with DB credentials
- [ ] `composer install` ✓
- [ ] `npm install` ✓
- [ ] `php artisan key:generate` ✓
- [ ] `php artisan migrate` ✓
- [ ] `php artisan db:seed --class=RoleSeeder` ✓
- [ ] `npm run dev` running
- [ ] `php artisan serve` running
- [ ] Can access http://localhost:8000

---

## 🛠️ Using Make Commands

```bash
# View all commands
make help

# Install everything
make install

# Full setup with migrations
make setup

# Start both servers
make dev

# Start only Laravel
make serve

# Start only Vite
make vite

# Database operations
make migrate              # Run migrations
make migrate-fresh        # Reset database
make seed                 # Seed roles
make tinker               # Open shell

# Utilities
make clear                # Clear all caches
make logs                 # Tail logs
make status               # Check server status
```

---

## 🧪 Test the Setup

### 1. **Public Landing Page**
```bash
# Open http://localhost:8000
# Should see:
# - ICSSF Navbar
# - Hero banner with animated circles
# - About, Timeline, Topics sections
# - Footer
```

### 2. **Create Test User**
```bash
php artisan tinker

# Create admin user
$admin = App\Models\User::create([
  'name' => 'Admin',
  'email' => 'admin@test.com',
  'password' => bcrypt('password')
]);

$adminRole = App\Models\Role::where('name', 'admin')->first();
$admin->roles()->attach($adminRole->id, ['conference_id' => 1]);

# Create participant user
$participant = App\Models\User::create([
  'name' => 'John Doe',
  'email' => 'john@test.com',
  'password' => bcrypt('password')
]);

$participantRole = App\Models\Role::where('name', 'participant')->first();
$participant->roles()->attach($participantRole->id, ['conference_id' => 1]);

# Create reviewer user
$reviewer = App\Models\User::create([
  'name' => 'Dr. Smith',
  'email' => 'reviewer@test.com',
  'password' => bcrypt('password')
]);

$reviewerRole = App\Models\Role::where('name', 'reviewer')->first();
$reviewer->roles()->attach($reviewerRole->id, ['conference_id' => 1]);
```

### 3. **Test Login for Each Role**
```
Login page: http://localhost:8000/login

Test 1: admin@test.com / password
  → Redirects to /dashboard/admin
  → Shows Admin Dashboard

Test 2: john@test.com / password
  → Redirects to /dashboard/participant
  → Shows Participant Dashboard

Test 3: reviewer@test.com / password
  → Redirects to /dashboard/reviewer
  → Shows Reviewer Dashboard
```

---

## 🔄 Development Tips

### **Auto-reload**
- **React Components**: Vite watches for changes (instant reload)
- **Laravel Code**: Changes auto-reload (requires `php artisan serve`)
- **Blade Views**: Refresh browser (F5)

### **Hot Module Replacement (HMR)**
```bash
# Vite automatically handles component updates without page reload
# Just save file → change appears in browser

# If HMR fails:
# 1. Check console for errors
# 2. Restart npm run dev
# 3. Clear browser cache (Ctrl+Shift+Delete)
```

### **Database**
```bash
# View all users
php artisan tinker
>>> User::with('roles')->get()

# Clear and re-seed
make migrate-fresh

# Run specific migration
php artisan migrate --path=database/migrations/xxxx_create_users_table.php
```

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| "Port 8000 already in use" | `lsof -i :8000` then `kill -9 PID` |
| "npm: command not found" | Install Node.js from nodejs.org |
| "SQLSTATE connection error" | Create database: `mysql -u root -p` then `CREATE DATABASE icssf_conference;` |
| "Vite failed to resolve" | Run `npm install` again |
| "Class not found" | Run `composer dump-autoload` |
| "React not rendering" | Check browser console, restart `npm run dev` |
| "Blade not updating" | Refresh browser or run `php artisan view:clear` |

---

## 📁 Project Structure

```
conference-web/
├── app/                          # Laravel application code
│   ├── Http/Controllers/         # Controllers (RouteController, DashboardController)
│   ├── Http/Middleware/          # Middleware (EnsureUserHasRole)
│   └── Models/                   # Eloquent models (User, Role, Registration, etc)
│
├── resources/
│   ├── js/                       # React & Inertia.js
│   │   ├── app.jsx              # Entry point (Inertia.js setup)
│   │   ├── Pages/               # Page components
│   │   │   └── Home.jsx         # Public landing page
│   │   └── Components/          # Reusable React components
│   │       ├── Navbar.jsx
│   │       ├── Banner.jsx
│   │       ├── About.jsx
│   │       └── ... (more components)
│   │
│   ├── views/                   # Blade templates
│   │   ├── dashboard/           # Dashboard pages per role
│   │   │   ├── admin.blade.php
│   │   │   ├── participant.blade.php
│   │   │   └── reviewer.blade.php
│   │   ├── auth/                # Authentication pages
│   │   └── layouts/             # Layout templates
│   │
│   └── css/
│       └── app.css              # Tailwind + custom styles
│
├── routes/
│   ├── web.php                  # Web routes (public + protected)
│   └── api.php                  # API routes
│
├── database/
│   ├── migrations/              # Schema migrations
│   └── seeders/                 # Database seeders (RoleSeeder, etc)
│
├── bootstrap/
│   └── app.php                  # Laravel bootstrap (middleware registration)
│
├── Makefile                      # Make commands for development
├── setup.sh                      # Automated setup script
├── dev-server.sh                # Start both servers
├── vite.config.js               # Vite configuration
├── tailwind.config.js           # Tailwind configuration
├── composer.json                # PHP dependencies
└── package.json                 # JavaScript dependencies & npm scripts
```

---

## 📖 Documentation Files

```
DEVELOPMENT_GUIDE.md       # Complete development guide
QUICK_START.md            # This file
API_DOCUMENTATION.md      # API endpoints
DATABASE_SCHEMA.md        # Database structure
ROLES_PERMISSIONS.md      # Role definitions
```

---

## 🎓 Next Steps

### 1. **Familiarize with Code**
- Check `routes/web.php` for routing
- Look at `resources/js/Components/` for React components
- Check `resources/views/dashboard/` for Blade templates

### 2. **Make Changes**
- Edit React components: Changes auto-reload via Vite
- Edit Blade views: Refresh browser to see changes
- Create new routes in `routes/web.php`

### 3. **Test Features**
- Use Postman/Insomnia for API testing
- Test authentication flow
- Verify role-based access

### 4. **Database Operations**
- Use Tinker for quick testing
- Create seeders for sample data
- Run migrations when needed

---

## ✅ You're Ready!

Everything is set up for development. Start your servers and begin coding! 🎉

```bash
# In Terminal 1
npm run dev

# In Terminal 2
php artisan serve

# Then visit http://localhost:8000
```

---

## 📞 Need Help?

Check these files:
- **DEVELOPMENT_GUIDE.md** - Complete setup instructions
- **API_DOCUMENTATION.md** - API endpoints
- **DATABASE_SCHEMA.md** - Database structure

Run `make help` to see all available commands.
