**Laravel Website Monitoring System**

A robust, real-time website monitoring dashboard built with Laravel and Vite. This system allows you to manage a list of websites, upload custom logos, and actively monitor their UP/DOWN status using HTTP response checks.

**Features:**
  1.  Active Uptime Monitoring: Verifies website status using reliable HTTP/HTTPS GET requests (bypassing ICMP/ping firewall restrictions).
  2.  Auto-Refreshing Dashboard: The UI automatically polls for the latest statuses every 60 seconds without requiring a manual page reload.
  3.  Asset Management: Allows uploading and displaying custom logos for each monitored website.
  4.  Modern Frontend: Asset bundling and hot-module replacement powered by Vite.
  5.  Automated Background Checks: Utilizes Laravel's Task Scheduler to perform background status verifications.

**Prerequisites**

Ensure your server (Ubuntu recommended) has the following installed:

1. PHP 8.1+
2. Composer
3. Node.js (v20 LTS recommended) & NPM
4. Nginx (or Apache)
5. MySQL / MariaDB or PostgreSQL

**Installation & Setup**

1. Clone the repository and install PHP dependencies
    git clone <your-repository-url> /var/www/laravel
    cd /var/www/laravel
    composer install
   
2. Configure the Environment
    cp .env.example .env
    php artisan key:generate
"Update your .env file with your database credentials and set your APP_URL to your production domain (e.g., APP_URL=[https://yourdomain.com](https://yourdomain.com))."

3. Set up the Database
     php artisan migrate

4. Link Storage for Logos
    To ensure uploaded website logos are publicly accessible, create the storage symbolic link:
    php artisan storage:link

5. Install and Build Frontend Assets
This project uses Vite to compile CSS and JavaScript.
  npm install
  npm run build

Background Scheduler (Cron)
To enable the automatic background checks for website uptime, you must add Laravel's schedule runner to your server's cron tab.
  Open your crontab: crontab -e
  
Add the following line to run the scheduler every minute:
  * * * * * cd /var/www/laravel && php artisan schedule:run >> /dev/null 2>&1

Troubleshooting Common Issues
1. Vite Manifest Not Found (resources/js/app.js)

If you encounter an error stating Unable to locate file in Vite manifest, it means the frontend assets failed to build or the cache is stale.
Fix: rm -rf public/build
     npm run build
     php artisan view:clear
     php artisan config:clear
Note: Ensure your vite.config.js input array exactly matches the @vite directive in your app.blade.php.

2. Logos Are Not Appearing (404 Error)
If website logos show as broken images when adding/editing:
  Ensure you ran php artisan storage:link.
  Ensure the storage directory has proper permissions:
      sudo chown -R www-data:www-data /var/www/laravel/storage
      sudo chmod -R 775 /var/www/laravel/storage/app/public

3. Websites Incorrectly Showing as "DOWN" (SSL Errors)
If valid websites are showing as DOWN, your server's CA certificates might be outdated, causing the HTTP client to reject valid SSL certificates.
Fix: Update your server's certificates:
  sudo apt update
  sudo apt install --reinstall ca-certificates
  sudo update-ca-certificates

4. Dashboard Refreshes Too Quickly

If the dashboard is flickering or polling too rapidly, check the JavaScript setInterval function or the Livewire wire:poll directive in the dashboard view and ensure it is set to 60000 (60 seconds) rather than 1 second. Run npm run build after modifying any JS files.
