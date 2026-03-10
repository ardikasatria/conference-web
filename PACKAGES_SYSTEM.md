# 🎫 Conference Packages System

## Overview

Sistem paket conference memungkinkan admin untuk membuat berbagai tingkatan paket registrasi dengan fitur dan harga berbeda. Setiap peserta dapat memilih paket yang paling sesuai dengan kebutuhan mereka.

---

## 📊 Konsep Dasar

### Struktur Paket

Setiap conference dapat memiliki **multiple packages** dengan konfigurasi berbeda:

```
Conference "Tech Summit 2024"
├── Package: Silver       (Price: Rp 100,000)
│   ├── Features: Basic access, Materials, Lunch
│   └── Capacity: 100 slots
├── Package: Gold         (Price: Rp 250,000)
│   ├── Features: Full access, Materials, All meals, VIP dinner, Certificate
│   └── Capacity: 50 slots
└── Package: Platinum     (Price: Rp 500,000)
    ├── Features: Full access, Premium meals, VIP treatment
    └── Capacity: 20 slots
```

### Fitur Utama

- ✅ Unlimited packages per conference
- ✅ Flexible pricing & capacity per package
- ✅ Detailed feature list per package
- ✅ Capacity tracking (current_registered)
- ✅ Package availability check
- ✅ Soft delete untuk package management

---

## 📂 Database Schema

### Tables

#### 1. **packages**
Tabel untuk menyimpan paket conference.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| conference_id | BigInt | FK ke conferences |
| name | String | Silver, Gold, Platinum, dll |
| description | Text | Deskripsi paket |
| price | Decimal(10,2) | Harga paket |
| max_capacity | Integer | Max peserta (nullable = unlimited) |
| current_registered | Integer | Tracking peserta terdaftar |
| benefits | LongText | JSON array atau plain text benefits |
| status | Enum | active, inactive |
| order | Integer | Urutan display (for sorting) |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- Banyak ke satu dengan Conference
- Satu ke banyak dengan PackageFeature
- Satu ke banyak dengan Registration

---

#### 2. **package_features**
Tabel untuk detail fitur setiap paket.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| package_id | BigInt | FK ke packages |
| feature_name | String | Nama fitur (e.g., "Coffee break", "VIP dinner") |
| description | Text | Deskripsi detail fitur |
| is_included | Boolean | Apakah included (optional) |
| order | Integer | Urutan display |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

**Relations:**
- Banyak ke satu dengan Package

---

#### 3. **registrations** (Updated)

Kolom baru ditambahkan:

| Column | Type | Description |
|--------|------|-------------|
| ... | ... | ... |
| package_id | BigInt | FK ke packages (nullable) |

---

## 🔑 Model Methods

### Package Model

```php
// Relasi
$package->conference();        // Get conference
$package->features();          // Get all features
$package->registrations();     // Get all registrations

// Capacity management
$package->hasAvailableCapacity();      // Check jika masih ada slot
$package->getRemainingCapacity();      // Get sisa kapasitas

// Register/unregister
$package->registerParticipant($registration);      // Register dengan auto capacity increment
$package->unregisterParticipant($registration);    // Unregister dengan auto capacity decrement
```

### Registration Model (Updated)

```php
$registration->package;        // Get selected package
$registration->package()->first();     // dengan relasi
```

### Conference Model (Updated)

```php
$conference->packages();           // Get all packages
$conference->activePackages();     // Get active packages only
```

---

## 📝 Usage Examples

### Admin: Create Package untuk Conference

```php
$conference = Conference::find(1);

$package = Package::create([
    'conference_id' => $conference->id,
    'name' => 'Gold',
    'description' => 'Premium package dengan akses penuh',
    'price' => 250000,
    'max_capacity' => 50,
    'status' => 'active',
    'order' => 2,
    'benefits' => [
        'Akses penuh ke semua sesi',
        'Makan siang dan kopi',
        'VIP dinner',
        'Sertifikat',
    ],
]);

// Add features
PackageFeature::create([
    'package_id' => $package->id,
    'feature_name' => 'VIP Dinner',
    'description' => 'Makan malam eksklusif dengan pembicara',
    'is_included' => true,
    'order' => 1,
]);

PackageFeature::create([
    'package_id' => $package->id,
    'feature_name' => 'Certificate',
    'description' => 'Sertifikat resmi kehadiran',
    'is_included' => true,
    'order' => 2,
]);
```

### Frontend: Display Available Packages

```php
// Get active packages untuk conference
$packages = $conference->activePackages()
    ->with('features')
    ->get();

// Foreach package
foreach ($packages as $package) {
    echo $package->name . " - Rp " . number_format($package->price) . "\n";
    echo "Kapasitas: " . $package->getRemainingCapacity() . " tersisa\n";
    
    foreach ($package->features as $feature) {
        echo "  ✓ " . $feature->feature_name . "\n";
    }
}
```

### Participant: Register dengan Package

```php
$user = Auth::user();
$conference = Conference::find(1);
$selectedPackage = Package::find(3); // Gold package

// Check capacity
if (!$selectedPackage->hasAvailableCapacity()) {
    abort(400, 'Paket ini sudah penuh');
}

// Create registration
$registration = Registration::create([
    'user_id' => $user->id,
    'conference_id' => $conference->id,
    'package_id' => $selectedPackage->id,
    'ticket_number' => 'TK-' . uniqid(),
    'status' => 'pending',
    'registered_at' => now(),
    'amount_paid' => $selectedPackage->price,
]);

// Or using helper method
$selectedPackage->registerParticipant($registration);

// User dipilih paket Gold dengan harga Rp 250,000
```

### Admin: Unregister Participant

```php
$registration = Registration::find(1);
$package = $registration->package;

// Unregister dari package
$package->unregisterParticipant($registration);

// Atau langsung update
$registration->update([
    'package_id' => null,
    'status' => 'cancelled',
]);
```

### Admin: View Registration by Package

```php
$package = Package::find(1);

// Get all registrations untuk paket ini
$registrations = $package->registrations()
    ->with('user', 'conference')
    ->get();

// Get total revenue dari paket ini
$totalRevenue = $package->registrations()
    ->where('status', 'confirmed')
    ->sum('amount_paid');
```

---

## 📊 Queries Contoh

### Get packages dengan occupancy rate

```php
$packages = Conference::find(1)
    ->packages()
    ->with('features')
    ->get()
    ->map(function($package) {
        $occupancy = 0;
        if ($package->max_capacity) {
            $occupancy = ($package->current_registered / $package->max_capacity) * 100;
        }
        return array_merge(
            $package->toArray(),
            ['occupancy_rate' => $occupancy]
        );
    });
```

### Get packages dengan detailed stats

```php
$conference = Conference::find(1);

$stats = $conference->packages()
    ->select('id', 'name', 'price', 'max_capacity', 'current_registered')
    ->withCount('registrations')
    ->withSum('registrations', 'amount_paid')
    ->get()
    ->map(function($package) {
        return [
            'name' => $package->name,
            'price' => $package->price,
            'capacity' => $package->max_capacity ?? 'Unlimited',
            'registered' => $package->current_registered,
            'available' => $package->getRemainingCapacity(),
            'total_revenue' => $package->registrations_sum_amount_paid ?? 0,
        ];
    });
```

---

## 🎯 Workflow: dari User Perspective

```
1. User browse conference
        ↓
2. Lihat available packages (Silver, Gold, Platinum)
        ↓
3. Baca fitur setiap paket
        ↓
4. Pilih paket favorit
        ↓
5. Check kapasitas (masih ada slot?)
        ↓
6. Register dengan package terpilih
        ↓
7. System:
   - Create registration record
   - Set package_id
   - Increment package.current_registered
   - Set amount_paid = package.price
        ↓
8. User dapat ticket & akses sesuai paket
```

---

## 📋 Workflow: dari Admin Perspective

```
1. Create conference
        ↓
2. Create packages untuk conference
   - Silver: Rp 100K, Capacity 100
   - Gold: Rp 250K, Capacity 50
   - Platinum: Rp 500K, Capacity 20
        ↓
3. Add features untuk setiap package
        ↓
4. Set status = active
        ↓
5. Monitor registrations & occupancy
        ↓
6. Manage participants:
   - View by package
   - Track revenue per package
   - Handle cancellations
        ↓
7. Optional: Adjust packages (add/remove/modify)
```

---

## 🔄 Migrasi dari Database Lama

Jika sebelumnya tidak ada packages:

```php
// Migration untuk add packages ke existing conferences
// Buat default package untuk setiap conference
foreach (Conference::all() as $conference) {
    Package::create([
        'conference_id' => $conference->id,
        'name' => 'Standard',
        'price' => $conference->registration_fee ?? 0,
        'max_capacity' => $conference->capacity,
        'status' => 'active',
        'order' => 1,
    ]);
}
```

---

## 📝 File Structure

```
conference-web/
├── app/Models/
│   ├── Package.php (NEW)
│   ├── PackageFeature.php (NEW)
│   └── Registration.php (UPDATED - add package_id)
├── database/migrations/
│   ├── ...0012_create_packages_table.php
│   ├── ...0013_create_package_features_table.php
│   └── ...0014_alter_registrations_add_package_id.php
├── database/factories/
│   └── PackageFactory.php
└── database/seeders/
    └── ConferenceSeeder.php (UPDATED - create packages with features)
```

---

## ✅ Summary

| Feature | Status | Details |
|---------|--------|---------|
| Multiple packages per conference | ✅ | Unlimited paket |
| Flexible pricing | ✅ | Customizable price |
| Capacity management | ✅ | Track filled slots |
| Feature per package | ✅ | Detailed benefits |
| Registration with package | ✅ | Link registration to package |
| Soft delete | ✅ | Archive old packages |
| Seeding with features | ✅ | Auto-generate sample data |
| Database performance | ✅ | Indexed foreign keys |

Siap untuk digunakan di frontend! 🚀
