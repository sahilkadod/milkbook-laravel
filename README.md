# 🥛 MilkBook — Laravel Web App

A full web version of the **MilkBook Android app**, converted to Laravel.
All features from the mobile app are preserved: customer management,
daily milk entries (morning/evening liter + fat), monthly billing with
fat-based formula, printable PDF bills, and charts.

---

## ✅ Features

| Feature | Status |
|---|---|
| Register / Login / Forgot Password | ✅ |
| Dashboard with monthly summary | ✅ |
| Customer CRUD (add / edit / delete) | ✅ |
| Daily milk entry (morning + evening liter & fat) | ✅ |
| Click any row to edit inline (modal) | ✅ |
| Monthly rate per customer (₹ / fat unit) | ✅ |
| Bill screen: fat-based billing formula | ✅ |
| Daily liters & fat chart | ✅ |
| Printable PDF bill (browser print dialog) | ✅ |
| Fully responsive (mobile + desktop) | ✅ |
| SQLite (default) or MySQL | ✅ |

---

## 📁 Project Structure

```
milkbook-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── MilkEntryController.php
│   │   │   └── BillController.php
│   │   └── Middleware/
│   │       └── AuthSession.php
│   └── Models/
│       ├── User.php
│       ├── Customer.php
│       ├── MilkEntry.php
│       └── CustomerRate.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_customers_table.php
│   │   ├── ..._create_milk_entries_table.php
│   │   └── ..._create_customer_rates_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php        ← main layout with sidebar
│   │   └── auth.blade.php       ← auth layout (no sidebar)
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   ├── forgot.blade.php
│   │   └── profile.blade.php
│   ├── customers/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php       ← customer dashboard + milk table + modal
│   ├── bills/
│   │   ├── show.blade.php       ← bill with chart
│   │   └── pdf.blade.php        ← printable bill
│   └── dashboard.blade.php
├── public/
│   ├── css/app.css
│   ├── index.php
│   └── .htaccess
└── routes/web.php
```

---

## 🚀 Setup Instructions

### Requirements

- PHP 8.2+
- Composer
- SQLite (built into PHP) **or** MySQL

---

### Step 1 — Install Dependencies

```bash
cd milkbook-laravel
composer install
```

---

### Step 2 — Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

**SQLite (easiest — no extra setup needed):**
The `.env.example` already uses SQLite. Just make sure the database file exists:

```bash
touch database/database.sqlite
```

**MySQL (optional):**
Edit `.env` and change:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=milkbook
DB_USERNAME=root
DB_PASSWORD=your_password
```
Then create the database in MySQL:
```sql
CREATE DATABASE milkbook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### Step 3 — Run Migrations

```bash
php artisan migrate
```

---

### Step 4 — (Optional) Seed Demo Data

Creates a demo user: **Phone: 9999999999 / Password: demo123**

```bash
php artisan db:seed
```

---

### Step 5 — Start the Server

```bash
php artisan serve
```

Open **http://localhost:8000** in your browser.

---

## 🌐 Deploy to cPanel / Shared Hosting

1. Upload all files to your hosting via FTP/FileManager
2. Point the **document root** to the `public/` folder
   - In cPanel → Domains → set document root to `public_html/milkbook/public`
   - Or use an `.htaccess` redirect in `public_html/` to point to `public/`
3. Set file permissions: `storage/` and `bootstrap/cache/` → `755`
4. Run migrations via SSH: `php artisan migrate`
5. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`

---

## 💡 Billing Formula

This matches exactly the Android app's formula:

```
Morning Amount = morning_liter × morning_fat × rate
Evening Amount = evening_liter × evening_fat × rate
Day Total      = Morning Amount + Evening Amount
```

Where `rate` is ₹ per fat unit (set per customer per month).

---

## 🔑 Default Demo Login

After seeding:
- **Phone:** `9999999999`
- **Password:** `demo123`

---

## 🗃️ Database Schema

```
users            — id, name, phone, password, dob
customers        — id, user_id, name, phone, address
milk_entries     — id, user_id, customer_id, date, morning_liter, morning_fat, evening_liter, evening_fat
customer_rates   — id, user_id, customer_id, month, year, rate
```
