## 🔧 Системные требования

### Минимальные требования:
- **PHP**: 8.1 или выше
- **Composer**: 2.0 или выше
- **База данных**: MySQL 8.0+ или PostgreSQL 13+
- **Веб-сервер**: Apache 2.4+ или Nginx 1.18+

### Необходимые PHP расширения:
```
php-cli, php-fpm, php-mysql (или php-pgsql), php-xml, php-mbstring,
php-curl, php-zip, php-intl, php-gd, php-json
```

## 🚀 Установка

### Windows (Command Prompt / PowerShell)

#### 1. Установка PHP
Скачайте PHP с официального сайта: https://windows.php.net/download/
```cmd
# Добавьте PHP в PATH системы
# Проверьте установку
php --version
```

#### 2. Установка Composer
Скачайте с https://getcomposer.org/download/
```cmd
# Проверьте установку
composer --version
```

#### 3. Установка MySQL
Скачайте MySQL Community Server: https://dev.mysql.com/downloads/mysql/
```cmd
# Создайте базу данных
mysql -u root -p
CREATE DATABASE symfony_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### 4. Клонирование проекта
```cmd
git clone https://github.com/quueli/adminka-public.git symfony-admin
cd symfony-admin
```

#### 5. Установка зависимостей
```cmd
composer install
```

#### 6. Настройка окружения
```cmd
# Скопируйте файл окружения
copy .env .env.local

# Отредактируйте .env.local в текстовом редакторе
# Установите параметры базы данных:
DATABASE_URL="mysql://root:password@127.0.0.1:3306/symfony_admin?serverVersion=8.0&charset=utf8mb4"
```

#### 7. Настройка базы данных
```cmd
# Создайте базу данных
php bin/console doctrine:database:create

# Выполните миграции
php bin/console doctrine:migrations:migrate
```

#### 8. Запуск сервера разработки
```cmd
# Вариант 1: Встроенный PHP сервер
php -S 127.0.0.1:8000 -t public

# Вариант 2: Symfony CLI (если установлен)
symfony serve
```

### macOS (Terminal)

#### 1. Установка PHP через Homebrew
```bash
# Установите Homebrew (если не установлен)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Установите PHP
brew install php@8.1
brew link php@8.1

# Проверьте установку
php --version
```

#### 2. Установка Composer
```bash
# Установите Composer
brew install composer

# Проверьте установку
composer --version
```

#### 3. Установка MySQL
```bash
# Установите MySQL
brew install mysql

# Запустите MySQL
brew services start mysql

# Создайте базу данных
mysql -u root -p
CREATE DATABASE symfony_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### 4. Клонирование проекта
```bash
git clone https://github.com/quueli/adminka-public.git symfony-admin
cd symfony-admin
```

#### 5. Установка зависимостей
```bash
composer install
```

#### 6. Настройка окружения
```bash
# Скопируйте файл окружения
cp .env .env.local

# Отредактируйте .env.local
nano .env.local

# Установите параметры базы данных:
DATABASE_URL="mysql://root:password@127.0.0.1:3306/symfony_admin?serverVersion=8.0&charset=utf8mb4"
```

#### 7. Настройка базы данных
```bash
# Создайте базу данных
php bin/console doctrine:database:create

# Выполните миграции
php bin/console doctrine:migrations:migrate
```

#### 8. Запуск сервера разработки
```bash
# Вариант 1: Встроенный PHP сервер
php -S 127.0.0.1:8000 -t public

# Вариант 2: Symfony CLI (если установлен)
symfony serve
```

## 🌐 Доступ к админ-интерфейсу

После успешного запуска сервера откройте браузер и перейдите по адресу:

```
http://localhost:8000
```

### Полезные команды Symfony
```bash
# Очистка кэша
php bin/console cache:clear

# Проверка маршрутов
php bin/console debug:router

# Проверка сервисов
php bin/console debug:container

# Создание новой миграции
php bin/console make:migration

# Выполнение миграций
php bin/console doctrine:migrations:migrate
```

## 🔧 Устранение неполадок

### Общие проблемы

#### 1. Ошибка "Class not found" или "Autoload"
```bash
# Обновите автозагрузчик Composer
composer dump-autoload

# Очистите кэш Symfony
php bin/console cache:clear
```

#### 2. Проблемы с правами доступа (macOS/Linux)
```bash
# Установите правильные права на директории
chmod -R 755 var/
chmod -R 755 public/
```

#### 3. Ошибки базы данных
```bash
# Проверьте подключение к базе данных
php bin/console doctrine:database:create

# Проверьте статус миграций
php bin/console doctrine:migrations:status

# Принудительно выполните миграции
php bin/console doctrine:migrations:migrate --no-interaction
```

### Windows-специфичные проблемы

#### 1. PHP не найден в PATH
```cmd
# Добавьте PHP в переменную PATH:
# Панель управления → Система → Дополнительные параметры системы → Переменные среды
# Добавьте путь к PHP (например: C:\php) в PATH
```

#### 2. Ошибки с расширениями PHP
```cmd
# Отредактируйте php.ini и раскомментируйте нужные расширения:
extension=pdo_mysql
extension=mbstring
extension=curl
extension=zip
extension=intl
```

#### 3. Проблемы с Composer
```cmd
# Переустановите Composer глобально
# Скачайте Composer-Setup.exe с официального сайта
```

### macOS-специфичные проблемы

#### 1. Проблемы с Homebrew
```bash
# Обновите Homebrew
brew update
brew upgrade

# Переустановите PHP
brew uninstall php@8.1
brew install php@8.1
brew link php@8.1 --force
```

#### 2. Конфликты версий PHP
```bash
# Проверьте активную версию PHP
which php
php --version

# Переключитесь на нужную версию
brew unlink php@8.0
brew link php@8.1 --force
```

#### 3. Проблемы с MySQL
```bash
# Перезапустите MySQL
brew services restart mysql

# Сбросьте пароль root (если забыли)
brew services stop mysql
mysqld_safe --skip-grant-tables &
mysql -u root
```

### Проверка работоспособности

#### Тест подключения к базе данных:
```bash
php bin/console doctrine:query:sql "SELECT 1"
```

#### Проверка маршрутов:
```bash
php bin/console debug:router | grep nomenclature
```

#### Тест веб-сервера:
```bash
curl http://localhost:8000
```

## 📚 Дополнительная информация

### Технологии
- **Backend**: Symfony 6+, PHP 8.1+
- **Frontend**: Bootstrap 5, Font Awesome, JavaScript
- **База данных**: MySQL 8.0+ / PostgreSQL 13+
- **Шаблонизатор**: Twig
- **ORM**: Doctrine
- **Локализация**: Symfony Translation Component