# 👥 Role & Permission System Documentation

## Overview

Conference Web Management menggunakan sistem **Role-Based Access Control (RBAC)** yang fleksibel dengan 3 role utama:

1. **Participant** - Peserta konferensi regular
2. **Admin** - Administrator/pengelola konferensi  
3. **Reviewer** - Peserta yang sudah di-approve menjadi reviewer (untuk review papers/submissions)

---

## 🔑 Role Definitions

### 1. **Participant** (Peserta)
- Role default untuk setiap user yang register ke conference
- Bisa mengakses:
  - Profile pribadi
  - List konferensi yang diikuti
  - Schedule sessions
  - Materi konferensi
  - Bisa apply menjadi reviewer

**Flow:**
```
User Register → Assigned "participant" role for that conference
```

### 2. **Admin** (Administrator)
- Pengelola konferensi/platform
- Bisa mengakses:
  - Dashboard admin
  - Manajemen conferences (create, edit, delete)
  - Manajemen speakers & sessions
  - Review reviewer applications
  - Manajemen registrations & payments
  - Reports & analytics

**Assignment:** Manual oleh super admin atau staff

### 3. **Reviewer** (Reviewer)
- Peserta yang sudah di-approve menjadi reviewer
- Bisa mengakses:
  - Semua fitur participant
  - Dashboard reviewer
  - Submissions/papers untuk di-review
  - Submit review & feedback
  - View review results

**Flow:**
```
Participant Apply → Admin Review Application → Admin Approve/Reject
   (if approved) → Assigned "reviewer" role for that conference
```

---

## 📊 Database Structure

### Tables

```
roles
├── id
├── name (unique) - 'participant', 'admin', 'reviewer'
├── display_name
├── description
└── timestamps

user_roles (Pivot)
├── id
├── user_id ─────┐
├── role_id ─────├─ Menghubungkan user dengan role
├── conference_id (nullable) - Role bisa global atau per-conference
└── timestamps
  └── Unique: (user_id, role_id, conference_id)

reviewer_applications
├── id
├── user_id ─────────────── User yang apply
├── conference_id ───────── Conference yang di-apply
├── motivation ──────────── Alasan ingin jadi reviewer
├── expertise ──────────── Bidang keahlian
├── status ─────────────── pending, approved, rejected
├── admin_notes ────────── Catatan dari admin
├── reviewed_by ────────── User (admin) yang review
├── reviewed_at ────────── Kapan di-review
└── timestamps + soft_deletes
  └── Unique: (user_id, conference_id)
```

---

## 🔐 Model Methods

### User Model

```php
// Check role
$user->hasRole('admin');                          // Global admin check
$user->hasRole('reviewer', $conferenceId);        // Reviewer untuk conference tertentu
$user->isAdmin();
$user->isReviewer($conferenceId);
$user->isParticipant($conferenceId);

// Get roles
$user->roles();                                   // Semua roles
$user->rolesInConference($conferenceId);          // Roles di conference tertentu

// Assign/remove role
$conference = Conference::find(1);
$user->assignRole($role, $conference);            // Assign per-conference
$user->removeRole($role, $conference);

// Reviewer applications
$user->reviewerApplications();                    // Applications dari user ini
$user->reviewedApplications();                    // Applications yang di-review oleh admin user ini
```

### ReviewerApplication Model

```php
$application->approve($adminId, 'notes');    // Approve & auto-assign reviewer role
$application->reject($adminId, 'notes');     // Reject aplikasi

$application->user;                           // User yang apply
$application->conference;                     // Conference yang di-apply
$application->reviewer;                       // Admin yang review
```

### Conference Model

```php
$conference->reviewerApplications();      // Semua reviewer applications
$conference->pendingReviewerApplications();  // Yang belum di-review
$conference->usersWithRole('reviewer');   // Users dengan role reviewer di conference ini
```

---

## 🎯 Example Usage

### Participant Apply Menjadi Reviewer

```php
// Step 1: Participant create application
$application = ReviewerApplication::create([
    'user_id' => auth()->id(),
    'conference_id' => 1,
    'motivation' => 'Saya ingin berkontribusi sebagai reviewer...',
    'expertise' => 'Machine Learning, AI',
]);

// Step 2: Admin view pending applications
$conference = Conference::find(1);
$pendingApps = $conference->pendingReviewerApplications()->get();

// Step 3: Admin approve
$application->approve(
    adminId: auth()->id(),
    notes: 'Silakan mulai review papers'
);
// Automatically assigns 'reviewer' role to user for this conference

// Step 4: Check user roles
$user = User::find($application->user_id);
$user->hasRole('reviewer', $conference->id);  // true
$user->rolesInConference($conference->id);    // ['participant', 'reviewer']
```

### Assign Admin Role

```php
$user = User::find(1);
$adminRole = Role::where('name', 'admin')->first();

// Global admin (untuk semua conferences)
$user->assignRole($adminRole);

// Or for specific conference
$conference = Conference::find(1);
$user->assignRole($adminRole, $conference);

// Check
$user->isAdmin();                    // true (global)
$user->isAdmin($conference->id);     // true (untuk conference tertentu)
```

### List Reviewers untuk Conference

```php
$conference = Conference::find(1);
$reviewers = $conference->usersWithRole('reviewer');

// Or manually
$reviewerRole = Role::where('name', 'reviewer')->first();
$reviewers = $reviewerRole->usersInConference($conference);
```

---

## 📋 Migration & Seeding

### Default Roles (Auto-created in DatabaseSeeder)

```php
// participant
{
    'name' => 'participant',
    'display_name' => 'Participant',
    'description' => 'Conference participant/attendee'
}

// admin
{
    'name' => 'admin',
    'display_name' => 'Administrator',
    'description' => 'Conference administrator'
}

// reviewer
{
    'name' => 'reviewer',
    'display_name' => 'Reviewer',
    'description' => 'Content/paper reviewer'
}
```

### Setup Database

```bash
# Run migrations (include role tables)
php artisan migrate

# Run seeder (creates default roles & test data)
php artisan db:seed

# Or fresh start
php artisan migrate:fresh --seed
```

---

## 🔗 Conference Registration Flow dengan Roles

```
User Register to Conference
       ↓
Create Registration Record
       ↓
Assign "participant" role for this conference
       ↓
Participant dapat akses conference features
       ↓
(Optional) Participant apply as reviewer
       ↓
Admin review application
       ↓
If Approved: Assign "reviewer" role untuk conference
If Rejected: Keep "participant" role only
```

---

## 📊 Role Scope

Sistem ini mendukung **dua scope** untuk roles:

### 1. **Global Roles** (conference_id = null)
```php
// Admin global (super admin)
$user->assignRole($adminRole);  // Tanpa conference_id parameter

$user->hasRole('admin');        // Check global admin
$user->isAdmin();               // Alias
```

### 2. **Conference-Specific Roles** (conference_id = set)
```php
// Admin untuk conference tertentu
$user->assignRole($adminRole, $conference);

$user->hasRole('admin', $conference->id);   // Check untuk conference spesifik
$user->rolesInConference($conference->id);  // Get semua roles di conference ini
```

---

## ⚙️ Practical Implementation Notes

### For Frontend (React)

```javascript
// Check if user can approve reviewer applications
if (user.hasRole('admin')) {
    // Show admin dashboard
}

// Check if user is reviewer for specific conference
if (user.hasRole('reviewer', conferenceId)) {
    // Show reviewer dashboard
}
```

### For Backend (Controllers)

```php
// Middleware to check admin
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/reviewer-applications', [ReviewerController::class, 'index']);
});

// Or in controller
public function approveReviewer(ReviewerApplication $application)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }
    
    $application->approve(auth()->id());
}
```

---

## 🚀 Future Enhancements

Sistem ini mudah di-extend untuk:
- ✅ Permission-level control (granular permissions for each role)
- ✅ Custom roles per conference
- ✅ Role expiration/schedule
- ✅ Reviewer scoring/rating system
- ✅ Role history tracking

---

## 📝 File Structure

```
conference-web/
├── app/Models/
│   ├── User.php (updated dengan role methods)
│   ├── Role.php (new)
│   └── ReviewerApplication.php (new)
├── database/migrations/
│   ├── ...0009_create_roles_table.php
│   ├── ...0010_create_user_roles_table.php
│   └── ...0011_create_reviewer_applications_table.php
├── database/factories/
│   └── RoleFactory.php
└── database/seeders/
    ├── DatabaseSeeder.php (updated)
    └── ConferenceSeeder.php (updated)
```

---

## ✅ Summary

Sistem Role & Permission yang telah dibuat:

| Feature | Status | Details |
|---------|--------|---------|
| 3 Default Roles | ✅ | participant, admin, reviewer |
| Role Assignment | ✅ | Global & per-conference |
| Reviewer Application | ✅ | Workflow dengan admin approval |
| Role Checking Methods | ✅ | hasRole, isAdmin, isReviewer, etc |
| Database Schema | ✅ | roles, user_roles, reviewer_applications |
| Auto-seeding | ✅ | Default roles created automatically |
| Database Integrity | ✅ | Foreign keys & unique constraints |

Semua siap untuk diintegrasikan dengan frontend React! 🚀
