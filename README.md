# Pair example
a skeleton project to use [Pair PHP Framework](https://github.com/Viames/Pair) easily

[![Latest Stable Version](https://poser.pugx.org/viames/pair_example/v/stable)](https://packagist.org/packages/viames/pair_example)
[![Total Downloads](https://poser.pugx.org/viames/pair_example/downloads)](https://packagist.org/packages/viames/pair_example)
[![Latest Unstable Version](https://poser.pugx.org/viames/pair_example/v/unstable)](https://packagist.org/packages/viames/pair_example)
[![License](https://poser.pugx.org/viames/pair_example/license)](https://packagist.org/packages/viames/pair_example)
[![composer.lock](https://poser.pugx.org/viames/pair_example/composerlock)](https://packagist.org/packages/viames/pair_example)

This base project allows a fast start to develop small to medium PHP applications like CRM or web-portals.
With the addition of a few files more, here provided as sample, and an initial database structure, your web project will be up and running in a breeze.

## Features
This basic project manages users authentication, creates new custom ActiveRecord classes and [CRUD](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete) modules starting from a DB table by a magic module named `developer`, all thru a friendly route logic.
Also it acts as REST API server.

## Quick start

The [Installer](https://github.com/Viames/Pair_example/wiki/Installer) is used to configure the basic data of your web project. It starts automatically when the URL for the root folder is first launched, creates a custom [configuration](https://github.com/Viames/Pair/wiki/Configuration-file) file and auto-deletes after checking that everything is OK and that the installation was successful.

After the installation is completed, all the fields declared in the installer can subsequently be customized by modifying the `configuration` file except those related to the user created. For the latter, it is sufficient to modify it via the Users module.

## Create a new project

To start a web project with the Pair framework, this sequence is the suggested one:

#### 1. Install Pair_example

Use Composer to copy php files to a folder into your web server documents path:
```shell
composer create-project viames/pair_example my_project_name
```

#### 2. Run it by the browser

Launch the browser to the base address of the Pair_example project to start the [Installer](https://github.com/Viames/Pair_example/wiki/Installer).
```shell
http://localhost/my_project_name
```
The project is ready to accept login and will let you manage users.

#### 3. Expand it according to your needs

Create additional InnoDB tables for database of your project and set the foreign keys properly, these are useful for getting the most out of [ActiveRecord](https://github.com/Viames/Pair/wiki/ActiveRecord). Then start the built-in [Developer module](https://github.com/Viames/Pair_example/wiki/Developer), which will allow you to create a complete CRUD and class module for each of your custom tables.

#### 4. Customize the template

Your project deserves a unique aspect, so proceed to customize the existing template or install a new template plugin via the built-in `Template` module.

---

### Contributing

If you would like to contribute to this project, please feel free to submit a pull request.

### License

MIT
