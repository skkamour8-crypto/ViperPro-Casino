# ViperPro Points-Only Project Plan

## Scope
This fork is being prepared as a points-only entertainment platform. Existing game catalog, game assets, game names, provider configuration, and game launch code are to remain unchanged unless a separate security review proves a specific change is required and the owner approves it.

## Account and points rules
- Every newly registered account starts with zero points.
- Points have no cash value and cannot be deposited, withdrawn, transferred, sold, or redeemed.
- Only an authenticated administrator may add or subtract points.
- Every administrator adjustment must be recorded with the acting administrator, target account, amount, reason, and timestamp.
- Users may view their points balance and point history.
- Point balances must never be exposed as fiat or cryptocurrency balances.

## Payment restrictions
- Disable customer-facing deposit and withdrawal workflows and payment gateway endpoints for this points-only deployment.
- Do not configure Stripe, Suitpay, BSPay, cryptocurrency, or other real-money payment credentials.
- Do not remove or rewrite game catalog/assets as part of this project.
- Before enabling any external provider integration, audit its wallet callbacks and confirm that wagers and wins can only change the non-redeemable points balance. A provider that cannot be safely operated in points-only mode must remain unavailable until that is verified.

## Admin security
- Replace hard-coded email-based admin access with a secure role/permission check.
- Do not use credentials published in the upstream README.
- Create the first administrator through a secure deployment-time process; never commit passwords, API secrets, or environment files.

## Implementation sequence
1. Preserve the existing games and record a baseline of game-related files.
2. Trace registration and wallet creation to ensure the initial points balance is zero.
3. Implement administrator-only point adjustments with an immutable audit trail.
4. Remove/disable payment and withdrawal routes from the points-only runtime.
5. Review every game/provider wallet callback for points-only behavior without changing game content or presentation.
6. Run automated tests for registration, authorization, balance changes, negative-balance prevention, and payment-route unavailability.
7. Deploy only after tests pass and the hosting environment has persistent database storage and secure secrets.

## Implemented on branch `points-only-demo`
- Registration now explicitly initializes balance, bonus, withdrawal, and demo balance fields to zero.
- Registration no longer grants spin-token bonus credits.
- Added a points transaction ledger migration with administrator, user, action, amount, before/after balance, reason, and timestamps.
- Added authenticated points API endpoints:
  - `GET /api/points/me`
  - `GET /api/points/admin/users` (admin role only)
  - `POST /api/points/admin/adjust` (admin role only)
- Added a Filament admin page at `/admin` named **Points manager**. It supports adding/deducting points and records the reason and audit entry.
- Admin panel access now requires the explicit `admin` role rather than a hard-coded published email address.
- Removed customer deposit/withdraw API route includes and web payment gateway route includes.
- Hid the legacy wallet editor, deposit/withdrawal admin resources, and payment gateway admin pages; the new Points manager is the supported admin balance control.
- Existing game catalog, game assets, game names, game launch code, and provider callback code have not been edited.

## Not yet verified
- The repository has not been built or run in a PHP/Laravel environment during this session. Run `composer install`, database migrations, and the project's automated tests before deployment.
- External provider wallet callbacks have not yet been fully audited. The existing game launch/callback code is unchanged; test every provider in an isolated environment and keep any provider whose callbacks cannot be proven to operate only on non-redeemable points disabled until reviewed.
- This is committed to the feature branch only; it has not been deployed to a public host.
- Existing admin payment-related Filament resources may still be visible in the admin navigation and need a separate UI cleanup.
- Do not add real-money payment credentials or configure cash deposits, withdrawals, transfers, or redemption.
