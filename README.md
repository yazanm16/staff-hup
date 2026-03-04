# Staff Hub

> A production-ready, dual-interface Staff Management System built with Laravel — featuring role-based access control, task management, attendance tracking, automated weekly reporting, and a full REST API alongside a Blade-based web interface.

---

## Table of Contents

- [Project Description](#project-description)
- [Features](#features)
- [Architecture Overview](#architecture-overview)
- [Tech Stack](#tech-stack)
- [Folder Structure](#folder-structure)
- [Installation Guide](#installation-guide)
- [Environment Setup](#environment-setup)
- [Database Setup](#database-setup)
- [Queue & Scheduler Setup](#queue--scheduler-setup)
- [API Documentation](#api-documentation)
- [Example Usage](#example-usage)
- [Future Improvements](#future-improvements)
- [Author](#author)

---

## Project Description

**Staff Hub** is a comprehensive internal staff management platform designed for organizations that need to manage employees, departments, tasks, and attendance — all within a single, unified system.

The project is architected as a **dual-interface application**: it exposes a full **RESTful JSON API** (authenticated via Laravel Sanctum) for mobile or SPA clients, and simultaneously serves a **Blade-based web UI** for browser-based usage. Both interfaces share the same business logic layer, enforcing consistent behavior across all access points.

The system implements **Role-Based Access Control (RBAC)** using Spatie Laravel Permission, distinguishing between `admin` and `employee` roles with fine-grained permission management. It includes a sophisticated **background job pipeline** that automatically generates weekly attendance reports, detects anomalies, and notifies managers via database and broadcast notifications.

---

## Features

- **Authentication** — Secure login with Laravel Sanctum (API token-based) and session-based auth for the web interface. Includes token expiration middleware.
- **Role & Permission Management** — Two built-in roles (`admin`, `employee`) with granular permissions (e.g., `task.view`, `comment.delete-any`, `role.manage`). Roles and permissions are manageable at runtime.
- **Employee Management** — Admins can create, update, and delete employee records. Employees belong to departments and have associated photos (polymorphic), tasks, and attendance records.
- **Department Management** — Full CRUD for departments. Deletion is guarded; departments with active employees cannot be removed.
- **Task Management** — Admins create and assign tasks to employees. Employees can view their own tasks and update task status. Tasks support threaded comments.
- **Comment System** — Employees can comment on tasks. Supports soft-delete, restore, and permanent deletion with policy-based authorization (`CommentPolicy`).
- **Attendance Tracking** — Employees check in and check out. The system prevents duplicate check-ins and tracks open sessions. Admins can view full attendance history.
- **Attendance Reports** — Admins can generate, export (CSV/XLSX), and import attendance data. Reports are filterable by date range.
- **Weekly Automated Reporting Pipeline** — A scheduled job (`WeeklyAttendanceBatchJob`) dispatches a `Bus::batch` containing:
    - `GenerateWeeklyReportJob` — Builds and stores an XLSX report to disk and caches the result for 24 hours.
    - `DetectAttendanceIssuesJob` — Analyzes the report for anomalies.
    - On batch success: dispatches `NotifyManagersJob`, which sends `WeeklyAttendanceNotification` via database and broadcast channels.
- **Real-time Notifications** — Managers receive broadcast notifications when the weekly report is ready (via Laravel Broadcasting).
- **Profile Management** — Authenticated users can update their profile and change their password.
- **Dual Dashboard** — Separate, role-aware dashboards for admins and employees surfacing contextual statistics.
- **Repository Pattern** — All data access is abstracted behind repository contracts, decoupling business logic from Eloquent.
- **Service Layer** — Each domain (Attendance, Task, Employee, etc.) has a dedicated service class injected into controllers.
- **Centralized Exception Handling** — Custom domain exceptions (e.g., `AlreadyCheckedInException`, `DepartmentHasEmployeesException`, `TaskAccessDeniedException`) handled by `ApiExceptionHandler` for consistent API error responses.

---

## Architecture Overview

Staff Hub follows a **layered architecture** pattern:

```
HTTP Layer (Routes)
    │
    ▼
Controllers (Web + API — separate namespaces, shared service layer)
    │
    ▼
Service Layer (Business Logic — e.g., AttendanceService, TaskService)
    │
    ▼
Repository Layer (Data Access via Contracts + Implementations)
    │
    ▼
Eloquent Models (User, Task, Attendance, Department, Comment, Photo)
    │
    ▼
MySQL Database
```

Background processing runs through Laravel's **Job/Queue** system:

```
Scheduler → WeeklyAttendanceBatchJob
                ├── GenerateWeeklyReportJob   (stores XLSX + caches data)
                ├── DetectAttendanceIssuesJob (flags anomalies)
                └── [on success] NotifyManagersJob → WeeklyAttendanceNotification
                                                          ├── database channel
                                                          └── broadcast channel
```

**Key design decisions:**

- Controllers are thin; all logic lives in services.
- Repositories abstract all Eloquent queries behind typed contracts, enabling easy swapping or mocking in tests.
- Domain exceptions carry semantic meaning and are caught centrally by `ApiExceptionHandler`, returning consistent JSON error payloads for the API.
- The web and API layers are fully separated (distinct controller namespaces) but share the same service and repository instances.

---

## Tech Stack

| Layer          | Technology                                    |
| -------------- | --------------------------------------------- |
| Framework      | Laravel 11                                    |
| Authentication | Laravel Sanctum (API) + Laravel Session (Web) |
| Authorization  | Spatie Laravel Permission                     |
| Database       | MySQL                                         |
| Queue Driver   | Database (configurable)                       |
| Scheduling     | Laravel Scheduler (console kernel)            |
| Excel I/O      | Maatwebsite Laravel Excel                     |
| Notifications  | Laravel Notifications (database + broadcast)  |
| Broadcasting   | Laravel Broadcasting                          |
| Frontend       | Blade Templates + Tailwind CSS                |
| API Style      | RESTful JSON                                  |

---

## Folder Structure

```
app/
├── Exceptions/              # Domain exceptions (Attendance, Task, Department, Employee)
│   └── ApiExceptionHandler  # Central API error handler
├── Exports/                 # Maatwebsite Excel export classes
├── Http/
│   └── Controllers/
│       ├── Api/             # API controllers (JSON responses)
│       └── *.php            # Web controllers (Blade responses)
├── Imports/                 # Maatwebsite Excel import classes
├── Jobs/                    # Queued jobs (batch pipeline for weekly reports)
├── Models/                  # Eloquent models
├── Notifications/           # Laravel notification classes
├── Policies/                # Authorization policies (CommentPolicy)
├── Providers/               # Service providers (binding repository contracts)
├── Repositories/
│   ├── Contracts/           # Repository interfaces
│   └── *.php                # Eloquent-backed implementations
├── Services/                # Business logic layer (one service per domain)
├── Traits/                  # Shared traits
└── View/Components/         # Blade view components

database/
├── migrations/              # Database schema migrations
├── seeders/                 # Role/permission seeders, fake data seeder

routes/
├── api.php                  # API routes (Sanctum-protected)
├── web.php                  # Web routes (session-protected)
├── auth.php                 # Auth scaffolding routes
└── console.php              # Scheduled job definitions

resources/views/             # Blade templates
```

---

## Installation Guide

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & npm (for asset compilation)

### Steps

**1. Clone the repository**

```bash
git clone https://github.com/yazanm16/staff-hup.git
cd staff-hub
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install Node dependencies and compile assets**

```bash
npm install && npm run build
```

**4. Copy the environment file**

```bash
cp .env.example .env
```

**5. Generate the application key**

```bash
php artisan key:generate
```

**6. Configure your database and other services** (see [Environment Setup](#environment-setup))

**7. Run migrations**

```bash
php artisan migrate
```

**8. Seed the database**

```bash
php artisan db:seed
```

**9. Link the storage**

```bash
php artisan storage:link
```

**10. Start the development server**

```bash
php artisan serve
```

---

## Environment Setup

Key `.env` variables to configure:

```dotenv
APP_NAME="Staff Hub"
APP_ENV=local
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=staff_hub
DB_USERNAME=root
DB_PASSWORD=

# Queue (use 'database' for local, 'redis' for production)
QUEUE_CONNECTION=database

# Broadcasting (configure for real-time notifications)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

# Mail (for future email notifications)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=

# Sanctum — set your frontend URL for SPA auth if needed
SANCTUM_STATEFUL_DOMAINS=localhost
```

---

## Database Setup

**Run all migrations:**

```bash
php artisan migrate
```

**Seed roles, permissions, and default admin user:**

```bash
php artisan db:seed --class=DatabaseSeeder
```

This runs the following seeders in order:

- `RolesAndPermissionsSeeder` — Creates `admin` and `employee` roles with their associated permissions.
- `UserSeeder` — Creates the default admin user.
- `AssignRolesToUsersSeeder` — Assigns roles to seeded users.
- `DepartmentSeeder` — Seeds initial department records.

**Seed fake data for development:**

```bash
php artisan db:seed --class=FakeDataSeeder
```

**Default admin credentials** (set by `UserSeeder` — verify in `database/seeders/UserSeeder.php`):

```
Email:    admin@example.com
Password: password
```

---

## Queue & Scheduler Setup

### Queue Worker

Staff Hub uses Laravel's database queue driver. Start the worker with:

```bash
php artisan queue:work --queue=default --tries=3
```

For production, use a process supervisor like **Supervisor**:

```ini
[program:staff-hub-worker]
command=php /var/www/staff-hub/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
```

### Scheduler

The weekly attendance batch pipeline is registered in `routes/console.php` and runs on a schedule. Register the Laravel scheduler in your system crontab:

```bash
* * * * * cd /var/www/staff-hub && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Jobs:**

| Job                        | Schedule                        | Description                                             |
| -------------------------- | ------------------------------- | ------------------------------------------------------- |
| `WeeklyAttendanceBatchJob` | Weekly (Sunday 08:00 Asia/Gaza) | Orchestrates the full weekly attendance report pipeline |

> **Note:** The schedule is currently set to `everyMinute()` for development/testing. Before going to production, update `routes/console.php` to use `->weeklyOn(0, '08:00')->timezone('Asia/Gaza')`.

### Batch Processing

The weekly pipeline uses Laravel Job Batching:

1. `GenerateWeeklyReportJob` — Exports XLSX report to `storage/app/reports/` and caches data for 24h.
2. `DetectAttendanceIssuesJob` — Flags employees with attendance anomalies.
3. On batch completion → `NotifyManagersJob` dispatches `WeeklyAttendanceNotification` to all admin-role users via **database** and **broadcast** channels.

---

## API Documentation

All API routes are prefixed with `/api`. Authentication uses **Bearer tokens** issued by Laravel Sanctum.

### Authentication

#### Login

```
POST /api/login
```

**Body:**

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**

```json
{
  "token": "<sanctum-token>",
  "user": { ... }
}
```

All subsequent requests require the header:

```
Authorization: Bearer <token>
```

#### Get Authenticated User

```
GET /api/me
```

#### Logout

```
POST /api/logout
```

---

### Profile

| Method | Endpoint               | Description                      |
| ------ | ---------------------- | -------------------------------- |
| GET    | `/api/profile`         | Get authenticated user's profile |
| PATCH  | `/api/profile`         | Update profile details           |
| PATCH  | `/api/change-password` | Change password                  |

---

### Employees _(Admin only)_

| Method    | Endpoint             | Description         |
| --------- | -------------------- | ------------------- |
| GET       | `/api/employee`      | List all employees  |
| POST      | `/api/employee`      | Create new employee |
| PUT/PATCH | `/api/employee/{id}` | Update employee     |
| DELETE    | `/api/employee/{id}` | Delete employee     |

---

### Departments _(Admin only)_

| Method    | Endpoint               | Description                                  |
| --------- | ---------------------- | -------------------------------------------- |
| GET       | `/api/department`      | List all departments                         |
| POST      | `/api/department`      | Create department                            |
| PUT/PATCH | `/api/department/{id}` | Update department                            |
| DELETE    | `/api/department/{id}` | Delete department (fails if employees exist) |

---

### Tasks

| Method    | Endpoint                   | Role     | Description             |
| --------- | -------------------------- | -------- | ----------------------- |
| GET       | `/api/tasks`               | Admin    | List all tasks          |
| POST      | `/api/tasks`               | Admin    | Create task             |
| PUT/PATCH | `/api/tasks/{id}`          | Admin    | Update task             |
| DELETE    | `/api/tasks/{id}`          | Admin    | Delete task             |
| GET       | `/api/my-tasks`            | Employee | List own assigned tasks |
| PATCH     | `/api/tasks/{task}/status` | Employee | Update task status      |

---

### Comments

| Method | Endpoint                             | Description                              |
| ------ | ------------------------------------ | ---------------------------------------- |
| GET    | `/api/tasks/{task}/comments`         | List comments on a task                  |
| POST   | `/api/tasks/{task}/comments`         | Add a comment                            |
| PATCH  | `/api/tasks/{task}/comments/{id}`    | Edit a comment                           |
| DELETE | `/api/tasks/{task}/comments/{id}`    | Soft-delete a comment                    |
| GET    | `/api/tasks/{task}/comments/deleted` | List soft-deleted comments _(Admin)_     |
| POST   | `/api/comments/{id}/restore`         | Restore a soft-deleted comment _(Admin)_ |
| DELETE | `/api/comments/{id}/force-delete`    | Permanently delete a comment _(Admin)_   |

---

### Attendance

| Method | Endpoint                       | Role     | Description                 |
| ------ | ------------------------------ | -------- | --------------------------- |
| POST   | `/api/attendances/check-in`    | Employee | Check in                    |
| POST   | `/api/attendances/check-out`   | Employee | Check out                   |
| GET    | `/api/attendances`             | Employee | View own attendance records |
| GET    | `/api/attendances/reports`     | Admin    | View attendance report      |
| GET    | `/api/attendances/export/csv`  | Admin    | Export attendance as CSV    |
| GET    | `/api/attendances/export/xlsx` | Admin    | Export attendance as XLSX   |
| POST   | `/api/attendances/import`      | Admin    | Import attendance from file |

---

### Dashboard

| Method | Endpoint                  | Role     | Description                 |
| ------ | ------------------------- | -------- | --------------------------- |
| GET    | `/api/dashboard/admin`    | Admin    | Admin stats and overview    |
| GET    | `/api/dashboard/employee` | Employee | Employee stats and overview |

---

### Roles & Permissions _(Requires `role.manage` permission)_

| Method    | Endpoint                | Description       |
| --------- | ----------------------- | ----------------- |
| GET       | `/api/roles`            | List roles        |
| POST      | `/api/roles`            | Create role       |
| PUT/PATCH | `/api/roles/{id}`       | Update role       |
| DELETE    | `/api/roles/{id}`       | Delete role       |
| GET       | `/api/permissions`      | List permissions  |
| POST      | `/api/permissions`      | Create permission |
| PUT/PATCH | `/api/permissions/{id}` | Update permission |
| DELETE    | `/api/permissions/{id}` | Delete permission |

---

### Error Responses

All API errors are handled by `ApiExceptionHandler` and return a consistent JSON structure:

```json
{
    "message": "Human-readable error description",
    "error": "ExceptionClassName"
}
```

Common status codes: `400` Bad Request, `401` Unauthenticated, `403` Forbidden, `404` Not Found, `422` Validation Error, `500` Server Error.

---

## Example Usage

**Authenticate and check in as an employee:**

```bash
# Login
TOKEN=$(curl -s -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"employee@example.com","password":"password"}' \
  | jq -r '.token')

# Check in
curl -X POST http://localhost/api/attendances/check-in \
  -H "Authorization: Bearer $TOKEN"
```

**Export weekly attendance as XLSX (admin):**

```bash
curl -X GET http://localhost/api/attendances/export/xlsx \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  --output attendance_report.xlsx
```

---

## Future Improvements

- **Email Notifications** — Extend `WeeklyAttendanceNotification` to include the `mail` channel, delivering formatted weekly summaries directly to managers' inboxes.
- **Leave Management Module** — Add support for leave requests, approvals, and leave balance tracking.
- **Automated Testing Suite** — Add feature and unit tests covering the service layer, repository contracts, and API endpoints using PHPUnit/Pest.
- **Redis Queue Driver** — Migrate from the database queue driver to Redis for higher throughput in production environments.
- **API Versioning** — Introduce `/api/` prefixing to allow non-breaking future changes to the API contract.
- **Audit Logging** — Track critical actions (employee creation/deletion, role changes) using an audit log package.
- **Reporting Dashboard** — Build visual charts for attendance trends and task completion rates on the admin dashboard.

---

## Author

**Yazan Hussein**
GitHub: [@yazanm16](https://github.com/yazanm16)

---

> Built with Laravel 11 · Dual Web + API Interface · Role-Based Access Control · Background Job Pipeline · Real-time Notifications
