# My Blog
This is a decoupled blogging application build using Symfony framework and ReactJS.

# Local setup

## Prerequisite
* Docker
* Lando
* Node js 16.13 or higher
* PHP 8.0 or higher
* Composer 2.1

# Steps
* Run `lando start`
* Optional - Run `composer install` (This also runs as part on lando start)
* Install node modules `npm ci` or `npm install`
* Build frontend assets `npm run build`
* Ssh into container using `lando ssh`
* Run migrations `./bin/console doctrine:migrations:list`
* Install assets (like ckeditor) using `./bin/console assests:install`
