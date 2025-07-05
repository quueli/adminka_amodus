# Руководство по установке и настройке на Windows

## Требования к системе

- Windows 10/11 (64-bit)
- Минимум 4 ГБ RAM
- 2 ГБ свободного места на диске

## Необходимое программное обеспечение

### 1. PHP 8.1 или выше
**Скачать:** https://windows.php.net/download/
- Выберите "Thread Safe" версию для Windows x64
- Рекомендуемая версия: PHP 8.2.x

### 2. Composer (менеджер пакетов PHP)
**Скачать:** https://getcomposer.org/download/
- Скачайте Composer-Setup.exe для Windows

### 3. MySQL или MariaDB
**MySQL:** https://dev.mysql.com/downloads/mysql/
**MariaDB:** https://mariadb.org/download/
- Рекомендуется MySQL 8.0+ или MariaDB 10.6+

### 4. Git (опционально, но рекомендуется)
**Скачать:** https://git-scm.com/download/win

## Пошаговая установка

### Шаг 1: Установка PHP

1. Скачайте PHP с официального сайта
2. Распакуйте архив в папку `C:\php`
3. Добавьте `C:\php` в переменную PATH:
   - Откройте "Система" → "Дополнительные параметры системы"
   - Нажмите "Переменные среды"
   - В "Системные переменные" найдите PATH и нажмите "Изменить"
   - Добавьте `C:\php`
4. Скопируйте `php.ini-development` в `php.ini`
5. Откройте `php.ini` и раскомментируйте следующие расширения:
   ```
   extension=pdo_mysql
   extension=mysqli
   extension=mbstring
   extension=openssl
   extension=curl
   extension=fileinfo
   extension=intl
   ```

### Шаг 2: Установка Composer

1. Запустите скачанный Composer-Setup.exe
2. Следуйте инструкциям установщика
3. Убедитесь, что Composer добавлен в PATH

### Шаг 3: Установка MySQL/MariaDB

1. Запустите установщик MySQL или MariaDB
2. Выберите "Developer Default" конфигурацию
3. Установите пароль для root пользователя (запомните его!)
4. Завершите установку

### Шаг 4: Настройка базы данных

1. Откройте MySQL Command Line Client или phpMyAdmin
2. Создайте базу данных:
   ```sql
   CREATE DATABASE symfony_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Создайте пользователя (опционально):
   ```sql
   CREATE USER 'symfony_user'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON symfony_admin.* TO 'symfony_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

## Настройка проекта

### Шаг 1: Подготовка проекта

1. Откройте командную строку (cmd) или PowerShell
2. Перейдите в папку с проектом:
   ```cmd
   cd C:\path\to\your\project
   ```

### Шаг 2: Установка зависимостей

```cmd
composer install
```

### Шаг 3: Настройка переменных окружения

1. Скопируйте `.env` в `.env.local`:
   ```cmd
   copy .env .env.local
   ```
2. Отредактируйте `.env.local` и настройте подключение к базе данных:
   ```
   DATABASE_URL="mysql://root:your_password@127.0.0.1:3306/symfony_admin"
   ```

### Шаг 4: Создание структуры базы данных

```cmd
php bin/console doctrine:migrations:migrate
```

## Запуск проекта

### Вариант 1: Встроенный сервер Symfony

```cmd
php bin/console server:run
```

Проект будет доступен по адресу: http://127.0.0.1:8000

### Вариант 2: Использование XAMPP/WAMP

1. Установите XAMPP или WAMP
2. Скопируйте проект в папку `htdocs` (XAMPP) или `www` (WAMP)
3. Настройте виртуальный хост в Apache

## Проверка установки

1. Откройте браузер и перейдите по адресу проекта
2. Вы должны увидеть панель администратора на русском языке
3. Попробуйте создать новую запись цвета

## Устранение неполадок

### Проблема: "Class not found" ошибки
**Решение:**
```cmd
composer dump-autoload
```

### Проблема: Ошибки подключения к базе данных
**Решение:**
1. Проверьте настройки в `.env.local`
2. Убедитесь, что MySQL/MariaDB запущен
3. Проверьте права доступа пользователя

### Проблема: Ошибки прав доступа
**Решение:**
```cmd
mkdir var\cache var\log
icacls var /grant Everyone:F /T
```

### Проблема: Отсутствуют переводы
**Решение:**
```cmd
php bin/console cache:clear
```

## Полезные команды

### Очистка кэша
```cmd
php bin/console cache:clear
```

### Создание новой миграции
```cmd
php bin/console make:migration
```

### Просмотр маршрутов
```cmd
php bin/console debug:router
```

### Проверка конфигурации
```cmd
php bin/console about
```

## Рекомендации по безопасности

1. Измените пароли по умолчанию
2. Настройте файрвол для базы данных
3. Используйте HTTPS в продакшене
4. Регулярно обновляйте зависимости:
   ```cmd
   composer update
   ```

## Поддержка

При возникновении проблем:
1. Проверьте логи в папке `var/log/`
2. Убедитесь, что все требования выполнены
3. Проверьте настройки PHP и базы данных
