1.Project description

The Task Management System is a web application developed using Laravel 12. It allows users to create, view, update, and delete tasks efficiently. Users can manage their tasks in an organized way, set priorities, and track progress.
The system includes user authentication, ensuring that each user can only access their own tasks. It also implements security features such as input validation, CSRF protection, and role-based access control to prevent unauthorized actions.
Overall, the system helps users stay organized, improve productivity, and manage tasks easily.

2.Dependencies

The following dependencies are required to run the system:

Laravel 12

PHP 8.3

Composer

Node.js (npm 10 and above)

SQLite

Herd (used as the web server)

3.Installation Steps.

1.After clone the code run this command:

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan optimize:clear

npm install

npm run build

4.Additional Configuration.

After completing the installation, run the following commands to clear caches and ensure proper configuration:

2.After run the command next need to make some configuration:

php artisan config:clear

php artisan cache:clear

php artisan route:clear

php artisan view:clear

php artisan optimize:clear

#check the connection to database

php artisan tinker

DB::connection()->getDatabaseName();

5.Admin Setup.

To set up the admin account, access the following URL in your browser:

Next, to setup admin email =  ...url/setup-admin

example:
https://taskmanagementcodessd1.test/setup-admin

note:
make sure connection is https, if use herd as webserver go to site see icon key security and press. 
