# Руководство по запуску сервера разработки Symfony 6.3

## Проблема: Команда `server:run` не найдена

В **Symfony 6.3** команда `php bin/console server:run` была **удалена**. Это устаревшая команда из более ранних версий.

## ✅ Решения для запуска сервера

### **Метод 1: Symfony CLI (Рекомендуется)**

#### Установка Symfony CLI

1. **Скачайте Symfony CLI:**
   - Перейдите на https://symfony.com/download
   - Скачайте версию для Windows

2. **Установите:**
   ```cmd
   # Если скачали .exe файл, просто запустите его
   # Или используйте Scoop (если установлен):
   scoop install symfony-cli
   ```

3. **Проверьте установку:**
   ```cmd
   symfony version
   ```

#### Запуск сервера

```cmd
# Перейдите в папку проекта
cd C:\Users\zavoe\Desktop\adminka2

# Запустите сервер
symfony serve
```

**Результат:** Сервер запустится на https://127.0.0.1:8000 (с HTTPS!)

### **Метод 2: Встроенный PHP сервер (Простой способ)**

```cmd
# Перейдите в папку проекта
cd C:\Users\zavoe\Desktop\adminka2

# Запустите встроенный PHP сервер
php -S 127.0.0.1:8000 -t public
```

**Результат:** Сервер запустится на http://127.0.0.1:8000

### **Метод 3: Использование XAMPP/WAMP**

1. Установите XAMPP или WAMP
2. Скопируйте проект в `C:\xampp\htdocs\adminka2`
3. Откройте http://localhost/adminka2/public

## ✅ Устранение предупреждений E_STRICT

### **Способ 1: Изменение настроек PHP (Временно)**

Создайте файл `.htaccess` в папке `public`:

```apache
# public/.htaccess
php_value error_reporting "E_ALL & ~E_DEPRECATED & ~E_STRICT"
```

### **Способ 2: Настройка окружения**

В файле `.env.local` добавьте:

```env
# Отключение отладочного режима
APP_ENV=prod
APP_DEBUG=0
```

### **Способ 3: Изменение php.ini**

Найдите файл `php.ini` и измените:

```ini
; Было:
error_reporting = E_ALL

; Стало:
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
```

## 🚀 Пошаговый запуск

### **Быстрый старт (Рекомендуется)**

```cmd
# 1. Перейдите в папку проекта
cd C:\Users\zavoe\Desktop\adminka2

# 2. Убедитесь, что зависимости установлены
composer install

# 3. Очистите кэш
php bin/console cache:clear

# 4. Запустите сервер (выберите один из способов):

# Способ A: PHP встроенный сервер
php -S 127.0.0.1:8000 -t public

# Способ B: Symfony CLI (если установлен)
symfony serve

# Способ C: Symfony CLI в фоне
symfony serve -d
```

### **Проверка работы**

1. Откройте браузер
2. Перейдите на http://127.0.0.1:8000
3. Вы должны увидеть русскоязычную панель администратора
4. Попробуйте создать новую запись цвета

## 🔧 Устранение проблем

### **Ошибка: "No route found"**

```cmd
# Проверьте маршруты
php bin/console debug:router

# Убедитесь, что контроллер зарегистрирован
php bin/console debug:container AdminController
```

### **Ошибка: База данных не найдена**

```cmd
# Создайте базу данных
php bin/console doctrine:database:create

# Выполните миграции
php bin/console doctrine:migrations:migrate
```

### **Ошибка: Переводы не работают**

```cmd
# Очистите кэш переводов
php bin/console cache:clear

# Проверьте переводы
php bin/console debug:translation ru
```

## 📋 Контрольный список

- [ ] Установлены зависимости (`composer install`)
- [ ] Очищен кэш (`php bin/console cache:clear`)
- [ ] Настроена база данных (`.env.local`)
- [ ] Выполнены миграции (`doctrine:migrations:migrate`)
- [ ] Выбран метод запуска сервера
- [ ] Сервер запущен успешно
- [ ] Интерфейс доступен в браузере
- [ ] Русская локализация работает
- [ ] CRUD операции с цветами функционируют

## 🎯 Рекомендуемая команда для вашего проекта

```cmd
# Самый простой и надежный способ:
cd C:\Users\zavoe\Desktop\adminka2
php -S 127.0.0.1:8000 -t public
```

После этого откройте http://127.0.0.1:8000 в браузере и протестируйте вашу панель управления цветами!

## 🔍 Дополнительные команды для отладки

```cmd
# Проверка статуса приложения
php bin/console about

# Проверка конфигурации
php bin/console config:dump framework

# Проверка сервисов
php bin/console debug:container

# Проверка событий
php bin/console debug:event-dispatcher
```
