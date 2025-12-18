# Deployment Instructions for Debian Server

## Prerequisites

- Debian Server (e.g. Debian 12 Bookworm)
- PHP 8.2 or higher
- Composer
- Nginx / Apache
- MySQL / MariaDB (or SQLite)
- Supervisor

## Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/kucingpresto/BelajarCerdas.id.git /var/www/
    cd /var/www/
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install --optimize-autoloader --no-dev
    ```

3.  **Environment Configuration:**
    ```bash
    cp .env.example .env
    # Edit .env file with your database, mail, and other credentials
    nano .env
    ```
    Make sure to set `APP_ENV=production` and `APP_DEBUG=false`.

4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5.  **Database Migration:**
    ```bash
    php artisan migrate --force
    ```

6.  **Set Permissions:**
    ```bash
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    ```

7.  **Web Server Configuration (Nginx):**
    - Copy `deployment/nginx.conf` to `/etc/nginx/sites-available/your-site.conf`.
    - Update the `server_name` and `root` path if necessary.
    - Enable the site: `ln -s /etc/nginx/sites-available/your-site.conf /etc/nginx/sites-enabled/`.
    - Restart Nginx: `systemctl restart nginx`.

8.  **Queue Worker (Supervisor):**
    - Copy `deployment/supervisor-laravel.conf` to `/etc/supervisor/conf.d/laravel-worker.conf`.
    - Update the `command` path if necessary.
    - Start Supervisor:
        ```bash
        supervisorctl reread
        supervisorctl update
        supervisorctl start laravel-worker:*
        ```

9.  **Task Scheduler (Cron):**
    - Add the cron entry from `deployment/cron` to the user's crontab (usually `www-data` or root):
        ```bash
        crontab -e
        ```
    - Append: `* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1`

## Midtrans Configuration

Ensure you add your Midtrans keys in `.env`:
```
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
```
