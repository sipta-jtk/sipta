@echo off
:loop
echo Starting Laravel development server...
php artisan serve
if %ERRORLEVEL% neq 0 (
    echo Server crashed. Restarting...
    goto loop
)