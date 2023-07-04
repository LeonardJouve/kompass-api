php.ini: 
- Set extension dir: extension_dir = "ext"
- The following php extensions are required:
    - curl
    - fileinfo
    - mbstring
    - openssl
    - pdo_mysql
- composer update
- .env:
    - DB_CONNECTION=mysql
    - DB_HOST=127.0.0.1
    - DB_PORT=3306
    - DB_DATABASE=kompass
    - DB_USERNAME=
    - DB_PASSWORD=

php artisan storage:link

php artisan db:seed --class=AvailableItemSeeder

php artisan db:seed --class=DropSeeder
