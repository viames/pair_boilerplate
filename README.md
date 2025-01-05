# Pair boilerplate
a skeleton project to use [Pair PHP Framework](https://github.com/viames/pair) easily

[![Latest Stable Version](https://poser.pugx.org/viames/pair_boilerplate/v/stable)](https://packagist.org/packages/viames/pair_boilerplate)
[![Total Downloads](https://poser.pugx.org/viames/pair_boilerplate/downloads)](https://packagist.org/packages/viames/pair_boilerplate)
[![Latest Unstable Version](https://poser.pugx.org/viames/pair_boilerplate/v/unstable)](https://packagist.org/packages/viames/pair_boilerplate)
[![License](https://poser.pugx.org/viames/pair_boilerplate/license)](https://packagist.org/packages/viames/pair_boilerplate)
[![composer.lock](https://poser.pugx.org/viames/pair_boilerplate/composerlock)](https://packagist.org/packages/viames/pair_boilerplate)

This base project allows a fast start to develop small to medium PHP applications like CRM or web-portals.
With the addition of a few files more, here provided as sample, and an initial database structure, your web project will be up and running in a breeze.

### Features
This basic project manages users authentication, creates new custom ActiveRecord classes and [CRUD](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete) modules starting from a DB table by a magic module named `crafter`, all thru a friendly route logic.
Also it acts as REST API server with oAuth2 authentication support.

## System Setup

### Apache Configuration

* Activate Apache's `mod_rewrite` module (e.g. with the command `sudo a2enmod rewrite`) to ensure correct reading of virtual URL paths in web pages;

* Check Apache's `httpd.conf` file for the `AllowOverride` and `Options` directives for the project directory, for example:

```apache
<Directory /var/www/html>
	Options Indexes FollowSymLinks
	AllowOverride None
</Directory>
```

### PHP Configuration

* Make sure that at least `PHP v8.2` or higher is installed and active on the machine;

* Verify that the following PHP 8 extensions are configured:
- `fileinfo`
- `json`
- `pcre`
- `PDO`
- `pdo_mysql`
- `Reflection`

### MySQL Configuration

* Locate the MySQL `my.cnf` file, for example `/etc/mysql/my.cnf`, and set the following directives:

```ini
[mysql]
default-character-set=utf8mb4

[mysqld]
collation-server = utf8mb4_unicode_ci
init-connect='SET NAMES utf8mb4'
character-set-server = utf8mb4
sql_mode = STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION
```
* Verify that the product database is set to charset `utf8mb4` and collation `utf8mb4_unicode_ci`;

* Verify that the database connection user has read, write, modify and delete privileges on the product database;

* Verify the database time, for example with the command `SELECT NOW();` from the command line;

### Project configuration

* Run `git pull origin main` from the command line in the root of the project to download the latest stable version;

* Run `composer install` from the command line in the root of the project to install the dependencies packages;

* Schedule the execution with a one-minute period of the `/cronjob.php` script by activating Crontab on Linux. For example:

```bash
* * * * * /var/www/html/cronjob.php
```

* Manually copy from the existing installation, the files and folders present in the root of the project:

- `.env`
- `/temp`

* Assign write permissions to the `/temp` folder for the Apache user;

* Verify with self-test that everything is configured correctly:
- Connect to the system web page using the credentials of an admin user;
- Run the menu module `Self test`;
- If the result has all green checkmarks, the test is complete;
- If at least one red `X` appears, make the indicated corrections.

## Quick start

The [Installer](https://github.com/viames/pair_boilerplate/wiki/Installer) is used to configure the basic data of your web project. It starts automatically when the URL for the root folder is first launched, creates a custom [configuration](https://github.com/viames/pair/wiki/Configuration-file) file and auto-deletes after checking that everything is OK and that the installation was successful.

After the installation is completed, all the fields declared in the installer can subsequently be customized by modifying the `configuration` file except those related to the user created. For the latter, it is sufficient to modify it via the Users module.

### Create a new project

To start a web project with the Pair framework, this sequence is the suggested one:

#### 1. Install the pair_boilerplate

Use Composer to copy php files to a folder into your web server documents path:
```shell
composer create-project viames/pair_boilerplate my_project_name
```

#### 2. Run it by the browser

Launch the browser to the base address of the pair_boilerplate project to start the [Installer](https://github.com/viames/pair_boilerplate/wiki/Installer).
```shell
http://localhost/my_project_name
```
The project is ready to accept login and will let you manage users.

#### 3. Expand it according to your needs

Create additional InnoDB tables for database of your project and set the foreign keys properly, these are useful for getting the most out of [ActiveRecord](https://github.com/viames/pair/wiki/ActiveRecord). Then start the built-in [Crafter module](https://github.com/viames/pair_boilerplate/wiki/Developer), which will allow you to create a complete CRUD and class module for each of your custom tables.

#### 4. Customize the template

Your project deserves a unique aspect, so proceed to customize the existing template or install a new template plugin via the built-in `Template` module.

## Project Development

Modules and templates are built as plugins that can be installed and downloaded from the system in the form of a compressed ZIP file.

### Folder Structure

The project folder structure follows the following tree:

```bash
/classes
/migrations
/modules
    /module1
        /assets
        /classes
        controller.php
        model.php
        view1.php
        view2.php
        /layouts
            /layout1.php
            /layout2.php
/public
    /assets
    /css
    /img
    /js
    /plugins
    .htaccess
    index.php
/temp
/templates
    /template1
        default.php
        login.php
        404.php
/translations
    en-GB.ini
    it-IT.ini
/vendor
/widgets
.gitignore
.htaccess
composer.json
composer.lock
.env
cronjob.php
README.md
routes.php
```

### MVC Pattern

Each module in the `/modules` folder contains the three elements of the MVC pattern: `Controller`, `Model` and `View`.

#### Controller

The Controller must contain only the routing and HTTP request management logic, so it must be as free as possible from business and presentation logic.

Access controls must be performed in this layer.

The file name is located in each module folder and is always called `controller.php`.

#### Model

The Model must contain all the business logic, so it must be as free as possible from routing and presentation logic.

Forms common to multiple pages of the module can be managed in this layer.

The file name is located in each module folder and is always called `model.php`.

#### View

The View must contain the presentation logic, so it must be as free as possible from routing and business logic. The layout file in the `layouts` folder must contain the HTML code and the minimum instructions for loops and variable output.

Each module usually contains more than one view, each corresponding to a `View` file. The name of each View file, in CamelCase with the first lowercase, contains the second part of the class name, excluding the prefix with the module name.

An example:

```php
// file name viewLogin.php
class UserViewLogin extends View {
// ...
}
```

### `crafter` module

For the simplified development of a basic module with all CRUD functions, the `crafter` module is available. This module scans the database for tables not yet associated with a module and automatically generates the code to manage that table.

For the module to work effectively and for CRUD data to be well-structured, it is necessary to perfectly type the fields of the object table and connect it to the other tables via foreign key relationships.

### `/classes` folder

This is a folder located in the base folder of the project and contains all the classes and interfaces common to multiple modules. Classes used only in one module can be placed in the `classes` folder inside a module, for example `/users/classes/MySpecialClass.php`.

The class name must be in CamelCase and must be the same as the file name.

Each file inside classes contains one and only one class.

These classes can be inherited from `ActiveRecord` or other classes.

Interface files must have the suffix `Interface`, for example `MyInterface.php` and can contain multiple interfaces each.

### Variables

Variable names must describe their content, for example `$mySpecialClass`, which underlies a `MySpecialClass` object.
Variables must be declared in CamelCase, for example `$mySpecialClass`.

### Constants

Constants must have a name that begins with a prefix that describes their scope of use, therefore their content, for example `CUSTOMIZED_CONSTANT`.

The name must be all uppercase, separated by an underscore `_`.

PHP default constants, such as `NULL`, `FALSE` and `TRUE`, must be written in uppercase.

```php
define ('CUSTOMIZED_CONSTANT', TRUE);
```

### Code formatting

These indications are optional, but they help to keep the project with a common style, therefore more understandable by everyone.

#### Conditions and loops

```php
// preferable
if (is_null($var)) {
    $var = 0;
} else {
    $var = 1;
}

// only for simple conditions
if (is_null($var)) $var = 0;
else if ($var > 3) $var = 3;
else $var = 1;

// simple conditions with ternary operator
$var = is_null($var) ? 0 : 1;

$result = is_a($mySpecialClass, 'MySpecialClass') ?
? $var->myFunction()
: MySpecialClass::getInstance()->myFunction();
```

#### Indentation

Always and only use the tab with a size of 4 spaces each for cpon files with the .php extension.

#### Inline code

In inline instructions, avoid PHP abbreviated tags. For consistency with pre-existing code, use `print` instead of `echo`.

```php
// preferable
<?php print $var ?>

// avoid
<? print $var; ?>
```

#### Comments

```php
// one-line comment, lowercase

/**
* Function comment, written as a paragraph of text, with punctuation.
* Does not include description of parameters and return value
* since it is already declared in the code.
*/
```

## Contributing

If you would like to contribute to this project, please feel free to submit a pull request.

### License

MIT
