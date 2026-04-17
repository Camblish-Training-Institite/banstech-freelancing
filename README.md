# banstech-freelancing
This is the an elite freelancing application that aims to provide both online and physical services.

## Deployment notes

If the app shows `419 Page Expired` on `POST /login` in production, the usual cause is that the CSRF token is rendered on `GET /login` but the browser does not send the same Laravel session back on submit.

For cPanel deployments:

1. Set the production environment values correctly in `.env`:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://banstech.co.za`
   - `SESSION_DRIVER=database`
   - `SESSION_DOMAIN=banstech.co.za`
   - `SESSION_SECURE_COOKIE=true`
   - `SESSION_SAME_SITE=lax`
2. Make sure the site uses one canonical host only. Do not mix `banstech.co.za` and `www.banstech.co.za` unless the session domain is configured for both.
3. Clear stale Laravel caches after updating `.env`:
   - `php artisan optimize:clear`
4. Do not deploy generated files from `bootstrap/cache/*.php` from local development.
5. Make sure Apache/Nginx and cPanel forward proxy headers correctly so Laravel can detect HTTPS.
6. If login still returns `419`, clear browser cookies for the domain and confirm the app is not switching between `www.banstech.co.za` and `banstech.co.za`.
7. As a cPanel fallback, temporarily test `SESSION_DRIVER=file`. If that fixes the issue, the problem is production database session writes rather than the login form itself.
