This is a home project based on PHP framework Symfony 4.x
It allows user to store and control expenses.
Project is currently in build phase.

How to install and use this project?
1) clone this project (https://github.com/maciej-sidorczuk/expenses.git) to your computer using git 
2) create user and database on your db server
3) create empty .env file in project folder
4) edit .env file and add this line:
DATABASE_URL=mysql://databaseuser:password@host:port/databasename
where databaseuser is your database user
password is a password for above user
host is a address to your database server, optionally you can provide port
databasename is your database name
5) install composer in your system if you don't have
6) go to main folder of project and use this command: composer require symfony/dotenv
7) then use this command: composer install --optimize-autoloader
8) clean cache using this command: php bin/console cache:clear
9) create database schema using commands:
php bin/console make:migration
php bin/console doctrine:migrations:migrate

now you can access the project from browser
