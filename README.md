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
    - OPEN_TRIP_MAP_API_KEY=

php artisan db:seed
