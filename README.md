# Pair boilerplate

Skeleton project for building applications with [Pair PHP Framework](https://github.com/viames/pair).

[![Latest Stable Version](https://poser.pugx.org/viames/pair_boilerplate/v/stable)](https://packagist.org/packages/viames/pair_boilerplate)
[![Total Downloads](https://poser.pugx.org/viames/pair_boilerplate/downloads)](https://packagist.org/packages/viames/pair_boilerplate)
[![Latest Unstable Version](https://poser.pugx.org/viames/pair_boilerplate/v/unstable)](https://packagist.org/packages/viames/pair_boilerplate)
[![License](https://poser.pugx.org/viames/pair_boilerplate/license)](https://packagist.org/packages/viames/pair_boilerplate)
[![composer.lock](https://poser.pugx.org/viames/pair_boilerplate/composerlock)](https://packagist.org/packages/viames/pair_boilerplate)

This repository provides a ready-to-install baseline for small and medium PHP applications such as CRM tools, internal portals and web back offices. It includes authentication, users and groups, ACL management, localization, a REST API entry point, OAuth2 support, migrations and a generator for CRUD modules.

The project targets the Pair 4 alpha development line. Bundled modules are aligned with the explicit Pair 4 web path based on `Pair\Web\Controller`, `PageResponse` and typed `*PageState` classes. Legacy implicit `View` variables are being replaced by explicit page state objects, and the `crafter` module generates Pair 4-style controller, state and layout files for new modules.

## Requirements

- PHP 8.4.1 or newer.
- MySQL 8.0 or newer.
- Apache with `mod_rewrite` enabled.
- Composer.
- PHP extensions:
  - `curl`
  - `fileinfo`
  - `intl`
  - `json`
  - `mbstring`
  - `pdo`
  - `pdo_mysql`

## Installation

Create a new project with Composer:

```shell
composer create-project viames/pair_boilerplate my_project_name
```

Open the project URL in the browser:

```text
http://localhost/my_project_name
```

The installer starts automatically when the root URL is opened for the first time. It checks the environment, creates or updates the database, imports the Pair 4 baseline schema and seed data, writes the `.env` file, creates the first administrator account and removes itself after a successful installation.

After installation, open the application URL and log in with the generated administrator credentials shown by the installer. Change the generated password after the first login.

## Apache Configuration

Enable Apache `mod_rewrite`:

```shell
sudo a2enmod rewrite
```

Make sure the project directory allows `.htaccess` rules. A typical configuration is:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride All
</Directory>
```

## MySQL Configuration

Pair applications should use `utf8mb4` and `utf8mb4_unicode_ci`. A typical MySQL configuration is:

```ini
[mysql]
default-character-set=utf8mb4

[mysqld]
collation-server = utf8mb4_unicode_ci
init-connect='SET NAMES utf8mb4'
character-set-server = utf8mb4
sql_mode = STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION
```

Verify that:

- the database uses charset `utf8mb4` and collation `utf8mb4_unicode_ci`;
- the database user can create, read, write, update and delete data in the project database;
- the database server time is correct, for example with `SELECT NOW();`.

## Configuration

Runtime configuration is stored in `.env`. Fresh installations generate it automatically from the installer. For existing installations, use `.env.example` as the reference structure.

Important Pair options include:

- `PAIR_AUDIT_ALL`
- `PAIR_SINGLE_SESSION`
- `PAIR_AUTH_BY_EMAIL`
- `PAIR_LOGGER_EMAIL_RECIPIENTS`
- `PAIR_LOGGER_EMAIL_THRESHOLD`
- `PAIR_LOGGER_TELEGRAM_CHAT_IDS`
- `PAIR_LOGGER_TELEGRAM_THRESHOLD`
- `PAIR_MOBILE_ACCESS_TOKEN_LIFETIME`
- `PAIR_MOBILE_REFRESH_TOKEN_LIFETIME`

The generated `.env` also contains cryptographic keys. Keep those values private and do not commit real production secrets.

## Updates and Migrations

Install or update dependencies from the project root:

```shell
composer install
composer update
```

Composer runs `php migrate-cli.php` after install and update. The migration runner applies Pair vendor migrations first and application migrations immediately after.

Application migrations live in `/migrations` and should use the `YYYYMMDD_description.sql` naming convention. Treat migration files as append-only after they have been applied to an environment.

When checking legacy code against the Pair 4 migration helper, run:

```shell
composer run upgrade-to-v4 -- --dry-run
```

The expected dry-run result for the current codebase is:

```text
Changed files: none
Warnings: none
```

## Development Commands

Run the test suite:

```shell
composer test
```

Run the Pair CLI wrapper:

```shell
composer run pair -- list
```

Generate a module:

```shell
composer run pair -- make:module orders
```

Generate a CRUD module from a database table:

```shell
composer run pair -- make:crud order --table=orders --fields=id,customer_id,total_amount
```

## Cron

Schedule `cronjob.php` every minute on Linux:

```cron
* * * * * /var/www/html/cronjob.php
```

Adjust the path to match the project location.

## Self-Test

After installation:

1. Log in as an administrator.
2. Open the `Self test` module.
3. Confirm that every check is green.
4. Fix every reported red `X` before treating the environment as ready.

## Folder Structure

```text
/classes
/installer
    /sql
        schema.sql
        seed.sql
/migrations
/modules
    /module1
        /assets
        /classes
        controller.php
        model.php
        /layouts
            default.php
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
.env
.env.example
.gitignore
.htaccess
composer.json
composer.lock
cronjob.php
migrate-cli.php
README.md
routes.php
```

The `/installer/sql` folder contains the fresh-install baseline split into schema and seed files. The `/migrations` folder contains append-only upgrade migrations.

## Architecture

Pair modules are organized around Controller, Model, state classes and layouts.

### Controller

Controllers should contain routing, access checks and request orchestration. Keep business rules, SQL details and presentation markup out of controllers.

In Pair 4-style modules, controllers return explicit responses such as `PageResponse` and pass typed state objects to layouts.

### Model

Models should contain business logic, queries and form handling that is shared by multiple pages of the same module. Keep request routing and presentation markup out of models.

### Page State and Layouts

Typed `*PageState` classes carry the data needed by a page. Layout files render HTML and should stay presentation-focused, with only simple conditions, loops and escaped output.

Do not add new legacy `View` classes for Pair 4 modules. Prefer typed state classes and explicit responses.

## Crafter Module

The `crafter` module scans database tables that are not yet associated with a module and generates Pair module code for CRUD workflows.

For best results:

- use InnoDB tables;
- define primary keys clearly;
- type every field correctly;
- define foreign key relationships where they express real data ownership or lookup behavior;
- review generated code before shipping it.

## Coding Conventions

Use CamelCase for PHP classes and variables:

```php
$mySpecialClass = new MySpecialClass();
```

Use uppercase constants separated by underscores:

```php
define('CUSTOMIZED_CONSTANT', TRUE);
```

Prefer clear multiline conditions:

```php
if (is_null($var)) {
    $var = 0;
} else {
    $var = 1;
}
```

Use short inline conditions only when readability stays high:

```php
$var = is_null($var) ? 0 : 1;
```

Use tabs for indentation in `.php` files, configured at 4 spaces visually.

In inline PHP templates, avoid abbreviated PHP tags and use `print` consistently with the existing codebase:

```php
<?php print $var ?>
```

Comment every PHP and JavaScript function. Add comments for non-trivial blocks when the intent is not immediately obvious:

```php
/**
 * Render the setup form with the current installer state.
 */
public function printSetupPage(): void {
    // ...
}
```

## Public Text

When adding user-facing HTML, wiki content, documentation, notifications or error messages, check grammar before finishing the change. For Italian text, verify accents and wording carefully.

## Contributing

Pull requests are welcome. Keep changes focused, include tests when behavior changes and update documentation when commands, requirements, API behavior or installation steps change.

## License

MIT
