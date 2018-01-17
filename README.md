# Pair example
a skeleton project to use Pair PHP Framework easily

[![Latest Stable Version](https://poser.pugx.org/viames/pair_example/v/stable)](https://packagist.org/packages/viames/pair_example)
[![Latest Unstable Version](https://poser.pugx.org/viames/pair_example/v/unstable)](https://packagist.org/packages/viames/pair_example)
[![License](https://poser.pugx.org/viames/pair_example/license)](https://packagist.org/packages/viames/pair_example)
[![composer.lock](https://poser.pugx.org/viames/pair_example/composerlock)](https://packagist.org/packages/viames/pair_example)

This base project allows a fast start to develop small to medium PHP applications like CRM or web-portals.
With the addition of a few files more, here provided as sample, and an initial database structure, your web project will be up and running in a breeze.

## Features
This basic project manages users, creates classes and modules by DB tables automatically, all thru a friendly route logic.
Also it acts as REST API server.

## Installation

Choose a name for your new project and create a new folder where to install Pair_example:

```bash
$ mkdir project_name
$ cd project_name
```
Now you can create a new project [manually](#manual-installation) or by using [Composer](#composer).

### <a name="manual-installation">Manual installation</a>

1. Please download the [Pair_example](https://github.com/Viames/Pair_example/releases)’s latest available release;
2. Unzip the content into your project folder;
3. Run your browser at web-server root and add the sub-url as configured in `BASE_URI` constant of config.php.

### <a name="composer">Composer</a>

You can ask Composer to create the project into your new folder.
Launch a command-line terminal and start a new composer project:

```bash
$ composer create-project viames/pair_example
```

If you want to test code that is in the master branch, which hasn’t been pushed as a release, you can use master.

```bash
$ composer create-project viames/pair_example dev-master
```

If you don’t have composer installed, you can download it here: [https://getcomposer.org](https://getcomposer.org/)

## Configuration

Please open the config.php file in the root of the project, you will see a bunch of lines that can be customized according to your installation.

```PHP
<?php

// product
define ('PRODUCT_VERSION', '1.0');
define ('PRODUCT_NAME', 'Pair example');
define ('BASE_URI', '/pair_example'); // application URL subpath

// database
define('DB_HOST', 'db_host');
define('DB_NAME', 'db_name');
define('DB_USER', 'db_user');
define('DB_PASS', 'db_pass');
```

By creating different versions of this file you can move the project on different web-servers. This is useful for running a development version, a test version and a production version of the same project.

## Contributing

If you would like to contribute to this project, please feel free to submit a pull request.

# License

MIT
