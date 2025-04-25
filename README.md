# Setup Instructions

### To set up and run this project locally, follow these steps:

```bash
git clone https://github.com/agungd3v/laravel12.git
cd laravel12
```

Create environment file
```bash
cp .env.example to .env

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

QUEUE_CONNECTION=database
```

Generate key for passport
```bash
php artisan passport:keys
```

Install and run project
```bash
composer install

php artisan migrate
php artisan passport:keys
php artisan queue:work
php artisan serve
```
