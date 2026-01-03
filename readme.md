herd  as server
npm 
PHP 8.3
npm 10 and owanrds
composer
SQLite

1 After install the code run:
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan optimize:clear
npm install
npm run build


2 After wanted to make some configuration 
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
php artisan tinker
DB::connection()->getDatabaseName();

to setup admin email =  ...url/setup-admin
example:
https://taskmanagementcodessd1.test/setup-admin

note:
make sure connection is https, if use herd as webserver go to site see icon key security and press 
