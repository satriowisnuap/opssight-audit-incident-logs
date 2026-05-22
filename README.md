# OpsSight — Incident Audit Log System

**OpsSight** is a production-ready **Incident Audit Log** management system built on **Laravel 12**, **Tailwind CSS v4**, and **Alpine.js**. It helps operations and security teams track, classify, and audit incidents with full change history — from initial report through resolution.

---

## ✨ Key Features

- 🚨 **Incident Management** — Log incidents with title, description, severity, and status tracking
- 🏷️ **Category Classification** — Organize incidents into custom categories for better reporting
- 👤 **Assignment & Ownership** — Assign incidents to team members and track the reporter
- 🔍 **Audit Trail** — Every create, update, and delete action is logged with old/new values, IP address, and timestamp
- 📊 **Severity & Status Filtering** — Filter by LOW / MEDIUM / HIGH / CRITICAL severity and OPEN / IN_PROGRESS / RESOLVED / CLOSED status
- 🗑️ **Soft Deletes** — Incidents are soft-deleted to preserve audit integrity
- 📱 **Fully Responsive** — Mobile-first layout that works on any screen size

---

## 🗄️ Database Schema

### `incident_categories`

| Column                      | Type      | Notes          |
| --------------------------- | --------- | -------------- |
| `id`                        | bigint    | Primary key    |
| `name`                      | string    | Category label |
| `created_at` / `updated_at` | timestamp |                |

### `incidents`

| Column                      | Type                       | Notes                                                        |
| --------------------------- | -------------------------- | ------------------------------------------------------------ |
| `id`                        | bigint                     | Primary key                                                  |
| `title`                     | string                     | Short incident title                                         |
| `description`               | text                       | Full incident description                                    |
| `severity`                  | enum                       | `LOW`, `MEDIUM`, `HIGH`, `CRITICAL`                          |
| `status`                    | enum                       | `OPEN`, `IN_PROGRESS`, `RESOLVED`, `CLOSED` — default `OPEN` |
| `category_id`               | FK → `incident_categories` |                                                              |
| `assigned_to`               | FK → `users` (nullable)    | Assigned team member                                         |
| `reported_by`               | FK → `users`               | Reporter                                                     |
| `incident_date`             | timestamp                  | When the incident occurred                                   |
| `resolved_at`               | timestamp (nullable)       | When the incident was resolved                               |
| `deleted_at`                | timestamp (nullable)       | Soft delete                                                  |
| `created_at` / `updated_at` | timestamp                  |                                                              |

> Indexed on: `severity`, `status`, `created_at`

### `audit_logs`

| Column       | Type                    | Notes                                |
| ------------ | ----------------------- | ------------------------------------ |
| `id`         | bigint                  | Primary key                          |
| `user_id`    | FK → `users` (nullable) | Who performed the action             |
| `action`     | string                  | e.g. `created`, `updated`, `deleted` |
| `table_name` | string                  | Affected table                       |
| `record_id`  | bigint                  | Affected record ID                   |
| `old_values` | JSON (nullable)         | State before change                  |
| `new_values` | JSON (nullable)         | State after change                   |
| `ip_address` | string (nullable)       | Actor's IP address                   |
| `created_at` | timestamp               | Auto-set to current time             |

> Indexed on: `user_id`, `action`, `created_at`

---

## 📋 Requirements

- **PHP 8.2+**
- **Composer**
- **Node.js 18+** and **npm**
- **Database** — SQLite (default), MySQL, or PostgreSQL

Verify your environment:

```bash
php -v
composer -V
node -v
npm -v
```

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-org/opssight.git
cd opssight
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env      # Linux/macOS
copy .env.example .env    # Windows
php artisan key:generate
```

### 4. Configure Database

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opssight
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed        # optional — loads sample data
php artisan storage:link
```

---

## 🏃 Running the Application

```bash
composer run dev
```

This starts the Laravel dev server, Vite HMR, queue worker, and log monitor in one command.

**Access the app at:** [http://localhost:8000](http://localhost:8000)

Or run manually in separate terminals:

```bash
php artisan serve   # Terminal 1
npm run dev         # Terminal 2
```

---

## 🏗️ Building for Production

```bash
npm run build
php artisan optimize
```

Update `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

---
