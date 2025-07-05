@echo off
echo ===================================
echo Symfony Admin Panel Installation
echo ===================================

REM Check if composer is installed
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo Error: Composer is not installed. Please install Composer first.
    echo Visit: https://getcomposer.org/download/
    pause
    exit /b 1
)

REM Check if PHP is installed
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo Error: PHP is not installed. Please install PHP 8.1 or higher.
    pause
    exit /b 1
)

REM Check PHP version
for /f "tokens=*" %%i in ('php -r "echo PHP_VERSION;"') do set PHP_VERSION=%%i
echo PHP Version: %PHP_VERSION%

REM Install dependencies
echo Installing Composer dependencies...
composer install

REM Check if .env.local exists
if not exist .env.local (
    echo Creating .env.local file...
    copy .env .env.local
    echo Please update the DATABASE_URL in .env.local with your database credentials.
)

echo.
echo Installation completed!
echo.
echo Next steps:
echo 1. Update DATABASE_URL in .env.local
echo 2. Create database: php bin/console doctrine:database:create
echo 3. Run migrations: php bin/console doctrine:migrations:migrate
echo 4. Start server: php -S localhost:8000 -t public/
echo 5. Visit: http://localhost:8000/admin
echo.
pause
