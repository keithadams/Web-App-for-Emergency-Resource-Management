# Team 072 - Emergency Resource Management System

## Notes:

Remember to run `composer update` after pulling down new changes in case anyone integrates any additional PHP packages in.

Due to the new addition of forced logins (automatic redirection to the /login route if you are not authenticated) you will need to login as either yourself or one of the other users in our sample data. Each user's password is simply `password`.

## Getting Started:

* Within the code/config directory is a `config.dist.php` file. Please copy and rename to `config.php` and then edit with your MySQL database name and other related info.
* Once that initial step is complete, you can run the installation process by using the `/install` route (e.g. if your project exists in `http://localhost/cs6400` then you would use `http://localhost/cs6400/install`). Alternatively, we have an extended set of sample data that was used for testing that could be loaded with `http://localhost/cs6400/install?testing`. Keep in mind that these URLs are just for guidance and will depend on the folder name used for this project on your computer.
* After the tables, stored procedures, and sample data have been installed you can login to the system (you can use one of our usernames as a starting point if using the regular sample data, or take a quick look at your `users` table to find a user to test. Each user has their password set to `password` currently.

#### Attributions:

We used the following packages/resources for our project:

* SB Admin 2 Bootstrap Template - https://startbootstrap.com/template-overviews/sb-admin-2/
* Slim 3 Framework - http://www.slimframework.com/
* Slim 3 PHP View Package - https://github.com/slimphp/PHP-View
* Monolog Package - https://github.com/Seldaek/monolog
* PSR7 Middlewares - https://github.com/oscarotero/psr7-middlewares
* Respect Validation Package - https://github.com/Respect/Validation
* Aura Session Package - https://github.com/auraphp/Aura.Session
* Carbon Date Formatting Package - https://github.com/briannesbitt/Carbon
* Free Siren Icon - http://www.flaticon.com/free-icon/siren_139775 (Made by http://www.flaticon.com/authors/madebyoliver and found on http://www.flaticon.com licensed as CC 3.0 BY: http://creativecommons.org/licenses/by/3.0/)
