Project description
The Task Management System is a web application developed using Laravel 12. It allows users to create, view, update, and delete tasks efficiently. Users can manage their tasks in an organized way, set priorities, and track progress.

The system includes user authentication, ensuring that each user can only access their own tasks. It also implements security features such as input validation, CSRF protection, and role-based access control to prevent unauthorized actions.

Overall, the system helps users stay organized, improve productivity, and manage tasks easily.

Dependencies:

use herd  as a web server

Use laravel 12

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
