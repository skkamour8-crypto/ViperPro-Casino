# Deployment checklist (points-only branch)

This project is a Laravel/PHP application, not a static website. A host must support the PHP version and extensions required by `composer.json`, Composer installation, Laravel's writable `storage` and `bootstrap/cache` directories, a supported SQL database, HTTPS, and a document root pointed at the project's `public/` directory.

## Before deployment

1. Use a clean database for this points-only branch. Do not import production balances or payment credentials.
2. Configure the environment variables on the host's private environment settings. Never commit `.env`, database passwords, JWT secrets, mail credentials, or provider secrets.
3. Install dependencies with Composer on the host or in a compatible build environment.
4. Generate a new Laravel application key and configure the database connection.
5. Run Laravel migrations only after reviewing the complete migration set against the empty database.
6. Create or promote the site owner account to the `admin` role through a secure, one-time deployment procedure. Do not use the published sample admin credentials in the repository README.
7. Set the web root to `public/`; enable HTTPS; ensure `storage/` and `bootstrap/cache` are writable by the PHP process.
8. Confirm that deposit, withdrawal, and payment gateway endpoints remain unavailable.
9. Test registration (all wallet balances must be zero), admin point adjustments, audit history, user balance display, and game launch/callback behavior with test accounts only.

## Important

- The game catalog and game/provider launch source files are intentionally preserved.
- Provider callback code has not yet been fully audited for points-only operation. Keep a provider unavailable until its wager, win, rollback, and balance callbacks are verified in a test environment.
- Do not enable real-money deposits, withdrawals, transfers, redemption, or payment gateways.
- This checklist is not proof that the app has been deployed or tested. The branch still requires a PHP/Laravel build and database integration test before public use.
