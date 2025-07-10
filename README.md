1. клонировать репозиторий
```
git clone https://github.com/quueli/adminka-public.git
cd adminka-public
```

2. установить зависимости
```
composer install
```

3. настроить mysql
```
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=8.0&charset=utf8mb4"
```

4. создать базу данных
```
php bin/console doctrine:database:create
```

5. выполнить миграции
```
php bin/console doctrine:migrations:migrate
```

6. запуск сервера разработки
```
php -S 127.0.0.1:8000 -t public
```

7. открыть в браузере
```
http://127.0.0.1:8000/admin
```