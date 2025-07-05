# Инструкция по настройке Production режима для Symfony 6.3

## 1. Устранение E_STRICT Deprecation Warnings

### ⚠️ ВАЖНО: Проблема с PHP 8.4 и Symfony 6.3
E_STRICT deprecation warnings появляются из-за несовместимости PHP 8.4.10 с Symfony 6.3.
Эти warnings генерируются внутри Symfony ErrorHandler и не могут быть полностью подавлены
стандартными методами.

### Рекомендуемые решения:

#### Вариант A: Игнорирование warnings (рекомендуется)
Warnings не влияют на функциональность приложения. Просто игнорируйте их:
```bash
# Обычный запуск (warnings видны, но не критичны)
php -S 127.0.0.1:8000 -t public
```

#### Вариант B: Использование PHP 8.3 (если возможно)
```bash
# Установите PHP 8.3 вместо 8.4 для полной совместимости
php8.3 -S 127.0.0.1:8000 -t public
```

#### Вариант C: Обновление до Symfony 7.x (для новых проектов)
```bash
# Для новых проектов используйте Symfony 7.x с PHP 8.4
composer create-project symfony/skeleton:^7.0 new_project
```

#### Вариант D: Production режим (warnings менее заметны)
```bash
# В production режиме warnings логируются, а не отображаются
APP_ENV=prod php -S 127.0.0.1:8000 -t public
```

## 2. Настройка Production режима

### Шаг 1: Создание production базы данных
```sql
CREATE DATABASE symfony_admin_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Шаг 2: Настройка environment файлов
Файл `.env.prod.local` уже создан с настройками:
- `APP_ENV=prod`
- `APP_DEBUG=0`
- `DATABASE_URL` для production базы

### Шаг 3: Установка production зависимостей
```bash
# Установка только production зависимостей
composer install --no-dev --optimize-autoloader

# Очистка кэша
php bin/console cache:clear --env=prod

# Прогрев кэша
php bin/console cache:warmup --env=prod
```

### Шаг 4: Миграция базы данных для production
```bash
# Создание миграций для production
php bin/console doctrine:migrations:migrate --env=prod --no-interaction

# Или создание схемы с нуля
php bin/console doctrine:schema:create --env=prod
```

### Шаг 5: Оптимизация для production
```bash
# Дамп autoloader для оптимизации
composer dump-autoload --optimize --no-dev

# Компиляция .env файлов
composer dump-env prod
```

## 3. Запуск в Production режиме

### Метод 1: PHP встроенный сервер (для тестирования)
```bash
# Запуск в production режиме
APP_ENV=prod php -S 127.0.0.1:8000 -t public

# Или с использованием .env.prod.local
php -S 127.0.0.1:8000 -t public
```

### Метод 2: Apache/Nginx (рекомендуется для production)
Настройте веб-сервер для работы с `public/index.php`

## 4. Проверка production настроек

### Проверка переменных окружения:
```bash
php bin/console debug:container --env=prod --parameter=kernel.environment
```

### Проверка кэша:
```bash
php bin/console cache:pool:list --env=prod
```

### Проверка маршрутов:
```bash
php bin/console debug:router --env=prod
```

## 5. Безопасность Production

### Обязательные настройки:
1. Смените `APP_SECRET` на случайную строку
2. Используйте HTTPS в production
3. Настройте правильные права доступа к файлам
4. Отключите debug режим (`APP_DEBUG=0`)
5. Используйте production базу данных

### Рекомендуемые настройки php.ini для production:
```ini
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /path/to/error.log
expose_php = Off
opcache.enable = 1
opcache.enable_cli = 1
```

## 6. Мониторинг и логирование

### Просмотр логов:
```bash
# Логи Symfony
tail -f var/log/prod.log

# Логи PHP
tail -f error.log
```

### Очистка логов:
```bash
# Очистка старых логов
php bin/console log:clear --env=prod
```

## 7. Команды для быстрого переключения

### Development → Production:
```bash
# 1. Переключение environment
copy .env.prod.local .env.local

# 2. Очистка и прогрев кэша
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

# 3. Запуск сервера
php -S 127.0.0.1:8000 -t public
```

### Production → Development:
```bash
# 1. Восстановление dev настроек
# Восстановите оригинальный .env.local с APP_ENV=dev

# 2. Очистка кэша
php bin/console cache:clear --env=dev

# 3. Запуск сервера
php -S 127.0.0.1:8000 -t public
```

## 8. Устранение проблем

### Если не работают маршруты:
```bash
php bin/console debug:router --env=prod
php bin/console cache:clear --env=prod
```

### Если ошибки с базой данных:
```bash
php bin/console doctrine:schema:validate --env=prod
php bin/console doctrine:migrations:status --env=prod
```

### Если проблемы с правами доступа:
```bash
# Windows
icacls var /grant Users:F /T
icacls var/cache /grant Users:F /T
icacls var/log /grant Users:F /T
```

## 9. Финальная проверка

После настройки production режима проверьте:
1. ✅ Отсутствие deprecation warnings
2. ✅ Работа всех CRUD операций
3. ✅ Корректное отображение русского интерфейса
4. ✅ Быстрая загрузка страниц (кэш работает)
5. ✅ Логирование ошибок в файлы, а не в браузер
