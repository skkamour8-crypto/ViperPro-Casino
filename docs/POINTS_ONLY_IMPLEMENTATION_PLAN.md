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

## Current status
Planning baseline only. No game files, assets, game launch logic, provider callback logic, or payment routes have been modified yet.
