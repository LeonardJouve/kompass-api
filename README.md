php.ini: 
- enable these extensions:
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_mysql
- set extension dir:
extension_dir = "ext"
composer update
.env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kompass
DB_USERNAME=
DB_PASSWORD=

php artisan storage:link
php artisan db:seed --class=AvailableItemSeeder
php artisan db:seed --class=DropSeeder
