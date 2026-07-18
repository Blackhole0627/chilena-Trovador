# T9 — Move credentials from settings table to .env

## Approach: env-first, settings-fallback (non-breaking)

Rather than ripping out every `getSetting()` call blind (risky — breaks login/payments
if a key is missed), each credential now reads **`.env` first and falls back to the
existing admin setting**. So:
- If the `.env` value is present → it wins (credentials live in `.env`, as required).
- If absent → the old settings-table value still works (nothing breaks mid-migration).

The single injection point is `SettingsServiceProvider` (it already pushes settings into
Laravel `config()` at boot).

## Done

- **Google OAuth** — `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` (SettingsServiceProvider).
- **Facebook OAuth** — `FACEBOOK_CLIENT_ID` / `FACEBOOK_CLIENT_SECRET`.
- **AWS Rekognition** — already env-driven via `config/rekognition.php` (T8).
- **`.env.trovador.example`** — full key list: OAuth, Spotify, MercadoPago, Resend, R2.

## Remaining (finish + verify with the app running)

Apply the same env-first one-liner at each injection site, then test that integration:

1. **Spotify** — find where spotify client id/secret are injected into config and wrap
   with `env('SPOTIFY_CLIENT_ID', getSetting(...))`. Test Spotify connect.
2. **MercadoPago** — locate the token read (SDK init / PaymentHelper) and prefer
   `env('MERCADOPAGO_ACCESS_TOKEN', getSetting('payments.mercadopago_*'))`. Test a
   **sandbox** payment before switching to the production token.
3. **Resend** — set `MAIL_MAILER`/Resend key from env; send a test email.
4. **R2 storage** — set the R2 disk from env (`config/filesystems.php`); upload a file.

## Test checklist (per credential, on the VPS)
- Google login → Facebook login → Spotify connect → MercadoPago sandbox payment →
  Resend test email → R2 upload. Each must succeed with the value coming from `.env`
  (temporarily blank the admin setting to confirm env is actually the source).

## Security
`.env` is already gitignored. Never commit real keys. Use MercadoPago **TEST**
credentials until go-live. Rotate all keys after launch (they traveled via PDF/chat).
