# OpsSight — Incident & Audit Log Management System

OpsSight adalah sistem manajemen insiden operasional dan audit trail berbasis web yang dikembangkan menggunakan **Laravel 11**, **TailAdmin (Tailwind CSS)**, dan **Alpine.js**. Sistem ini dirancang untuk memenuhi kebutuhan tim operasional **Greenfields Indonesia** dalam mengelola, memantau, dan mendokumentasikan insiden operasional secara terstruktur dan terpusat.

---

## Deskripsi Sistem

OpsSight menerapkan dua lapisan utama fungsionalitas:

**Incident Management** — Modul pencatatan, pengelolaan, dan pelacakan insiden operasional berdasarkan tingkat keparahan (severity level): `LOW`, `MEDIUM`, `HIGH`, dan `CRITICAL`, serta status penyelesaian (lifecycle status): `OPEN`, `IN_PROGRESS`, `RESOLVED`, dan `CLOSED`.

**Audit Trail** — Modul yang mencatat secara otomatis seluruh aktivitas pengguna dalam sistem, meliputi identitas pengguna, waktu aktivitas, IP address, serta perubahan data sebelum dan sesudah tindakan dilakukan.

---

## Fitur Utama

- **Manajemen Insiden** — Operasi CRUD insiden dengan klasifikasi severity, tracking status lifecycle, penugasan ke operator, dan mekanisme soft-delete untuk preservasi data historis.
- **Klasifikasi Kategori** — Pengelolaan kategori insiden yang dapat dikustomisasi sesuai kebutuhan operasional.
- **Penugasan & Kepemilikan** — Assign insiden ke anggota tim; setiap insiden memiliki reporter (`reported_by`) dan assignee (`assigned_to`).
- **Audit Trail Otomatis** — Setiap aksi `CREATE`, `UPDATE`, dan `DELETE` tercatat beserta data lama (`old_values`), data baru (`new_values`), IP address, dan timestamp.
- **Dashboard Prioritas** — Tampilan ringkasan insiden aktif berdasarkan urgency/severity dengan filter visual dan statistik status.
- **Filter & Pencarian** — Filter insiden berdasarkan severity, status, kategori, dan rentang tanggal, dilengkapi pagination.
- **Role-Based Access Control (RBAC)** — Dua peran pengguna dengan hak akses berbeda: `ADMIN` dan `OPERATOR`.
- **Soft Delete** — Insiden yang dihapus tidak hilang dari database; data tetap tersedia untuk keperluan audit dan pemulihan.
- **Responsif** — Antarmuka mendukung tampilan desktop dan tablet (minimal lebar layar 768px).

---

## Peran Pengguna

| Peran        | Tugas                                                                                                       | Hak Akses                                               |
| ------------ | ----------------------------------------------------------------------------------------------------------- | ------------------------------------------------------- |
| **ADMIN**    | Mengelola pengguna, kategori insiden, melihat seluruh insiden dan audit log, mengassign insiden ke operator | Full Access: Insert, Read, Update, Delete semua entitas |
| **OPERATOR** | Membuat insiden baru, memperbarui status insiden miliknya                                                   | Create & Update insiden milik sendiri, Read kategori    |

---

## Batasan Sistem

Sistem OpsSight **tidak mencakup** fitur berikut:

- Integrasi dengan sistem monitoring infrastruktur (Prometheus, Nagios, dan sejenisnya)
- Notifikasi real-time atau push notification
- Pengelolaan Service Level Agreement (SLA) secara otomatis
- Integrasi dengan modul pelaporan eksternal

---

## Skema Database

### `incident_categories`

| Kolom                       | Tipe      | Keterangan     |
| --------------------------- | --------- | -------------- |
| `id`                        | bigint    | Primary key    |
| `name`                      | string    | Label kategori |
| `created_at` / `updated_at` | timestamp |                |

### `incidents`

| Kolom                       | Tipe                       | Keterangan                                                   |
| --------------------------- | -------------------------- | ------------------------------------------------------------ |
| `id`                        | bigint                     | Primary key                                                  |
| `title`                     | string                     | Judul singkat insiden                                        |
| `description`               | text                       | Deskripsi lengkap insiden                                    |
| `severity`                  | enum                       | `LOW`, `MEDIUM`, `HIGH`, `CRITICAL`                          |
| `status`                    | enum                       | `OPEN`, `IN_PROGRESS`, `RESOLVED`, `CLOSED` — default `OPEN` |
| `category_id`               | FK → `incident_categories` |                                                              |
| `assigned_to`               | FK → `users` (nullable)    | Anggota tim yang ditugaskan                                  |
| `reported_by`               | FK → `users`               | Pelapor insiden                                              |
| `incident_date`             | timestamp                  | Waktu insiden terjadi                                        |
| `resolved_at`               | timestamp (nullable)       | Waktu insiden diselesaikan                                   |
| `deleted_at`                | timestamp (nullable)       | Soft delete                                                  |
| `created_at` / `updated_at` | timestamp                  |                                                              |

> Index pada kolom: `severity`, `status`, `created_at`, `role`

### `audit_logs`

| Kolom            | Tipe                    | Keterangan                              |
| ---------------- | ----------------------- | --------------------------------------- |
| `id`             | bigint                  | Primary key                             |
| `user_id`        | FK → `users` (nullable) | Pengguna yang melakukan aksi            |
| `action`         | enum                    | `CREATE`, `UPDATE`, `DELETE`            |
| `auditable_type` | string                  | Tipe entitas yang diaudit (polymorphic) |
| `auditable_id`   | bigint                  | ID record yang diaudit                  |
| `old_values`     | JSON (nullable)         | Kondisi data sebelum perubahan          |
| `new_values`     | JSON (nullable)         | Kondisi data setelah perubahan          |
| `ip_address`     | string (nullable)       | IP address pelaku                       |
| `user_agent`     | string (nullable)       | User agent browser                      |
| `created_at`     | timestamp               | Waktu aksi; otomatis diisi              |

> Index pada kolom: `user_id`, `action`, `created_at`

### `users`

| Kolom                       | Tipe              | Keterangan                             |
| --------------------------- | ----------------- | -------------------------------------- |
| `id`                        | bigint            | Primary key                            |
| `name`                      | string            | Nama lengkap                           |
| `email`                     | string            | Unique                                 |
| `password`                  | string            | Di-hash menggunakan bcrypt (cost ≥ 10) |
| `role`                      | enum              | `ADMIN`, `OPERATOR`                    |
| `remember_token`            | string (nullable) | Token sesi                             |
| `created_at` / `updated_at` | timestamp         |                                        |

---

## Lingkungan Operasi

| Komponen              | Spesifikasi                                           |
| --------------------- | ----------------------------------------------------- |
| Target Deployment     | Internal Virtual Machine (VM) — Greenfields Indonesia |
| Spesifikasi VM        | CPU: 1 Core, RAM: 2 GB, OS: Ubuntu 22.04 LTS          |
| Web Server            | Nginx (latest stable)                                 |
| Application Runtime   | PHP 8.2+ dengan PHP-FPM                               |
| Framework             | Laravel 11                                            |
| Database              | PostgreSQL 15+                                        |
| Process Manager       | Supervisor 4.x                                        |
| Browser yang Didukung | Chrome 90+, Firefox 88+, Edge 90+, Safari 14+         |
| Protokol              | HTTP/HTTPS (port 80/443)                              |

---

## Persyaratan Instalasi

- PHP 8.2+ dengan ekstensi: `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`
- Composer 2.x
- Node.js 18+ dan npm
- PostgreSQL 15+

Verifikasi environment sebelum instalasi:

```bash
php -v
composer -V
node -v
npm -v
psql --version
```

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-org/opssight.git
cd opssight
```

### 2. Install Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env      # Linux/macOS
copy .env.example .env    # Windows

php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` sesuai kredensial database PostgreSQL Anda:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=opssight
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Migrasi & Seeder

```bash
php artisan migrate
php artisan db:seed        # opsional — memuat data sampel
php artisan storage:link
```

---

## Menjalankan Aplikasi

### Mode Development

```bash
composer run dev
```

Perintah ini menjalankan Laravel dev server, Vite HMR, queue worker, dan log monitor secara bersamaan.

Akses aplikasi di: [http://localhost:8000](http://localhost:8000)

Atau jalankan secara terpisah:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### Mode Production

```bash
npm run build
php artisan optimize
```

Perbarui `.env` untuk lingkungan produksi:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

Untuk deployment zero-downtime, sistem menggunakan strategi rolling dengan symlink (Laravel deployment pattern) yang dikelola oleh Supervisor.

---

## Keamanan

- Autentikasi menggunakan **Laravel Breeze** dengan proteksi brute-force via rate limiting pada endpoint login
- Proteksi **CSRF** pada seluruh form (POST, PUT, PATCH, DELETE)
- Password di-hash menggunakan **bcrypt** dengan cost factor minimal 10
- Seluruh request ke halaman terproteksi wajib melewati autentikasi; request tanpa session diredirect ke halaman login
- OPERATOR tidak dapat mengakses atau memodifikasi insiden milik pengguna lain (authorization check)
- Seluruh input divalidasi di sisi server (server-side validation)

---

## Referensi

- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [PostgreSQL 15 Documentation](https://www.postgresql.org/docs/15/)
- [Ubuntu 22.04 LTS Server Guide](https://ubuntu.com/server/docs)
- [Nginx Documentation](https://nginx.org/en/docs/)
- [PHP-FPM Documentation](https://www.php.net/manual/en/install.fpm.php)
- [Supervisor Documentation](http://supervisord.org/)
- [OWASP Web Application Security Guidelines](https://owasp.org/)
- IEEE Std 830-1998 — IEEE Recommended Practice for Software Requirements Specifications
