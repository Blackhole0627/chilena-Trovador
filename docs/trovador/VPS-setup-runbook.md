# Trovador — VPS setup runbook

Target: Ubuntu 22.04 LTS. Run as a sudo user. Adapt paths to the client's server.
Do the demo in a **staging** path/subdomain, not the final production root.

## 0. Confirm with client before starting
- Fresh Ubuntu or pre-installed stack? RAM ≥ 2 GB?
- AWS region for Rekognition (expected `us-east-1`).
- Does `trovadorapp.com` already point to this IP?

## 1. System packages
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server redis-server ffmpeg unzip git curl \
  php8.4-fpm php8.4-cli php8.4-mysql php8.4-mbstring php8.4-xml php8.4-curl \
  php8.4-zip php8.4-gd php8.4-bcmath php8.4-intl php8.4-redis
# Composer
curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer
# Node (for asset build)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash - && sudo apt install -y nodejs
```

## 2. Database
```bash
sudo mysql -e "CREATE DATABASE trovador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'trovador'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';"
sudo mysql -e "GRANT ALL ON trovador.* TO 'trovador'@'localhost'; FLUSH PRIVILEGES;"
```

## 3. Deploy code
```bash
# upload the trovador branch (git clone from your private repo, or rsync)
cd /var/www/trovador
cp .env.sample .env
# edit .env: APP_URL, DB_*, then append blocks from .env.trovador.example
php artisan key:generate
composer install --no-dev --optimize-autoloader   # add -d memory_limit=1G if OOM
php artisan npm:i
npm run prod
php artisan migrate --force
php artisan db:seed --force        # first install only
php artisan make-admin admin@trovadorapp.com
php artisan storage:link
sudo chown -R www-data:www-data storage bootstrap/cache
```

## 4. Realtime (Laravel Reverb)
```bash
composer require laravel/reverb
php artisan reverb:install         # writes reverb connection into broadcasting config
# set BROADCAST_DRIVER=reverb and REVERB_* / PUSHER_* (see .env.trovador.example)
```
Run Reverb + queue worker as services (systemd or supervisor):
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080   # behind nginx wss proxy
php artisan queue:work --queue=default --tries=3 --timeout=310
```

## 5. Nginx + SSL
- Server block → root `/var/www/trovador/public`, PHP-FPM 8.4 socket.
- Reverse-proxy `wss://<domain>/app` to the Reverb port (8080) for websockets.
- `sudo certbot --nginx -d trovadorapp.com` for Let's Encrypt SSL.

## 6. Supervisor units (keep worker + reverb alive)
Create `/etc/supervisor/conf.d/trovador.conf` with two programs:
`trovador-queue` (queue:work) and `trovador-reverb` (reverb:start), then
`sudo supervisorctl reread && sudo supervisorctl update`.

## 7. Cron (Laravel scheduler — needed later for F2/F3 crons)
```
* * * * * cd /var/www/trovador && php artisan schedule:run >> /dev/null 2>&1
```

## Smoke test for the demo
1. Browse the site over HTTPS, log in.
2. Upload a normal photo → real-time "publicado" toast (approved).
3. Upload a known-explicit test image → "rejected" toast, preview removed.
4. See `docs/trovador/T8-rekognition-moderation.md` → "How to test".
