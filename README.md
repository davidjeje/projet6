 Symfony

Definition:

Symfony is a set of PHP components and a free MVC framework written in PHP. It provides flexible and adaptable features that facilitate and accelerate the development of a website.


Requirements :

-Git
-Local server with PHP
- PHP 7.2 or Higher
- Composer
- Symfony


Download composer:

Normally, PHP 7.2 or the latest version of PHP must already be installed on our working environment, as this is a prerequisite for installing composer and symfony.

Here are the commands to install Composer on our computer. We can install it the same way, whatever our operating system, as soon as PHP is installed:

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
Then quickly check that PHP and Composer are available in your command prompt:
➜ php -v
➜ composer –version

Both of these commands must return a result. 

Installing the Symfony framework
Since version 4 of the Symfony framework, there is no longer any official distribution.
The vision of the symfony team is to provide lightweight application skeletons, and to let developers decide which dependencies are needed in their applications.
Officially, there are two application skeletons:
skeleton: the minimalist skeleton to start a PHP project, it is recommended for command line applications, for example.
Website-skeleton: the recommended skeleton for making web projects, which is recommended as a basis for work.

There is a demonstration project called Symfony Demonstration. In this project, we find some controllers, some integrated views with Bootstrap, some forms and a minimalist administration space already in place.
Although this is not recommended by the Symfony project, in practice many developers use this learning project as a basis for their business projects.
In the case where we do not create a new project but add my project, here is the command with the link github:
 
git clone  https://github.com/davidjeje/projet6


Before Starting the application 
For the application to access the database:
It will be necessary to parameterize the file .env which is at the root of the project. Then if you have a local server on your computer you can connect to the database of your choice. Example with mysql who is relational database management system :
DATABASE_URL=mysql://root:@127.0.0.1:3306/projet6
if not you can use SQLite which is a database system that does not require a client-server architecture. It will be necessary to check that the PHP SQLite driver (php-sqlite3) is installed and activated. We will then modify the DATABASE_URL environment variable as follows:
Example:
DATABASE_URL=sqlite:///%kernel.project_dir%/var/jobeet.db 
Once your fixtures are written, load them by executing this command:
 php bin/console doctrine:fixtures:load

Starting the application 
If in production we use a web server like Apache or Nginx, in development, we can use the PHP local server. For this, the framework provides a dedicated console:

 ➜ cd mon-super-projet
 ➜ php bin/console server:run

If the port is not busy, the application will be available at this address: 
 http://localhost:8000/ .
To stop this local server, use the command  Ctrl + C  in your command prompt.

Symfony Framework Home Page By default, since there is no controller or configured route, the framework presents us with a page with a link to the official documentation. 




