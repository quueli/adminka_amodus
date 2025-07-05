# Руководство по исправлению ошибок миграции

## Проблемы и их решения

### 1. Ошибка "Translation component is not installed"

**Проблема:** Отсутствует компонент `symfony/translation`

**Решение:**
```cmd
composer require symfony/translation
```

Или если у вас уже обновлен `composer.json`, выполните:
```cmd
composer install
```

### 2. PHP Deprecation Warnings (E_STRICT)

**Проблема:** Предупреждения о deprecated константе E_STRICT

**Решение:** Эти предупреждения не критичны для работы приложения, но их можно подавить:

1. В файле `php.ini` найдите строку `error_reporting` и установите:
   ```ini
   error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
   ```

2. Или временно в `.env.local`:
   ```
   APP_ENV=prod
   ```

### 3. Проверка миграции

Обновленная миграция использует Doctrine Schema API вместо сырого SQL, что более надежно.

## Пошаговое исправление

### Шаг 1: Установка недостающих компонентов

```cmd
# Перейдите в папку проекта
cd C:\path\to\your\project

# Установите недостающие зависимости
composer install

# Если возникают ошибки, попробуйте обновить
composer update
```

### Шаг 2: Очистка кэша

```cmd
php bin/console cache:clear
```

### Шаг 3: Проверка конфигурации базы данных

Убедитесь, что файл `.env.local` содержит правильные настройки:

```env
# Database configuration
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=8.0&charset=utf8mb4"

# Application environment
APP_ENV=dev
APP_SECRET=your_secret_key_here
```

### Шаг 4: Создание базы данных (если не существует)

```cmd
php bin/console doctrine:database:create
```

### Шаг 5: Выполнение миграции

```cmd
# Проверьте статус миграций
php bin/console doctrine:migrations:status

# Выполните миграцию
php bin/console doctrine:migrations:migrate

# Подтвердите выполнение, когда система спросит
```

### Шаг 6: Проверка структуры таблицы

```cmd
# Проверьте, что таблица создана правильно
php bin/console doctrine:schema:validate
```

## Проверка результата

После успешного выполнения миграции у вас должна быть создана таблица `colors` со следующими полями:

- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `color` (VARCHAR(255), NOT NULL) - Цвет
- `hex_color_number` (VARCHAR(255), NOT NULL) - Номер Hex цвета  
- `saturation` (VARCHAR(255), NOT NULL) - Насыщенность
- `palette` (VARCHAR(255), NOT NULL) - Палитра
- `created_at` (DATETIME, NOT NULL)
- `updated_at` (DATETIME, NOT NULL)

## Тестирование

### Запуск сервера разработки

```cmd
php bin/console server:run
```

### Проверка в браузере

1. Откройте http://127.0.0.1:8000
2. Интерфейс должен быть на русском языке
3. Попробуйте создать новую запись цвета
4. Проверьте, что все поля отображаются правильно

## Устранение дополнительных проблем

### Если миграция не выполняется

```cmd
# Сброс миграций (ОСТОРОЖНО: удалит данные!)
php bin/console doctrine:schema:drop --force
php bin/console doctrine:migrations:migrate
```

### Если возникают ошибки прав доступа

```cmd
# Windows
icacls var /grant Everyone:F /T

# Создание папок если они не существуют
mkdir var\cache var\log
```

### Если не работают переводы

```cmd
# Очистка кэша переводов
php bin/console cache:clear
php bin/console translation:update --force ru
```

## Полезные команды для отладки

```cmd
# Проверка конфигурации
php bin/console about

# Проверка маршрутов
php bin/console debug:router

# Проверка сервисов
php bin/console debug:container

# Проверка переводов
php bin/console debug:translation ru

# Проверка схемы базы данных
php bin/console doctrine:schema:validate
```

## Контрольный список

- [ ] Установлен компонент `symfony/translation`
- [ ] Очищен кэш приложения
- [ ] Настроена база данных в `.env.local`
- [ ] Создана база данных
- [ ] Выполнена миграция
- [ ] Проверена структура таблицы
- [ ] Запущен сервер разработки
- [ ] Проверен интерфейс на русском языке
- [ ] Протестировано создание записи

Если все пункты выполнены, ваша панель администратора должна работать корректно!
