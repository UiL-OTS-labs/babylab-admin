# Babylab Admin

Babylab administration and data warehouse system, written in CodeIgniter.

## Introduction
This CI project is a custom-tailored portal used to keep track of participants, experiments and appointments for the 
babylab, part of the UiL OTS Labs.

## Requirements

- PHP 7.1+ 
- PHP PDO (mysql) extension
- Apache 2
- MySQL(-like) database

This application integrates with a LimeSurvey installation, but this functionality is disabled by default.
If you wish to use this, you have to supply a LimeSurvey 1.x installation. (LimeSurvey 2 has not been tested)

## Installation

Installation is quite straightforward, but needs a functional Apache 2 environment. On request, a puppet module can be 
provided for automated deployments.

### Setting up your code
- Clone this repo to the root of the webserver folder*
- Edit `config.php` (in application/config) to reflect your setup
- Create a file in application/config called `email.php` based upon the `email_default.php` in the same folder. Edit 
this file for your own setup.
- Repeat the last step for `database.php`

If you already have a database set up and up-to-date, you should be ready to go. If you do not, follow the instructions below.

### Setting up your database
- Import `babylab_schema_20140722.sql` into your database. (Skip this if you only need to update your database).
- Enable the migrate controller by commenting out `show_error` in `application/controllers/migrate.php`.
- Open your browser, and navigate to `$host/index.php/migrate/install`
  - You might need to temporarily disable the session library. You can do this by removing `session` from 
`application/config/autoload.php`. Remember to re-enable this library when you're done.
  - If you still encounter errors, try to manually install each migration on it's own by navigating to 
`$host/index.php/migrate/version/$id`
- When you're done, git-revert `migrate.php` to disable migrations (and to make sure you don't commit the enabled file ;-) )

### Adding a user account
Go to your database, and add a user entry to the users table. The password needs to be hashed with BCrypt. You can 
generate one on a command line with the following command:

`php -r 'echo password_hash("PASSWORD HERE", PASSWORD_BCRYPT);'`

### Enabling limesurvey
- Open `database.php`
- Make a new database entry (you can copy paste the `$db['default']` database), and call it `survey`.
- Fill in your limesurvey database details.
- Fill in the following constants defined in `database.php`:
  - LS_BASEURL: This is the place where you would find the index page for your limesurvey install
  - SURVEY_DEV_MODE: Set this to false. 

## Language

The main language of this web application is Dutch, as it's aimed towards the mostly avid Dutch-speaking researchers of Utrecht University.
However, there is a full English translation available.

Translations in other languages are possible, of course. Simply make a new folder under `application/language` for the
desired language with the translated files in it. 

## 