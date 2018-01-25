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

### Installation

Choose a name for your new project and create a new folder where to install Pair_example:

```bash
$ mkdir project_name
$ cd project_name
```
Now you can create a new project [manually](#manual-installation) or by using [Composer](#composer), that allows to easily update Pair framework.

#### <a name="manual-installation">Manual installation</a>

1. Please download the [Pair_example](https://github.com/Viames/Pair_example/releases)’s latest available release;
2. Unzip the content into your project folder;
3. Run your browser at web-server root and add the sub-url as configured in `BASE_URI` constant of config.php.

#### <a name="composer">Composer</a>

You can ask Composer to create the project into your new folder. If you don’t have Composer installed in your system, please [download it](https://getcomposer.org/).

Launch a command-line terminal and start a new composer project:

```bash
$ composer create-project viames/pair_example
```

If you want to test code that is in the master branch, which hasn’t been pushed as a release, you can use master:

```bash
$ composer create-project viames/pair_example dev-master
```

### Configuration

When launched, the Pair framework will check `config.php` file and, because it isn’t bundled, will starts the web installer interface. Please fill in all required data and in a second your Pair application will be up and running.

In case you written something wrong in the config.php file, you can edit it manually. This is the file content

```PHP
<?php

// product
define ('PRODUCT_VERSION', '1.0');
define ('PRODUCT_NAME', 'Your application name');
define ('BASE_URI', '/any_subpath_on_url');

// database
define ('DB_HOST', 'your_host');
define ('DB_NAME', 'your_name');
define ('DB_USER', 'your_user');
define ('DB_PASS', 'your_pass');
```

By creating different versions of this file you can move the project on other web-servers. This is useful to run a development version, a test version and a production version of the same project.

Now it’s time to login using the account that have your email address as username and a strong 15 chars random password that’s shown at the installation end.

---

### Contributing

If you would like to contribute to this project, please feel free to submit a pull request.

### License

MIT
