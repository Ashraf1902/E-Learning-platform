# El-Molhem

An Arabic-first learning platform for Mr. Abd Elrahman, built with Laravel 13.

The platform provides separate admin and student experiences for managing lectures,
quizzes, subscriptions, payments, access codes, and in-app notifications.

## Features

- **Admin dashboard** for lectures, students, quizzes, subscriptions, payments,
  access codes, leaderboard settings, and routine maintenance.
- **Student portal** with enrolled lectures, protected video playback, lecture
  materials, quizzes, results, and subscription status.
- **Access codes** that can unlock selected lectures without a full subscription.
- **Quiz engine** supporting multiple-choice and essay questions, grading, and
  regrading.
- **Notifications** with per-item read state, an unread counter, mark-all, and a
  dedicated notifications page.
- **Arabic-first UI** with RTL support, English switching, dark mode, responsive
  layouts, and reduced-motion support.

## Stack

- PHP 8.1+ and Laravel 13
- Blade templates with Tailwind CSS and Vite
- SQLite for local development, MySQL for shared-hosting production
- Alpine.js for small interactive behaviours

## Repository Layout

This application is configured for shared hosting where the **project root is the
web root** (the current InfinityFree setup):

- `index.php` and `.htaccess` are in the project root.
- `images/` and `build/` are served directly from the project root.
- There is no `public/index.php`.
- Uploaded lecture materials live under `storage/app/public` and are not committed.

Keep this layout when deploying. Moving the app into a conventional `public/`
document root requires updating the entry point and asset paths.

## Local Setup

Requirements: PHP 8.1+, Composer, Node.js 20+, and either SQLite or MySQL.

```bash
composer install
cp .env.example .env
php artisan key:generate
```

For SQLite, create the local database and run the migrations:

```bash
touch database/database.sqlite
php artisan migrate
```

To seed demo data, first set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env`, then run:

```bash
php artisan db:seed
```

There are no default admin credentials in this repository. If both variables are
set, the seeder creates that admin account (or reuses the existing one with the
same email). Without them it uses an existing admin and skips demo content when
none exists. Demo students receive `DEMO_PASSWORD` when set, otherwise a random
password printed once by the seeder.

The demo accounts and sample lectures/quizzes are for local use only. The
seeder refuses to run when `APP_ENV=production`; create production admin
accounts manually with a strong, unique password.

Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

Serve the project with Apache using the project root as its document root. The
included `.htaccess` handles front-controller rewrites and blocks access to
application internals. If you use PHP's built-in server, use a router that sends
non-file requests to `index.php`; do not serve the project root without blocking
`.env`, `storage`, and other internal paths.

## Configuration

Copy `.env.example` to `.env` and adjust the values for your environment. Never
commit `.env`, `.env.local`, or any other environment file containing secrets.

Production values to review:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
LOG_LEVEL=warning
```

For MySQL, set `DB_CONNECTION=mysql` and provide the host, database name, user,
and password. For SQLite, keep `DB_CONNECTION=sqlite` and point `DB_DATABASE` at
the database file if it is not in the default location.

### Frontend assets

`npm run build` writes assets to `public/build`. For the root-document-root
deployment used here, copy the generated `public/build` contents into the
top-level `build/` directory before uploading, or configure Vite to output there.
The tracked top-level `build/` directory is the deployment copy used by the
current hosting layout.

## Notifications

Notifications are assembled from real platform activity: new lectures, upcoming
quizzes, quiz results, subscription and payment reminders, and admin digest
events. Read state is stored per item in `notification_reads`; the legacy
`notifications_seen_at` value is only a fallback for existing accounts.

The notification bell and the `/notifications` page poll for updates. They pause
while the tab is hidden, refresh when it becomes visible again, and ignore stale
responses.

## Mail and Password Resets

The application uses Laravel's password-reset flow. To deliver reset emails in
production, configure a real SMTP relay in the server `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-app-password
MAIL_FROM_ADDRESS="no-reply@your-domain.example"
MAIL_FROM_NAME="El-Molhem"
```

Use an app-specific password where the provider supports it, and never commit the
server `.env`. With `MAIL_MAILER=log`, reset links are written to the application
log instead of being delivered.

## Backups and Admin Activity

Admins can download a gzipped SQL backup from **Maintenance → Database Backup**.
The backup is generated from the active database connection and removed from
temporary storage after the download. For a second copy, download the file
regularly and keep it off the hosting account.

The **Activity Log** page records admin write actions (`POST`, `PUT`, `PATCH`,
`DELETE`) with the acting user, route, status, IP, and timestamp. Database backup
downloads are recorded there as well.

## Testing

Run the starter test suite with:

```bash
composer test
```

Feature tests use SQLite in-memory (`phpunit.xml`) and refresh the schema for each
test, so they do not touch the local development database.

## Deployment Notes

1. Create the production database and import the schema with the project
   migrations.
2. Create a production `.env` on the server; it is intentionally not in Git.
3. Upload the tracked application files and the built assets.
4. Run pending migrations with a temporary, access-protected deployment script
   or your host's console. Remove any temporary deployment script immediately
   after it runs.
5. Clear and rebuild caches, then verify the login page, dashboard, lecture
   playback, quiz submission, notifications, and uploaded materials.

Deployment helper scripts that contain a hardcoded key are ignored by Git and
must never be committed or left on a public server after use.

## Security

- Environment files, SQLite data, uploads, compiled views, logs, and sessions are
  ignored.
- Keep `APP_DEBUG=false` in production.
- Rotate any credentials that were ever stored in a local helper script.
- Use strong passwords for admin accounts and review user roles after seeding or
  restoring data.
