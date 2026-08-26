# EN.AR Workforce Management System

A centralized web application for EN.AR Limited to manage staff records, units/departments, job positions, employment classifications, and leave requests — with role-based access control.

## Project Overview

EN.AR Limited currently manages workforce information (units, job roles, employment types, staff categories, leave records) manually or across disconnected sources. This system centralizes all of that into a single, role-aware application.

**Assessment objectives this project demonstrates:**
- Relational database design
- Backend application logic
- Authentication and authorization
- CRUD operations across related entities
- Workflow logic (leave approval process)
- A functional, understandable frontend

## Tech Stack

| Layer | Technology |
|---|---|
| Backend framework | Laravel (PHP) |
| Database | MySQL |
| Templating | Blade |
| Frontend | HTML, CSS, JavaScript (Bootstrap) |
| Data access | Raw SQL via Laravel's `DB` facade (no Eloquent, no migrations — existing hand-built schema) |

> **Note on architecture choice:** This project intentionally uses raw SQL queries (`DB::select`, `DB::insert`, `DB::update`) instead of Eloquent ORM, and connects directly to an existing, manually-created database rather than using Laravel migrations. Validation, authentication, authorization, and middleware are still implemented using Laravel's built-in features.

## User Roles & Permissions

| Role | Permissions |
|---|---|
| **Administrator** | Full access to all modules |
| **HR/Admin Officer** | Manage staff records, units, positions, leave requests |
| **Unit Head** | View staff within their unit; review leave requests for their unit |
| **Staff** | View own profile; submit leave requests; view own leave history |

Access is enforced both in the UI (hiding actions) and at the route level via middleware — role checks are never left to the frontend alone.

## Core Modules

1. **Authentication & Roles** — Login system with role-based access control.
2. **Staff Management** — Add, view, update, and deactivate/archive staff. Records are never hard-deleted.
3. **Unit/Department Management** — Create and edit units; assign a unit head; view staff per unit.
4. **Job Position Management** — Manage job titles/positions, assignable to staff.
5. **Employment Classification** — Separate tracking of Staff Category, Employment Type, and Employment Status.
6. **Leave Management** — Staff submit leave requests (auto-calculated duration); authorized users approve/reject with comments; staff track request status.
7. **Dashboard** — Workforce summary stats (headcounts, by unit, by employment type, on leave, pending requests), with an optional chart.
8. **Search & Filtering** — Filter staff by name, unit, position, category, employment type, and status.

### Bonus Features (optional)
- Internship management (institution, programme, supervisor, dates, status)
- Staff document storage (CV, appointment letter, etc.)
- Notifications on leave approval/rejection
- Basic reports (staff by unit, by employment type, category distribution, leave history)
- REST API exposing selected data

## Project Structure

```
en-ar-workforce/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # StaffController, UnitController, PositionController,
│   │   │                    # LeaveController, DashboardController, AuthController
│   │   └── Middleware/      # CheckRole.php (role-based route protection)
├── resources/
│   └── views/
│       ├── layouts/         # Shared layout: navbar, sidebar
│       ├── auth/            # Login views
│       ├── staff/           # Staff CRUD views
│       ├── units/           # Unit CRUD views
│       ├── positions/       # Position CRUD views
│       ├── leave/           # Leave request + approval views
│       └── dashboard/       # Dashboard view
├── routes/
│   └── web.php               # All URL → Controller route definitions
└── public/
    └── css, js, images
```

## Database Tables (existing schema)

| Table | Purpose |
|---|---|
| `users` | Login accounts, linked to a role |
| `units` | Departments/units, optionally linked to a unit head |
| `positions` | Job titles/positions |
| `staff` | Staff records — linked to unit, position, category, employment type, status |
| `leave_requests` | Leave submissions — linked to staff, with type, dates, status, comments |

Relationships are enforced via foreign keys in the database and joined manually in raw SQL queries (e.g. `staff` joined to `units` and `positions` for display).

## Setup & Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL Server
- Node.js + npm (only if compiling CSS/JS assets)

### Steps

1. **Clone/copy the project**
   ```bash
   cd en-ar-workforce
   composer install
   ```

2. **Configure environment**
   Copy `.env.example` to `.env` and set your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=en_ar_workforce
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
   Generate the app key if not already set:
   ```bash
   php artisan key:generate
   ```

3. **Database**
   This project connects to an existing, already-built database — no migrations are run. Ensure the `en_ar_workforce` database (with its tables: `users`, `units`, `positions`, `staff`, `leave_requests`) exists and matches the schema above before starting the app.

4. **Frontend assets** (only if using Vite/npm-compiled CSS/JS)
   ```bash
   npm install
   npm run dev
   ```

5. **Run the application**
   ```bash
   php artisan serve
   ```
   Visit `http://127.0.0.1:8000` in your browser.

## Security Notes

- All raw SQL queries use parameter binding (`?` placeholders) — no user input is ever concatenated directly into a query string.
- Authorization is enforced server-side via middleware on every protected route, not just hidden in the UI.
- Passwords are hashed using Laravel's built-in hashing (never stored in plain text).

## Status

This project is under active development as part of an academic assessment for EN.AR Limited's Workforce Management System brief.