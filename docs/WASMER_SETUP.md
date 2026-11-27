# Wasmer browser deployment guide

Wasmer's current documentation lists Laravel/PHP repository detection and Composer installation. It also documents managed MySQL/PostgreSQL databases and the database environment variables used at runtime. This guide is for a low-traffic points-only test deployment, not a production guarantee.

## Deploy from an iPhone browser

1. Open https://wasmer.io/ and create/sign in to your account.
2. Choose **Deploy** / **Import from GitHub** and connect GitHub when asked.
3. Select `skkamour8-crypto/ViperPro-Casino`.
4. Choose the branch `points-only-demo`, not `main`.
5. Enable a managed database and choose **MySQL**.
6. Review the free-plan quotas shown in your account before confirming. Do not add a paid add-on or payment method if you want to stay on a zero-cost plan.
7. Add the private application secrets in Wasmer's dashboard:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_KEY` (generate a unique Laravel key; never reuse a public/example key)
   - `APP_URL` (the deployed Wasmer URL)
   - `DB_CONNECTION=mysql`
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USERNAME`, `DB_PASSWORD` (copy from the managed database panel)
   - `JWT_SECRET` (generate a unique secret)
8. Wait for the build/deploy result. If the platform does not build Vite assets automatically, the deployment needs a build step running `npm ci && npm run build`.
9. Before using the site, create a secure owner account and assign the `admin` role through a protected one-time process. Never use the sample admin credentials published in the repository README.
10. Test signup, points balance, admin point adjustments, and the existing game launch/callback flows with test accounts only.

## Platform compatibility note

Wasmer documents Laravel support and managed SQL databases, but this repository has not yet been deployed there. Confirm the PHP extensions required by this particular codebase, frontend asset build, database connectivity, persistent file storage, and the free-plan compute/storage/database quotas during the first deployment. If any requirement is unavailable on the free plan, stop rather than switching to a paid service automatically.

## Security

- Never paste `.env`, database passwords, `APP_KEY`, or `JWT_SECRET` into chat or commit them to GitHub.
- Keep all deposits, withdrawals, payment gateways, cash redemption, and transfers disabled.
- The games/provider callback code is preserved but still requires points-only testing before enabling providers.
