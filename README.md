# Library Management system

A PHP + MySQL library system: a public catalogue of **200 books**, and a staff area for loans, returns, fines and members.

## Screenshots
Run the project and save captures in `screenshots/` as `catalogue.png`, `book.png`, `dashboard.png`, `loans.png`.

| Catalogue | Book details | Dashboard | Loans |
|---|---|---|---|
| ![Catalogue](screenshots/catalogue.png) | ![Book](screenshots/book.png) | ![Dashboard](screenshots/dashboard.png) | ![Loans](screenshots/loans.png) |

## Features
- Animated landing page with rising book spines and counting stats
- Catalogue: search by title, author or ISBN, filter by category, "Available now" toggle, pagination
- 3D book covers that swing open on hover; click a book for a details dialog
- Students and teachers register with full details (ID, department, class or designation, phone, email, address), then reserve books online
- Librarian reviews each booking request with the person's complete information, approves it (book issued and saved to the library record) or rejects it
- Exactly one library admin; registration can only create students and teachers
- Students borrow up to 3 books for 14 days, teachers up to 5 for 30 days
- My books page: profile, pending reservations (cancel), borrowed books with fines, history
- Issue and return books (transactional, stock stays correct), 14-day loans, fine of 0.50 per late day
- Dashboard: totals, overdue list, recent activity, books-by-category bars
- Members: add and see how many books each has out. Books: add new titles
- Responsive, keyboard focus styles, reduced-motion support

## The book data
200 real, well-known titles across 10 categories (including Poetry & Drama and Young Adult) with author, first-publication year, category, shelf, copies and availability. **ISBNs, shelf codes and copy counts are generated sample values** (ISBNs have valid check digits but are not the real editions). Rebuild `database.sql` any time: `python3 tools/generate_books.py`.

## Setup
1. Install XAMPP/WAMP/MAMP (PHP 8+, MySQL 8+).
2. Copy this folder to `htdocs/library-management-system`.
3. In phpMyAdmin, import `database.sql`.
4. Check the database login in `config.php` (default `root`, no password).
5. Open `http://localhost/library-management-system/`.

**Library admin (the only one):** `admin@library.local` / `admin123`, created on first visit to the login page. Change it after first use.

**Students and teachers:** open *Create account*. `database.sql` also ships sample data so the system looks alive: 24 students and teachers, 46 loans (open, overdue and returned, with fines) and 28 reservations (pending, approved, rejected and cancelled). The sample people are records only and cannot log in.

**How booking works:** user reserves a book, the admin opens *Requests*, checks the details and presses *Approve and issue book*.

## Structure
```
index.php  register.php  login.php  logout.php  reserve.php  my.php
dashboard.php  requests.php  loans.php  members.php  books.php
config.php  database.sql  includes/layout.php  assets/css  assets/js  tools/generate_books.py
```
## Roadmap
Password reset, book edit/delete, email notifications, CSV export, CSRF tokens, book descriptions and cover images.
