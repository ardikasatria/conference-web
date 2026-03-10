# Development & Testing Guide - ICSSF Conference Web

## 🚀 Project Overview

**ICSSF Conference Web** adalah sistem konferensi dengan arsitektur dual-stack:

1. **Public Landing Page** → React + Inertia.js (SPA)
2. **Dashboard** → Laravel + Blade (Traditional MVC)
3. **API** → Laravel RESTful API

---

## 📋 Prerequisites

Sebelum memulai, pastikan sudah install:

```bash
# Check versions
php -v          # PHP 8.1+
composer -v     # Latest
node -v         # Node 18+
npm -v          # npm 9+
```

---

## 🔧 Step 1: Setup Environment

### 1. Clone/Open Project
```bash
cd /Users/ardikasatria/haki/conference-web
```

### 2. Setup Environment File
```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env dengan database credentials
nano .env
```

**Important .env settings:**
```env
APP_NAME=ICSSF
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=icssf_conference
DB_USERNAME=root
DB_PASSWORD=your_password

VITE_API_URL=http://localhost:8000
```

### 3. Generate App Key
```bash
php artisan key:generate
```

---

## 💾 Step 2: Database Setup

### 1. Create Database
```bash
# Gunakan MySQL Workbench atau command:
mysql -u root -p
mysql> CREATE DATABASE icssf_conference CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
mysql> EXIT;
```

### 2. Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Seed roles
php artisan db:seed --class=RoleSeeder

# Optional: Seed sample data
php artisan db:seed
```

**Check database:**
```bash
php artisan tinker
>>> DB::table('roles')->get();
```

---

## 📦 Step 3: Install Dependencies

### PHP Dependencies (Composer)
```bash
# Install composer packages
composer install

# Update packages if needed
composer update
```

### JavaScript Dependencies (npm)
```bash
# Install npm packages
npm install

# Update packages if needed
npm update
```

**Files akan di-install:**
- React 18
- Inertia.js
- Tailwind CSS
- Lucide Icons
- Laravel Vite Plugin

---

## 🎯 Step 4: Compile Assets

### Development Mode (dengan Hot Reload)
```bash
# Terminal 1 - Vite dev server (untuk React assets)
npm run dev
```

Output:
```
  ➜  Local:   http://localhost:5173/
```

### Production Build
```bash
# Build assets untuk production
npm run build

# Check generated files di public/build/
ls public/build/
```

---

## 🏃 Step 5: Start Development Server

### Laravel Development Server
```bash
# Terminal 2 - Laravel dev server
php artisan serve
```

Output:
```
Local:   http://localhost:8000
```

### Full Development Setup
```bash
# Terminal 1: Vite (React hot reload)
npm run dev

# Terminal 2: Laravel (Backend)
php artisan serve

# Terminal 3 (Optional): Queue (untuk email/notifications)
php artisan queue:work
```

---

## 🧪 Step 6: Testing

### A. Test Public Landing Page (Inertia.js + React)

1. **Open browser:** http://localhost:8000
2. **Check components:**
   - ✅ Navbar (sticky pada scroll)
   - ✅ Banner dengan animated circles
   - ✅ About, Timeline, Topics sections
   - ✅ Footer

3. **Test responsive:**
   - Desktop (full width)
   - Tablet (768px)
   - Mobile (375px)

### B. Test Dashboard (Blade + Role-based)

1. **Login page:** http://localhost:8000/login
2. **Create test user dengan roles:**

```bash
php artisan tinker

# Create test users
>>> $user = \App\Models\User::create([
>>>   'name' => 'Admin User',
>>>   'email' => 'admin@example.com',
>>>   'password' => bcrypt('password')
>>> ]);

>>> $adminRole = \App\Models\Role::where('name', 'admin')->first();
>>> $user->roles()->attach($adminRole->id, ['conference_id' => 1]);

# Repeat for participant dan reviewer roles
```

3. **Test different dashboards:**
   - **Admin**: http://localhost:8000/dashboard/admin
     - Manage registrations, submissions, reviewers
   - **Participant**: http://localhost:8000/dashboard/participant
     - View status, submit papers, register sessions
   - **Reviewer**: http://localhost:8000/dashboard/reviewer
     - Assign papers, submit reviews

### C. API Testing

```bash
# Test registration API
curl -X POST http://localhost:8000/api/registrations \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"conference_id":1}'

# Test authentication
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

---

## 🐛 Debugging Tips

### Laravel Debugging
```bash
# Tail logs
tail -f storage/logs/laravel.log

# Check database queries
php artisan tinker
>>> DB::enableQueryLog();
>>> \App\Models\User::all();
>>> DB::getQueryLog();

# Clear cache/config
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### React/Inertia Debugging
```bash
# Open browser DevTools
F12 → Console / React DevTools

# Check network requests
Network tab → look for Inertia requests
```

---

## 🏗️ Project Structure

```
conference-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── Auth/LoginController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       └── EnsureUserHasRole.php
│   └── Models/
│       ├── User.php
│       ├── Role.php
│       ├── Registration.php
│       └── ...
├── resources/
│   ├── js/
│   │   ├── app.jsx              ← Inertia.js entry point
│   │   ├── Pages/
│   │   │   └── Home.jsx         ← Public landing page
│   │   └── Components/          ← React components
│   │       ├── Navbar.jsx
│   │       ├── Banner.jsx
│   │       └── ...
│   ├── views/                   ← Blade views
│   │   ├── dashboard/
│   │   │   ├── admin.blade.php
│   │   │   ├── participant.blade.php
│   │   │   └── reviewer.blade.php
│   │   └── auth/
│   │       └── login.blade.php
│   └── css/
│       └── app.css              ← Tailwind + custom styles
├── routes/
│   ├── web.php                  ← All web routes
│   └── api.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   └── RoleSeeder.php
│   └── factories/
├── vite.config.js               ← Vite configuration (React)
├── tailwind.config.js           ← Tailwind configuration
├── package.json                 ← npm dependencies
└── composer.json                ← PHP dependencies
```

---

## 📚 Common Commands

```bash
# Laravel Commands
php artisan migrate              # Run migrations
php artisan migrate:refresh      # Refresh database
php artisan tinker               # Interactive shell
php artisan serve                # Start server
php artisan make:controller Name  # Generate controller
php artisan db:seed              # Seed database

# npm Commands
npm install                      # Install dependencies
npm run dev                       # Start Vite dev server
npm run build                     # Build for production
npm run preview                   # Preview production build

# Composer Commands
composer install                 # Install dependencies
composer update                  # Update packages
composer require package-name    # Add package
composer dump-autoload           # Regenerate autoloader
```

---

## 🚨 Troubleshooting

### "SQLSTATE[HY000]: General error: 1030 Got error"
```bash
# Solution: Run migrations
php artisan migrate
```

### "npm: command not found"
```bash
# Solution: Install Node.js dari nodejs.org
```

### "Vite failed to resolve"
```bash
# Solution: Clear npm cache dan reinstall
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### "Class not found" error
```bash
# Solution: Regenerate autoloader
composer dump-autoload
```

### React component not rendering
```bash
# Solution: Check browser console
# Make sure npm run dev is running
# Clear browser cache (Ctrl+Shift+Delete)
```

---

## ✅ Checklist Setup

- [ ] PHP & Composer installed
- [ ] Node.js & npm installed
- [ ] .env configured dengan database
- [ ] `composer install` selesai
- [ ] `npm install` selesai
- [ ] Database created & migrated
- [ ] `php artisan key:generate` done
- [ ] `php artisan db:seed --class=RoleSeeder` done
- [ ] `npm run dev` running (Terminal 1)
- [ ] `php artisan serve` running (Terminal 2)
- [ ] Can access http://localhost:8000 (public page)
- [ ] Can access /login (login page)
- [ ] Can login with test user
- [ ] Dashboard loads correctly

---

## 🎉 Ready to Develop!

Ketika semua checklist selesai, Anda siap untuk:

1. ✏️ Edit React components (`resources/js/Components/`)
2. ✏️ Edit Pages (`resources/js/Pages/`)
3. ✏️ Edit Blade views (`resources/views/`)
4. ✏️ Create new routes (`routes/web.php`)
5. ✏️ Create controllers dan models

Semua changes akan auto-reload via Vite! 🔄

---

## 📖 Additional Resources

- [Laravel Docs](https://laravel.com/docs)
- [Inertia.js Docs](https://inertiajs.com)
- [React Docs](https://react.dev)
- [Tailwind CSS Docs](https://tailwindcss.com)
- [Vite Docs](https://vitejs.dev)

Happy Coding! 🚀
