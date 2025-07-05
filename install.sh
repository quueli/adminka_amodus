#!/bin/bash

echo "==================================="
echo "Symfony Admin Panel Installation"
echo "==================================="

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "Error: Composer is not installed. Please install Composer first."
    echo "Visit: https://getcomposer.org/download/"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed. Please install PHP 8.1 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "PHP Version: $PHP_VERSION"

# Install dependencies
echo "Installing Composer dependencies..."
composer install

# Check if .env.local exists
if [ ! -f .env.local ]; then
    echo "Creating .env.local file..."
    cp .env .env.local
    echo "Please update the DATABASE_URL in .env.local with your database credentials."
fi

# Make console executable
chmod +x bin/console

echo ""
echo "Installation completed!"
echo ""
echo "Next steps:"
echo "1. Update DATABASE_URL in .env.local"
echo "2. Create database: php bin/console doctrine:database:create"
echo "3. Run migrations: php bin/console doctrine:migrations:migrate"
echo "4. Start server: symfony server:start (or php -S localhost:8000 -t public/)"
echo "5. Visit: http://localhost:8000/admin"
echo ""
