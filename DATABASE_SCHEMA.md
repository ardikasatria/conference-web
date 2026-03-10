# 📊 Database Schema Documentation

## Overview
Database Conference Web Management terdiri dari 6 tabel utama yang saling terhubung untuk mengelola konferensi, pembicara, sesi, dan pendaftaran peserta.

---

## 📋 Tabel-Tabel

### 1. **users** (Default Laravel)
Tabel untuk menyimpan informasi pengguna (peserta dan admin).

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| name | String | Nama pengguna |
| email | String | Email (unique) |
| email_verified_at | Timestamp | Waktu verifikasi email |
| password | String | Password (hashed) |
| remember_token | String | Token untuk "Remember Me" |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

---

### 2. **conferences**
Tabel untuk menyimpan informasi konferensi/acara.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| name | String | Nama konferensi |
| description | Text | Deskripsi lengkap |
| start_date | DateTime | Tanggal mulai konferensi |
| end_date | DateTime | Tanggal berakhir konferensi |
| location | String | Lokasi konferensi |
| image | String | URL gambar konferensi |
| slug | String | URL slug (unique) |
| status | Enum | draft, published, ongoing, completed, cancelled |
| capacity | Integer | Kapasitas peserta |
| registration_fee | Decimal | Biaya pendaftaran (10,2) |
| contact_email | String | Email kontak |
| contact_phone | String | Nomor telepon kontak |
| terms_conditions | LongText | Syarat dan ketentuan |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- Banyak ke banyak dengan User (via registrations)
- Satu ke banyak dengan Session
- Satu ke banyak dengan Registration

---

### 3. **speakers**
Tabel untuk menyimpan informasi pembicara.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| name | String | Nama pembicara |
| email | String | Email (unique) |
| phone | String | Nomor telepon |
| bio | Text | Biografi singkat |
| image | String | URL foto profil |
| company | String | Perusahaan |
| position | String | Posisi/jabatan |
| website | String | Website pribadi |
| twitter | String | Handle Twitter |
| linkedin | String | Profil LinkedIn |
| status | Enum | active, inactive, banned |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- Banyak ke banyak dengan Session (via session_speaker)

---

### 4. **sessions**
Tabel untuk menyimpan informasi sesi/jadwal breakout sessions.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| conference_id | BigInt | Foreign Key ke conferences |
| title | String | Judul sesi |
| description | Text | Deskripsi sesi |
| start_time | DateTime | Waktu mulai sesi |
| end_time | DateTime | Waktu berakhir sesi |
| room | String | Ruangan/lokasi |
| capacity | Integer | Kapasitas peserta |
| status | Enum | scheduled, ongoing, completed, cancelled |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- Banyak ke satu dengan Conference
- Banyak ke banyak dengan Speaker (via session_speaker)
- Banyak ke banyak dengan Registration (via registration_sessions)

---

### 5. **registrations**
Tabel untuk menyimpan informasi pendaftaran peserta ke konferensi.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| conference_id | BigInt | Foreign Key ke conferences |
| user_id | BigInt | Foreign Key ke users |
| ticket_number | String | Nomor tiket (unique) |
| status | Enum | pending, confirmed, cancelled, no_show |
| registered_at | DateTime | Waktu pendaftaran |
| payment_date | DateTime | Tanggal pembayaran |
| amount_paid | Decimal | Jumlah dibayar (10,2) |
| payment_method | String | Metode pembayaran |
| invoice_number | String | Nomor invoice |
| notes | Text | Catatan tambahan |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |
| deleted_at | Timestamp | Soft delete |

**Constraints:**
- Unique: (conference_id, user_id)

**Relations:**
- Banyak ke satu dengan Conference
- Banyak ke satu dengan User
- Banyak ke banyak dengan Session (via registration_sessions)

---

### 6. **session_speaker** (Pivot Table)
Tabel pivot untuk menghubungkan pembicara dengan sesi.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| session_id | BigInt | Foreign Key ke sessions |
| speaker_id | BigInt | Foreign Key ke speakers |
| is_moderator | Boolean | Apakah moderator sesi |
| order | Integer | Urutan presentasi |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

**Constraints:**
- Unique: (session_id, speaker_id)

---

### 7. **registration_sessions** (Pivot Table)
Tabel pivot untuk menghubungkan peserta dengan sesi yang mereka daftar.

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| registration_id | BigInt | Foreign Key ke registrations |
| session_id | BigInt | Foreign Key ke sessions |
| attendance_status | Enum | registered, attended, absent |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

**Constraints:**
- Unique: (registration_id, session_id)

---

## 🔗 Entity Relationship Diagram (ERD)

```
Users
  ├── 1:N → Registrations
  └── M:N → Conferences (via Registrations)

Conferences
  ├── 1:N → Sessions
  ├── 1:N → Registrations
  └── M:N → Users (via Registrations)

Speakers
  └── M:N → Sessions (via session_speaker)

Sessions
  ├── N:1 → Conferences
  ├── M:N → Speakers (via session_speaker)
  └── M:N → Registrations (via registration_sessions)

Registrations
  ├── N:1 → Conferences
  ├── N:1 → Users
  └── M:N → Sessions (via registration_sessions)
```

---

## 🚀 Cara Menjalankan Migrations & Seeding

### Setup Database
```bash
# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Run seeder (opsional, untuk dummy data)
php artisan db:seed
```

### Reset Database (Development Only)
```bash
php artisan migrate:fresh --seed
```

---

## 📝 Soft Deletes
Tabel-tabel berikut mendukung **soft deletes**:
- conferences
- speakers  
- sessions
- registrations

Artinya, data tidak benar-benar dihapus tapi ditandai dengan `deleted_at` timestamp.

---

## 🔐 Data Integrity
- **Foreign Keys**: Semua relasi menggunakan foreign key constraints dengan `onDelete('cascade')`
- **Unique Constraints**: ticket_number, slug, email (speakers)
- **Composite Unique**: (conference_id, user_id), (session_id, speaker_id)

---

## 📊 Query Examples

### Get semua conferences dengan jumlah peserta
```php
Conference::withCount('registrations')->get();
```

### Get speakers dari sebuah sesi
```php
Session::find(1)->speakers;
```

### Get sesi yang didaftar oleh seorang peserta
```php
$registration = Registration::find(1);
$registration->sessions;
```

### Get konferensi yang telah diikuti user
```php
$user = User::find(1);
$user->conferences;
```

---

## 📌 Notes
- Semua timestamps menggunakan timezone UTC
- Password disimpan dengan bcrypt hashing
- Image fields menyimpan URL/path dari file
- Status fields menggunakan ENUM untuk konsistensi data
