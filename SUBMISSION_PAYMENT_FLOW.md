# 📝 Abstract Submission & Payment Flow

## Overview

Alur lengkap untuk user mendaftar conference:
1. **Register** - Daftar sebagai participant
2. **Login** - Masuk ke akun
3. **Pilih Paket** - Pilih conference package (Silver, Gold, Platinum)
4. **Submit Abstract** - User mengisi form abstract/paper seperti di abstracts.model.js
5. **Admin Review** - Admin review abstract (approve/reject)
6. **Bayar Paket** - Jika approved, user bayar paket ke rekening yang tertera
7. **Konfirmasi Pembayaran** - User submit bukti transfer
8. **Admin Verifikasi** - Admin verifikasi & tandai sudah dibayar
9. **Selesai** - Status user berubah "Paid" dan dapat akses conferencenya

---

## 📊 Database Schema

### **submissions** Table
Menyimpan abstract/paper yang di-submit user

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| registration_id | BigInt | FK ke registrations |
| user_id | BigInt | FK ke users |
| conference_id | BigInt | FK ke conferences |
| title | String | Abstract title |
| abstract | LongText | Abstract content |
| keywords | JSON | Array of keywords |
| presenter_name | String | Nama presenter |
| presenter_email | String | Email presenter |
| co_authors | JSON | Array of co-authors |
| topic | String | Topik/kategori |
| subtopics | JSON | Sub-topik/bidang khusus |
| file_path | String | Path ke file abstract (PDF/DOC) |
| status | Enum | draft, submitted, approved, rejected |
| submission_notes | Text | Notes dari review |
| submitted_at | Timestamp | Waktu submit |
| created_at | Timestamp | Waktu buat |
| updated_at | Timestamp | Waktu update |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- N:1 → Registration
- N:1 → User
- N:1 → Conference
- 1:N → SubmissionReview

---

### **submission_reviews** Table
Review dari admin untuk setiap submission

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| submission_id | BigInt | FK ke submissions |
| reviewed_by | BigInt | FK ke users (admin/reviewer) |
| conference_id | BigInt | FK ke conferences |
| status | Enum | pending, approved, rejected, revision_requested |
| comments | Text | Komentar dari reviewer |
| rating | Integer | Rating 1-5 (optional) |
| revision_notes | Text | Catatan revisi jika diminta |
| requires_revision | Boolean | Apakah butuh revisi |
| reviewed_at | Timestamp | Waktu review |
| created_at | Timestamp | Waktu buat |
| updated_at | Timestamp | Waktu update |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- N:1 → Submission
- N:1 → User (reviewer/admin)
- N:1 → Conference

---

### **payments** Table
Tracking pembayaran paket conference

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| registration_id | BigInt | FK ke registrations |
| package_id | BigInt | FK ke packages |
| user_id | BigInt | FK ke users |
| conference_id | BigInt | FK ke conferences |
| amount | Decimal(10,2) | Amount to pay (= package price) |
| status | Enum | pending, awaiting_confirmation, confirmed, paid, cancelled |
| bank_name | String | Bank tujuan (e.g., BCA, Mandiri) |
| account_number | String | Nomor rekening tujuan |
| account_holder | String | Nama rekening tujuan |
| payment_invoice_number | String | Invoice number untuk tracking |
| due_date | Timestamp | Batas pembayaran |
| confirmed_at | Timestamp | Waktu payment received |
| paid_at | Timestamp | Waktu marked as paid |
| created_at | Timestamp | Waktu buat |
| updated_at | Timestamp | Waktu update |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- 1:1 → Registration
- N:1 → Package
- N:1 → User
- N:1 → Conference
- 1:N → PaymentConfirmation

---

### **payment_confirmations** Table
Konfirmasi pembayaran dari user (bukti transfer)

| Column | Type | Description |
|--------|------|-------------|
| id | BigInt | Primary Key |
| payment_id | BigInt | FK ke payments |
| registration_id | BigInt | FK ke registrations |
| user_id | BigInt | FK ke users |
| conference_id | BigInt | FK ke conferences |
| bank_name | String | Bank pengirim (yang user gunakan) |
| sender_name | String | Nama pengirim |
| transaction_date | Date | Tanggal transfer |
| reference_number | String | Nomor referensi/bukti transfer (UNIQUE) |
| amount_transferred | Decimal(10,2) | Jumlah yg ditransfer |
| proof_image_path | String | Path ke screenshot/bukti transfer |
| notes | Text | Catatan dari user |
| status | Enum | pending, approved, rejected |
| admin_notes | Text | Catatan dari admin |
| verified_by | BigInt | FK ke users (admin verifier) |
| verified_at | Timestamp | Waktu diverifikasi admin |
| created_at | Timestamp | Waktu buat |
| updated_at | Timestamp | Waktu update |
| deleted_at | Timestamp | Soft delete |

**Relations:**
- N:1 → Payment
- N:1 → Registration
- N:1 → User (yang submit)
- N:1 → Conference
- N:1 → User (verified_by, admin verifier)

---

### **registrations** Table (Updated Columns)

Kolom baru ditambahkan:

| Column | Type | Description |
|--------|------|-------------|
| ... | ... | ... existing columns ... |
| submission_id | BigInt | FK ke submissions (nullable) |
| submission_status | Enum | not_required, pending_submission, pending_review, approved, rejected |
| payment_status | Enum | not_required, pending_payment, awaiting_confirmation, confirmed, paid, cancelled |

---

## 🔑 Model Methods

### Submission Model

```php
// Status checking
$submission->isApproved();                           // Check jika approved
$submission->isReviewed();                           // Check jika di-review

// Mark status
$submission->markAsSubmitted();                      // Update status to submitted + timestamp
$submission->approve($notes);                        // Approve & update registration
$submission->reject($notes);                         // Reject & update registration

// Relations
$submission->registration;                           // Get registration
$submission->user;                                   // Get user who submitted
$submission->conference;                             // Get conference
$submission->reviews();                              // Get all reviews
$submission->latestReview();                         // Get latest review
```

### SubmissionReview Model

```php
// Actions
$review->approve($comments);                         // Approve submission
$review->reject($comments);                          // Reject submission
$review->requestRevision($notes);                    // Request revision

// Relations
$review->submission;                                 // Get submission
$review->reviewer;                                   // Get admin who reviewed
$review->conference;                                 // Get conference
```

### Payment Model

```php
// Status checking
$payment->isPendingConfirmation();                   // Check if awaiting confirmation

// Actions
$payment->markAsPaid($notes);                        // Mark as paid (by admin)
$payment->requestConfirmation();                     // Update status to awaiting_confirmation

// Relations
$payment->registration;                              // Get registration
$payment->package;                                   // Get package
$payment->user;                                      // Get user
$payment->conference;                                // Get conference
$payment->confirmations();                           // Get all confirmations
$payment->latestConfirmation();                      // Get latest confirmation
```

### PaymentConfirmation Model

```php
// Actions (by admin)
$confirmation->approve($notes);                      // Approve & mark payment as paid
$confirmation->reject($notes);                       // Reject confirmation

// Relations
$confirmation->payment;                              // Get payment
$confirmation->registration;                         // Get registration
$confirmation->user;                                 // Get user who submitted
$confirmation->conference;                           // Get conference
$confirmation->verifier;                             // Get admin who verified
```

### Registration Model (Updated)

```php
// Check requirements
$registration->requiresSubmission();                 // Check if submission required
$registration->requiresPayment();                    // Check if payment required
$registration->isComplete();                         // All requirements met?

// Progress
$registration->getProgressPercentage();              // Get progress % (0-100)

// Relations
$registration->submission;                           // Get submission
$registration->payment;                              // Get payment (hasOne)
$registration->paymentConfirmations();               // Get payment confirmations (hasMany)
```

---

## 📝 Complete Flow Example

### Step 1: User Register & Login
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
]);

auth()->login($user);
```

### Step 2: User Browse Conferences & Select Package
```php
$conference = Conference::find(1);
$packages = $conference->activePackages()->with('features')->get();

// User pilih Gold package
$selectedPackage = $packages->find(2); // Gold
```

### Step 3: Create Registration
```php
$registration = Registration::create([
    'conference_id' => $conference->id,
    'user_id' => auth()->id(),
    'package_id' => $selectedPackage->id,
    'ticket_number' => 'TK-' . uniqid(),
    'status' => 'pending',
    'registered_at' => now(),
    'submission_status' => 'pending_submission', // Require abstract submission
    'payment_status' => 'pending_payment',       // Require payment
]);

// Track in package
$selectedPackage->increment('current_registered');
```

### Step 4: User Submit Abstract
```php
$submission = Submission::create([
    'registration_id' => $registration->id,
    'user_id' => auth()->id(),
    'conference_id' => $conference->id,
    'title' => 'Machine Learning for Healthcare',
    'abstract' => 'This paper discusses...',
    'keywords' => ['ML', 'Healthcare', 'AI'],
    'presenter_name' => 'John Doe',
    'presenter_email' => 'john@example.com',
    'co_authors' => ['Jane Smith', 'Bob Johnson'],
    'topic' => 'Computer Science',
    'subtopics' => ['AI', 'Machine Learning'],
    'file_path' => 'submissions/abstract_123.pdf',
    'status' => 'draft',
]);

// User submit
$submission->markAsSubmitted();
$registration->update(['submission_status' => 'pending_review']);
```

### Step 5: Admin Review Abstract
```php
// Admin lihat pending submissions
$pendingSubmissions = Submission::where('status', 'submitted')
    ->where('conference_id', $conference->id)
    ->get();

// Admin approve submission
$review = SubmissionReview::create([
    'submission_id' => $submission->id,
    'reviewed_by' => auth()->id(), // Admin ID
    'conference_id' => $conference->id,
    'status' => 'approved',
    'comments' => 'Great research work!',
    'rating' => 5,
]);

$review->approve('Approved for presentation');
// Auto update: submission.status = 'approved'
// Auto update: registration.submission_status = 'approved'
```

### Step 6: Create Payment Record
```php
// After approval, create payment record automatically
$payment = Payment::create([
    'registration_id' => $registration->id,
    'package_id' => $selectedPackage->id,
    'user_id' => auth()->id(),
    'conference_id' => $conference->id,
    'amount' => $selectedPackage->price, // Rp 250,000
    'status' => 'pending',
    'bank_name' => 'Bank Central Asia',
    'account_number' => '1234567890',
    'account_holder' => 'Conference Admin',
    'due_date' => now()->addDays(7),
    'payment_invoice_number' => 'INV-' . uniqid(),
]);

// User lihat payment details
// Frontend shows:
// "Silakan transfer Rp 250,000 ke rekening BCA xxx
// Setelah transfer, submit bukti pembayaran"
```

### Step 7: User Submit Bukti Transfer
```php
// User submit payment confirmation
$confirmation = PaymentConfirmation::create([
    'payment_id' => $payment->id,
    'registration_id' => $registration->id,
    'user_id' => auth()->id(),
    'conference_id' => $conference->id,
    'bank_name' => 'BCA',
    'sender_name' => 'John Doe',
    'transaction_date' => '2024-03-01',
    'reference_number' => 'BCA20240301123456', // Unique
    'amount_transferred' => 250000,
    'proof_image_path' => 'proof/payment_123.jpg', // Screenshot transfer
    'notes' => 'Sudah transfer via mobile banking',
    'status' => 'pending',
]);

// Registration update
$registration->update(['payment_status' => 'awaiting_confirmation']);
$payment->requestConfirmation();
```

### Step 8: Admin Verify Payment
```php
// Admin lihat pending confirmations
$pendingConfirmations = PaymentConfirmation::where('status', 'pending')
    ->where('conference_id', $conference->id)
    ->get();

// Admin verify
$confirmation->approve('Pembayaran diterima');
// Auto actions:
// - confirmation.status = 'approved'
// - payment.status = 'paid'
// - payment.paid_at = now()
// - registration.payment_status = 'paid'
```

### Step 9: Check Complete Registration
```php
$registration->refresh();

$registration->isComplete();              // true
$registration->getProgressPercentage();   // 100

// User dapat akses penuh conference
// Show: ✓ Registered
//       ✓ Abstract Approved
//       ✓ Payment Confirmed
```

---

## 📊 Status Flow Diagram

```
REGISTRATION CREATED
├── submission_status: pending_submission
└── payment_status: pending_payment
    ↓
USER SUBMIT ABSTRACT
├── submission.status: submitted
└── registration.submission_status: pending_review
    ↓
ADMIN REVIEW ABSTRACT
├── IF APPROVED:
│   ├── submission.status: approved
│   ├── registration.submission_status: approved
│   └── payment.status: created (pending)
│       ↓
│   PAYMENT CREATED
│   └── registration.payment_status: pending_payment
│       ↓
│   USER PAY & SUBMIT PROOF
│   ├── payment_confirmation.status: pending
│   └── registration.payment_status: awaiting_confirmation
│       ↓
│   ADMIN VERIFY PAYMENT
│   ├── payment_confirmation.status: approved
│   ├── payment.status: paid
│   └── registration.payment_status: paid
│       ↓
│   REGISTRATION COMPLETE ✓
│
└── IF REJECTED:
    ├── submission.status: rejected
    ├── registration.submission_status: rejected
    └── registration.payment_status: cancelled
```

---

## 🔄 Queries Contoh

### Get pending submissions untuk admin
```php
$submissions = Submission::where('status', 'submitted')
    ->where('conference_id', $conferenceId)
    ->with('user', 'registration')
    ->paginate(10);
```

### Get pending payment confirmations
```php
$confirmations = PaymentConfirmation::where('status', 'pending')
    ->where('conference_id', $conferenceId)
    ->with('user', 'payment')
    ->paginate(10);
```

### Get user registration status
```php
$registration = Registration::where('user_id', auth()->id())
    ->where('conference_id', $conferenceId)
    ->with('submission', 'payment', 'paymentConfirmations')
    ->first();

echo "Submission: " . $registration->submission_status;   // approved
echo "Payment: " . $registration->payment_status;         // paid
echo "Progress: " . $registration->getProgressPercentage(); // 100%
```

### Get conference stats
```php
$stats = [
    'total_submissions' => Submission::where('conference_id', $conferenceId)->count(),
    'approved_submissions' => Submission::where('conference_id', $conferenceId)->where('status', 'approved')->count(),
    'pending_payments' => Payment::where('conference_id', $conferenceId)->where('status', 'pending')->count(),
    'paid_registrations' => Registration::where('conference_id', $conferenceId)->where('payment_status', 'paid')->count(),
];
```

---

## 📋 File Structure

```
conference-web/
├── app/Models/
│   ├── Submission.php (NEW)
│   ├── SubmissionReview.php (NEW)
│   ├── Payment.php (NEW)
│   ├── PaymentConfirmation.php (NEW)
│   └── Registration.php (UPDATED)
├── database/migrations/
│   ├── ...0015_create_submissions_table.php
│   ├── ...0016_create_submission_reviews_table.php
│   ├── ...0017_create_payments_table.php
│   ├── ...0018_create_payment_confirmations_table.php
│   └── ...0019_alter_registrations_add_submission_payment_fields.php
└── database/seeders/
    └── ConferenceSeeder.php (can include sample submissions)
```

---

## ✅ Summary

| Feature | Status | Details |
|---------|--------|---------|
| Abstract submission | ✅ | Full form with file upload |
| Admin review workflow | ✅ | With approve/reject/revision |
| Payment tracking | ✅ | Auto-create on approval |
| Bank account display | ✅ | Show to user for transfer |
| Payment confirmation | ✅ | User submit bukti transfer |
| Admin verification | ✅ | Verify & mark as paid |
| Status tracking | ✅ | Multi-step progress |
| Completion checking | ✅ | All requirements met? |
| Progress tracking | ✅ | Percentage calculation |

Alur lengkap siap untuk diintegrasikan dengan React frontend! 🚀
