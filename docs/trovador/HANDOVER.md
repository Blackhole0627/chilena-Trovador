# Trovador — Handover & Maintenance Guide

Chilean creator-subscription platform built on JustFans **v11.2.0** (Laravel 12 / Filament 4 / Livewire 3).
Live at **https://trovadorapp.com**.

---

## 1. Server access

- **Host:** `179.197.78.122` (Hostinger KVM2, Ubuntu 24.04, 2 vCPU / 8 GB / 100 GB)
- **SSH:** key-only (password login disabled). Connect with your installed key:
  `ssh root@179.197.78.122`
- **App path:** `/var/www/trovador` (Laravel root; public web root is `/var/www/trovador/public`)
- **Web user:** `www-data`

Credentials/secrets stored on the server:
- App config & keys: `/var/www/trovador/.env`
- Database password: `/root/trovador_db_pass.txt`
- Admin panel password: `/root/trovador_admin_pass.txt`
- Root password (recovery only; SSH is key-only): `/root/.root_password.txt`

**Admin panel:** https://trovadorapp.com/admin — `admin@trovadorapp.com`

---

## 2. Services (all auto-start on boot)

| Service | Role | Manage |
|---|---|---|
| nginx | web server (+ SSL, + `/app` Reverb proxy) | `systemctl status/reload nginx` |
| php8.4-fpm | PHP runtime | `systemctl restart php8.4-fpm` |
| mysql | database `trovador` | `systemctl status mysql` |
| redis-server | cache + queue backend | `systemctl status redis-server` |
| **supervisor** | runs Reverb + queue workers | `supervisorctl status` |
| cron | Laravel scheduler (`schedule:run` every min) | `crontab -l` |

**Supervisor programs** (`/etc/supervisor/conf.d/trovador.conf`):
- `trovador-reverb` — websocket server on port 8080 (realtime: live comments, moderation notify)
- `trovador-worker` (x2) — Redis queue workers (async jobs: Rekognition moderation, notifications)

Restart background workers after a code change:
`supervisorctl restart all`

---

## 3. Updating the code

The repo is on GitHub (`main` branch). To deploy an update:

```bash
cd /var/www/trovador
# pull new code (or upload), then:
composer install --no-dev --optimize-autoloader             # if deps changed
php artisan migrate --force                                 # if new migrations
export NODE_OPTIONS=--openssl-legacy-provider
npm run prod                                                # if SCSS/JS changed
chown -R www-data:www-data storage bootstrap/cache public/css public/js
php artisan optimize:clear
supervisorctl restart all
```

**Frontend theme (SCSS) changes** must be recompiled with `npm run prod` (Laravel Mix v4 needs the
`NODE_OPTIONS=--openssl-legacy-provider` flag on modern Node).

---

## 4. What's configured

- **Branding:** Trovador logo + favicon (`public/img/branding/`, also on the public storage disk), dark
  `#141210` palette + coral `#FF5A5F` accent (compiled), Spanish (LATAM) default, homepage = Explore.
- **Payments:** PayPal (live mode) + MercadoPago (**TEST token** — switch to production at go-live in
  Admin → Settings → Payments). CCBill/Verotel removed. Platform fee = **15%** (withdrawal fee).
- **Email:** Resend (`no-reply@trovadorapp.com`).
- **Moderation (T8):** AWS Rekognition on image/video uploads — auto-reject >85%, manual-review 70–85%,
  auto-approve <70% (thresholds configurable in Admin → Settings → AI). Runs on the queue workers.
- **Realtime:** Laravel Reverb (self-hosted, Pusher-protocol). Backend → `127.0.0.1:8080`; browser →
  `wss://trovadorapp.com/app` (nginx proxy). Live comments broadcast flat (TikTok-Live style, no reply threads).
- **Crons:** streak badges (daily), scheduled-live push reminders (24 h + 15 min), plus base JustFans crons.
- **Compliance:** consent checkbox required at registration; cookie banner (3-option opt-in) enabled.
- **Legal pages:** 8 pages created (Spanish slugs, footer-linked) — content loaded by the owner from Admin → Pages.
- **Security:** `APP_DEBUG=false`, UFW firewall (22/80/443), SSH key-only, SSL (Let's Encrypt, auto-renew),
  channel authorization verifies real access before signing (T10).

---

## 5. Custom Trovador features (where they live)

| Feature | Key files |
|---|---|
| Rekognition moderation (T8) | `app/Services/Moderation/`, `app/Jobs/ModerateAttachmentJob.php`, `config/rekognition.php` |
| Live comments (T6) | `app/Events/NewPostCommentEvent.php`, `NewReelCommentEvent.php`, `public/js/Websockets.js` |
| Comment toggle (T7) | `posts.comments_enabled`, `post-create-actions.blade.php` |
| Channel security (T10) | `PostsController::authorizePostChannel`, `StreamsController::authorizeUser` |
| Expiry countdown (F1) | `public/js/trovador/post-countdown.js`, `post-box.blade.php` |
| Streak badges (F3) | `CronCheckStreaks`, `users.streak_*`, `streak-badge.blade.php` |
| Welcome audio (F5) | `users.welcome_audio`, `SettingsController`, `profile-details.blade.php` |
| Scheduled lives (F2) | `CronSendStreamNotifications`, `streams.scheduled_at` |
| Featured feed (F4) | `app/Helpers/FeaturedContentHelper.php`, `feed.featured_highlights_enabled` |

Per-task notes are in `docs/trovador/*.md`.

---

## 6. Pending / owner actions

- **MercadoPago → production:** swap the TEST token for the production one (Admin → Settings → Payments) before real launch.
- **Cloudflare R2 (optional):** currently on local storage. To activate R2, provide the bucket **public URL**
  (r2.dev URL or a custom domain like `media.trovadorapp.com`) and set it as "Custom URL" in Admin → Settings → Storage.
- **Email domain:** verify `trovadorapp.com` in the Resend dashboard (DNS records) so mail delivers reliably.
- **Legal content:** load the 8 pages' text from Admin → Pages.
- **Cloudflare SSL:** set to **Full (strict)** (done).

---

## 7. Common maintenance commands

```bash
# logs
tail -f /var/www/trovador/storage/logs/laravel.log
tail -f /var/log/trovador-reverb.log
tail -f /var/log/trovador-worker.log

# clear caches after a settings/config change
cd /var/www/trovador && php artisan optimize:clear

# check queue is processing / reverb is up
supervisorctl status

# SSL renews automatically; test with:
certbot renew --dry-run
```
