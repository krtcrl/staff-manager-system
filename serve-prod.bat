@echo off
REM Change directory to Laravel project folder - edit this path accordingly
cd /d C:\path\to\your\laravel-project

echo Installing production dependencies...
composer install --no-dev --optimize-autoloader

echo Caching config, routes, views...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo Running Laravel server in production mode...
php artisan serve --env=production

pause
