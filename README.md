# GICP Talaga Church Website and CMS

Grace in Christ Presbyterian Church - Talaga website and content management system built with CodeIgniter 3, MySQL, Bootstrap 5, HTML5, CSS3, JavaScript, and jQuery.

## Highlights

- Public-facing church website with Home, About Us, Pastors, Ministries, Sermons, Events, Announcements, News & Updates, Gallery, Contact, and Search pages
- CMS admin panel with authentication, dashboard, church profile management, pastors, ministries, sermons, events, announcements, daily verses, news updates, galleries, users, menu items, schedules, prayer requests, contact messages, newsletter subscriptions, and activity logs
- Responsive church-branded design using deep blue, gold, and white with accessible layouts and clear call-to-action sections
- Seeded MySQL schema and sample content for fast local setup
- Dynamic menu management, service schedules, prayer request submission, visitor counting, newsletter subscription, and audit logging

## Project Structure

- `application/controllers/Site.php` public website controller
- `application/controllers/admin/Auth.php` admin authentication controller
- `application/controllers/admin/Dashboard.php` admin dashboard controller
- `application/controllers/admin/Content.php` generic admin CRUD controller for CMS modules
- `application/models/` data models for church content, settings, users, and supporting records
- `application/views/site/` public website templates
- `application/views/admin/` CMS templates
- `assets/` CSS, JavaScript, and SVG artwork
- `database/gicpctalaga.sql` complete schema plus sample data

## Requirements

- PHP 8.1+ with `mysqli`, `openssl`, `mbstring`, and `fileinfo`
- MySQL 5.7+ or MariaDB 10.4+
- Apache with `mod_rewrite` enabled
- Writable directories:
  - `application/cache/`
  - `application/cache/sessions/`
  - `application/logs/`
  - `uploads/` and any upload subfolders created by the CMS

## Local Installation

1. Create a MySQL database named `gicpctalaga`.
2. Import `database/gicpctalaga.sql`.
3. Place the project inside your web root, for example `c:/xampp/htdocs/TALAGA`.
4. Update database credentials in `application/config/database.php` if your MySQL user is not `root` with an empty password.
5. Confirm the writable folders listed above have write permission.
6. Open the site in your browser:
   - `http://localhost/TALAGA/`
7. Open the admin panel:
   - `http://localhost/TALAGA/admin/login`

## Seeded Admin Accounts

- Administrator:
  - Email: `admin@gicptalaga.org`
  - Password: `password`
- Editor:
  - Email: `editor@gicptalaga.org`
  - Password: `password`

Change the seeded passwords immediately after first login.

## Deployment Notes

- `application/config/config.php` auto-detects the base URL and enables CSRF protection.
- `index.php` is removed from URLs through the included root `.htaccess` file.
- `uploads/.htaccess` blocks script execution in uploaded files.
- For production, replace the sample encryption key in `application/config/config.php` with a unique secret.
- For production, replace seeded sample content and graphics with real church media, and switch default credentials before launch.

## GitHub to InfinityFree Deployment

InfinityFree does not provide a native GitHub deployment connection. This repository includes `.github/workflows/deploy-infinityfree.yml`, which now deploys each branch to its own InfinityFree target:

- `staging` deploys to `gicpctalaga.page.gd`
- `main` deploys to `graceinchristpc.online`

1. In the InfinityFree control panel, open your hosting account FTP details and copy the FTP username and FTP password.
2. In your GitHub repository, open `Settings > Secrets and variables > Actions` and create these repository secrets:
  - `LIVE_FTP_USERNAME` and `LIVE_FTP_PASSWORD` for the live domain, or reuse `FTP_USERNAME` and `FTP_PASSWORD` as shared fallbacks
  - `STAGING_FTP_USERNAME` and `STAGING_FTP_PASSWORD` for the staging domain
3. In the same Actions settings page, create these repository variables:
  - `LIVE_FTP_SERVER_DIR` for the live document root. If you leave it unset, the workflow defaults live deployments to `htdocs/`.
  - `STAGING_FTP_SERVER_DIR` for the staging document root used by `gicpctalaga.page.gd`.
4. The workflow deploys to `ftpupload.net`, but each branch only uploads to its own configured server directory.
5. Push to `staging` to deploy only the staging site, or push to `main` to deploy only the live site.
6. Import `database/gicpctalaga.sql` into the correct InfinityFree MySQL database separately, because the workflow deploys files only.

Notes:

- `application/config/config.php` only loads Composer's autoloader when `vendor/autoload.php` exists, so this project does not require a Composer install step on InfinityFree.
- `application/config/database.php` now selects `if0_42125686_gicpctalaga` for `gicpctalaga.page.gd` and `if0_42125686_gicpclive` for `graceinchristpc.online`.
- After the first deploy, make sure `application/cache/`, `application/cache/sessions/`, `application/logs/`, and `uploads/` are writable on the hosting account.

## CMS Modules

- Church Profile
- Pastors
- Ministries
- Sermons
- Events
- Announcements
- Daily Verses
- News & Updates
- Galleries
- Users
- Menu Items
- Service Schedules
- Prayer Requests
- Contact Messages
- Newsletter Subscriptions
- Website Settings
- Activity Logs

## Notes

- Forgot password currently generates a reset link directly in the admin flash message so the system works locally without SMTP configuration.
- The gallery lightbox and verse sharing features are handled client-side in `assets/js/site.js`.
- Sample file attachment records are included in the database script. The UI only links files that actually exist on disk.