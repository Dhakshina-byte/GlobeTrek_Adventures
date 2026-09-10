
## 1. Folder structure (MVC)

```
globetrek/
├── config/
│   └── database.php        Database connection (PDO)
├── models/                 M — talks to the database only
│   ├── User.php
│   ├── Package.php
│   ├── Booking.php
│   ├── Payment.php
│   └── Query.php
├── controllers/            C — validation + business logic
│   ├── AuthController.php
│   ├── PackageController.php
│   ├── BookingController.php
│   ├── PaymentController.php
│   ├── QueryController.php
│   └── AdminController.php         
├── includes/               shared layout + helper functions
│   ├── header.php / footer.php
│   └── functions.php
├── assets/                 CSS + JS
├── staff/                  staff-only pages
├── admin/                  admin-only pages
├── index.php, login.php, register.php, packages.php,
│   package_details.php, book.php, payment.php,
│   my_bookings.php, contact.php, get_image.php   V — one file per page
└── database.sql            Import this into phpMyAdmin
```


## 2. Requirements

- WAMP Server (Apache + PHP 7.4 or newer + MySQL/MariaDB)
- A browser

## 3. Setup steps

1. Copy the whole `globetrek` folder into `C:\wamp64\www\` (or your
   WAMP `www` directory), so the path is `C:\wamp64\www\globetrek`.
2. Start WAMP and make sure the icon is green.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Click **Import**, choose `database.sql` from the project folder,
   and click **Go**. This creates the `globetrek_db` database with
   all tables and one default admin account.
5. Visit `http://localhost/globetrek/index.php` in your browser.

If your MySQL root user has a password, or you use a different DB
name, update `config/database.php` accordingly.

## 4. Default login

| Role  | Email                  | Password   |
|-------|------------------------|------------|
| Admin | admin@globetrek.com    | Admin@123  |

Use the Admin account to add staff members (Admin Dashboard → Manage
Staff). Customers register themselves from the Register page. Staff
accounts cannot self-register — only an admin can create them, which
matches the brief ("Administrators should be able to manage staff
accounts").

## 5. User roles implemented

- **Customer** — register/login, browse & search packages, customize
  a trip (date, travelers, special requests), pay (simulated card
  payment), view "My Bookings", submit queries via Contact page.
- **Staff** — add/edit/delete packages (with picture upload),
  confirm/cancel bookings, reply to customer queries.
- **Admin** — everything staff can do, plus create/remove staff
  accounts and view a sales & customer report.

## 6. Notable design choices / assumptions

- **Package pictures are stored directly in the database** (as a
  `LONGBLOB`) rather than as files on disk, as requested — this
  avoids upload-folder permission issues on WAMP and keeps everything
  in one place for phpMyAdmin. `get_image.php` streams the picture
  back out when a page needs to display it.
- **Payment is simulated.** No real payment gateway is used — the
  form validates a card-like number/expiry/CVV for the coursework
  demonstration, and full card numbers are never stored (only the
  last 4 digits), which is called out in the code as a simplification.
- **Validation happens twice**: quick feedback in the browser with
  JavaScript (`assets/js/validation.js`), and the *real*, trusted
  check again in PHP on the server (in the Controllers) — because
  client-side checks can always be bypassed.
- **Errors are handled gracefully**: a failed DB connection shows a
  friendly message instead of a raw crash, forms redisplay with the
  exact field that failed and why, and pages check that a record
  exists before using it (e.g. booking someone else's booking is
  blocked).
- A simple CSRF token is included in every form as good practice for
  a "beginner+" level project.
