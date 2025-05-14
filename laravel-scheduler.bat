@echo off
:start
SET PATH=C:\laragon\bin\php\php-8.3.6;%PATH%
cd /d "C:\laragon\www\BelajarCerdas"
php artisan schedule:run >> "C:\laragon\www\BelajarCerdas\storage\logs\scheduler.log" 2>&1
goto start
