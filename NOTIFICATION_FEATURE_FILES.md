# Daftar File Notifikasi Pengingat Peminjaman APD

## Ringkasan Fitur

Sistem notifikasi otomatis untuk pengingat pengembalian APD dengan 3 jenis:
1. **H-1** — Reminder sehari sebelum tanggal kembali
2. **Jatuh Tempo** — Notifikasi pada hari tanggal kembali
3. **Terlambat** — Notifikasi jika sudah melewati tanggal kembali

Notifikasi dikirim via:
- **WhatsApp** ke peminjam dan semua Admin K3
- **Email** ke peminjam (via Laravel notification)
- **Database notification** (disimpan di table `notifications`)

---

## File yang Dibuat (4 files)

### 1. Migration
**File:** `database/migrations/2026_05_09_063846_add_reminder_columns_to_peminjaman_headers.php`
- Menambahkan 3 kolom timestamp untuk tracking notifikasi:
  - `reminder_h1_sent_at`
  - `reminder_jatuh_tempo_sent_at`
  - `reminder_terlambat_last_sent_at`
- Mencegah spam notifikasi (hanya kirim sekali per tipe)

### 2. Notification Class
**File:** `app/Notifications/PeminjamanReminderNotification.php`
- Mengirim email dan database notification ke peminjam
- Parameter: PeminjamanHeader, tipe, itemList
- Supported channels: `mail`, `database`
- Pesan berbeda untuk tiap tipe (reminder_h1, jatuh_tempo, terlambat)

### 3. Console Command
**File:** `app/Console/Commands/CheckPeminjamanReminder.php`
- Command: `k3:check-peminjaman-reminder`
- Melakukan 3 query berdasarkan status `approved`:
  - Query 1: `tanggal_kembali_rencana = tomorrow` + `reminder_h1_sent_at IS NULL`
  - Query 2: `tanggal_kembali_rencana = today` + `reminder_jatuh_tempo_sent_at IS NULL`
  - Query 3: `tanggal_kembali_rencana < today` (selalu cek, tidak ada tracking untuk avoid spam karena penting)
- Mengirim WA ke peminjam (via WhatsAppService)
- Mengirim email + database notif ke peminjam
- Mengirim WA ke semua admin K3 (dengan role `admin_k3`)
- Membedakan pesan untuk peminjam dan admin
- Update tracking timestamp setelah notifikasi dikirim

### 4. Schedule Registration
**File:** `routes/console.php`
- Menambahkan 3 schedule harian:
  - `08:00` — Reminder H-1 dan cek terlambat
  - `13:00` — Repeat reminder untuk yang terlambat
  - `15:30` — Last call untuk jatuh tempo hari ini

---

## File yang Diubah (2 files)

### 1. Model PeminjamanHeader
**File:** `app/Models/PeminjamanHeader.php`
**Perubahan:**
- Tambah 3 field ke `$fillable`:
  - `'reminder_h1_sent_at'`
  - `'reminder_jatuh_tempo_sent_at'`
  - `'reminder_terlambat_last_sent_at'`
- Tambah casting ke `$casts`:
  - `'reminder_h1_sent_at' => 'datetime'`
  - `'reminder_jatuh_tempo_sent_at' => 'datetime'`
  - `'reminder_terlambat_last_sent_at' => 'datetime'`

### 2. Console Routes
**File:** `routes/console.php`
**Perubahan:**
- Menambahkan 3 baris `Schedule::command('k3:check-peminjaman-reminder')` pada waktu:
  - `dailyAt('08:00')`
  - `dailyAt('13:00')`
  - `dailyAt('15:30')`

---

## Cara Menjalankan

### Manual Testing
```bash
# Jalankan command manual
php artisan k3:check-peminjaman-reminder
```

### Schedule (Cron Job)
Jika menggunakan Laravel scheduler, tambahkan ke crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Migration
Migration sudah dijalankan otomatis:
```bash
php artisan migrate
```

---

## Dependencies

- **WhatsAppService** (`app/Services/WhatsAppService.php`) — sudah ada, digunakan untuk kirim WA
- **User model** — menggunakan method `role()` (Spatie Permission) untuk ambil admin K3
- **PeminjamanHeader model** — relasi: `user()`, `details()` dengan `apdItem`
- **PeminjamanDetail model** — relasi ke `ApdItem` untuk nama barang dan satuan

---

## Notifikasi Email

Email dikirim menggunakan Laravel's built-in notification system.
View default akan menampilkan data dari `toDatabase()`.
Untuk kustomisasi template email, edit method `toMail()` di `PeminjamanReminderNotification`.

---

## Database Notifications

Notifikasi tersimpan di table `notifications` (default Laravel).
Dapat dilihat di Filament jika ada resource untuk notifications, atau via:
```php
$user->notifications()->latest()->get();
```

---

## Logging

Command akan log ke console:
- Jumlah peminjaman untuk masing-masing tipe notifikasi
- Timestamp selesai

Error WA logging via `WhatsAppService` (ke `storage/logs/laravel.log`).

---

## Testing Checklist

- [ ] Migration berjalan (`peminjaman_headers` memiliki 3 kolom baru)
- [ ] Command `k3:check-peminjaman-reminder` bisa dijalankan manual
- [ ] Schedule terdaftar di `php artisan schedule:list`
- [ ] WhatsApp token configured di `.env` (`SERVICES_FONNTE_TOKEN`)
- [ ] User memiliki `no_hp` dan `email` terisi
- [ ] Role `admin_k3` exists (Spatie Permission)
- [ ] Email config (MAIL_*) sudah diset
- [ ] Coba dengan data peminjaman `status = approved` dan tanggal kembali beragam

---

## Catatan

- Notifikasi WA hanya dikirim jika `no_hp` terisi
- Notifikasi email hanya dikirim jika `email` terisi
- Tracking timestamp mencegah notifikasi duplikat untuk tipe yang sama
- Untuk yang terlambat (`terlambat`), tidak ada tracking — akan terus kirim setiap hari
