<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

class Installer {
	
	/**
	 * Absolute path to project root.
	 * @var string
	 */
	private $rootFolder;
	
	/**
	 * Subfolder of this project from web-server root.
	 * @var string
	 */
	private $baseUri;
	
	/**
	 * DB handler.
	 * @var PDO
	 */
	private $dbh;
	
	/**
	 * Flag to set DB_UTF8 constant.
	 * @var bool
	 */
	private $forceDbUtf8 = FALSE;
	
	/**
	 * List of installer notifications.
	 * @var array
	 */
	private $notifications = [];
	
	/**
	 * List of installer errors.
	 * @var array
	 */
	private $errors = [];
	
	public function __construct() {
		
		// the app root
		$this->rootFolder = dirname(dirname(__FILE__));
		
		// get the subpath of this project in web-server root
		$this->baseUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		
		// remove trailing slash
		if (substr($this->baseUri, strlen($this->baseUri)-1, 1) == '/') {
			$this->baseUri = substr($this->baseUri,0, -1);
		}
		
	}
	
	public function addNotification($text) {
		
		$this->notifications[] = $text;
		
	}
	
	public function getNotifications() {
		
		return $this->notifications;
		
	}
	
	public function addError($text) {
		
		$this->errors[] = $text;
		
	}

	public function getErrors() {
		
		return $this->errors;
		
	}
	
	public function checkFoldersErrors() {
	
		// check root folder writable
		if (!is_writable($this->rootFolder)) {
			$this->addError('The folder ' . $this->rootFolder . ' is not writable. Please grant ' .
					'write permission to web-server running this application.');
		}
	
	}
	
	public function checkApacheErrors() {
		
		// test apache
		if (function_exists('apache_get_modules')) {
			$aModules = \apache_get_modules();
			if (!in_array('mod_rewrite', $aModules)) {
				$this->addError('Apache mod_rewrite is not loaded.');
			}
		}
		
		// htaccess
		if (!file_exists($this->rootFolder . '/.htaccess')) {
			$this->addError('Apache .htaccess file not found in application root folder.');
		}
		
	}
	
	public function checkPhpErrors($requiredVersion, $extensions) {
		
		// check PHP extensions
		foreach ($extensions as $ext) {
			if (!extension_loaded($ext)) {
				$this->addError('Missing PHP extension ' . $ext);
			}
		}
		
		// check PHP version
		if (version_compare(phpversion(), $requiredVersion, "<")) {
			$this->addError('PHP version required is ' . $requiredVersion . ' or greater. This web-server is running PHP ' . phpversion());
		}
		
	}
	
	public function checkRequiredFields() {
	
		// list of required field names
		$requiredFields = ['productName', 'productVersion', 'dbHost', 'dbName',
				'dbUser', 'dbPass', 'name', 'surname', 'email'];
		
		// check that all fields are submitted
		foreach ($requiredFields as $f) {
			if (!isset($_POST[$f]) or ''==trim($_POST[$f])) {
				$this->addError('A valid value for ' . $f . ' is required');
			}
		}
		
	}
	
	public function connectToDbms() {
		
		$v = $this->getPostVars();
		
		try {
			$this->dbh = new \PDO('mysql:host=' . $v['dbHost'], $v['dbUser'], $v['dbPass']);
			if (!is_a($this->dbh, 'PDO')) {
				throw new \PDOException('DB-handler is not valid, connection failed');
			}
		} catch (Exception $e) {
			$this->addError('MySQL connection failed: ' . $e->getMessage());
			return FALSE;
		}
		
		return TRUE;
		
	}
	
	public function checkDbmsVersion($requiredVersion) {
		
		// check MySQL version
		$sth = $this->dbh->prepare('SELECT VERSION()');
		$sth->execute();
		$version = $sth->fetch(\PDO::FETCH_COLUMN);	

		if (version_compare($version, $requiredVersion) < 0) {
			$this->addError('MySQL version required is ' . $requiredVersion . ' or greater. You are using MySQL ' . $version);
		}
		
	}
	
	public function checkDbUtf8() {
		
		// check about charsets and collations
		$settings = array(
				'character_set_client'		=> 'utf8mb4',
				'character_set_connection'	=> 'utf8mb4',
				'character_set_database'	=> 'utf8mb4',
				'character_set_results'		=> 'utf8mb4',
				'character_set_server'		=> 'utf8mb4',
				'collation_connection'		=> 'utf8mb4_unicode_ci',
				'collation_database'		=> 'utf8mb4_unicode_ci',
				'collation_server'			=> 'utf8mb4_unicode_ci');
		
		// ask to dbms the current settings
		$sth = $this->dbh->prepare('SHOW VARIABLES WHERE Variable_name LIKE \'character\_set\_%\' OR Variable_name LIKE \'collation%\'');
		$sth->execute();
		$list = $sth->fetchAll(\PDO::FETCH_OBJ);
		
		// compare settings
		foreach ($list as $row) {
			if (array_key_exists($row->Variable_name, $settings)) {
				if ($settings[$row->Variable_name] != $row->Value) {
					$this->addNotification('Charset and collation will be forced to utf8mb4');
					$this->forceDbUtf8 = TRUE;
				}
			}
		}
		
	}
	
	public function createDb() {
		
		// get the variables by http post
		$v = $this->getPostVars();
		
		// check if db exists
		try {
			$sth = $this->dbh->prepare('SELECT * FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?');
			$sth->execute([$v['dbName']]);
			$db = $sth->fetch(\PDO::FETCH_OBJ);
		} catch (Exception $e) {
			$this->addError('Cannot check if DB exists by information schema: ' . $e->getMessage());
			return FALSE;
		}
		
		// not exists, create a new database
		if (FALSE === $db) {
			
			$query = 'CREATE DATABASE `' . $v['dbName'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			
			try {
				if (!$this->dbh->exec($query)) {
					throw new \PDOException($this->dbh->errorInfo()[2]);
				}
				$this->addNotification('New database `' . $v['dbName'] . '` has been created');
			} catch (Exception $e) {
				$this->addError('DB `' . $v['dbName'] . '` creation failed: ' . $e->getMessage());
				return FALSE;
			}
			
		// db exists, check if charset and collation are utf8mb4
		} else if ('utf8mb4'!=$db->DEFAULT_CHARACTER_SET_NAME or 'utf8mb4_unicode_ci'!=$db->DEFAULT_COLLATION_NAME) {
			
			$query = 'ALTER DATABASE `' . $v['dbName'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			
			try {
				if (!$this->dbh->exec($query)) {
					throw new \PDOException($this->dbh->errorInfo()[2]);
				}
				$this->addNotification('Existing database ' . $v['dbName'] . ' has been converted to charset and collation utf8mb4');
			} catch (Exception $e) {
				$this->addError('Change of DB charset and collation failed: ' . $e->getMessage());
				return FALSE;
			}
			
		}
		
		// load Pair’s DB dump by SQL file
		$queries = file_get_contents($this->rootFolder . '/installer/dump.sql');
		
		// create all tables, if not exist
		try {
			$this->dbh->exec('USE `' . $v['dbName'] . '`');
			$this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 1);
			$this->dbh->exec($queries);
		} catch (Exception $e) {
			$this->addError('Table creation failed: ' . $e->getMessage());
			return FALSE;
		}
		
	}
	
	/**
	 * Create and insert a new application user with random password.
	 */
	public function createUser() {
		
		// get the variables by http post
		$v = $this->getPostVars();
		
		// check if user exists
		try {
			$sth = $this->dbh->prepare('SELECT COUNT(1) FROM `users` WHERE username = ?');
			$sth->execute([$v['email']]);
			$exists = (bool)$sth->fetchColumn();
		} catch (Exception $e) {
			$this->addError('Error while checking if username is already in use: ' . $e->getMessage());
			$exists = FALSE;
		}
		
		if ($exists) {
			$this->addError('Cannot create a user with username ' . $v['email'] . ' as it already exists');
			return;
		}

		// random password
		$password = '';
		
		// list all chars/numbers
		$chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
		
		$charsCount = count($chars);
		
		// fill in each char of the random password
		for ($i=1; $i <= 15; $i++) {
			if (0 == $i%4) {
				$password .= '-';
				continue;
			}
			$password .= $chars[mt_rand(0, $charsCount-1)];
		}
		
		// salt for bcrypt needs to be 22 base64 characters (only [./0-9A-Za-z])
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		
		// 2a = bcrypt algorithm selector, 12 = the workload factor
		$hash = crypt($password, '$2a$12$' . $salt);

		// assemble the query
		$query =
			'INSERT INTO `users` (`group_id`, `language_id`, `ldap_user`, `username`, `hash`, `name`,' .
			'`surname`, `email`, `admin`, `enabled`, `last_login`, `faults`) VALUES ' .
			'(1, 1, NULL, ?, ?, ?, ?, ?, 1, 1, NULL, 0)';
		
		// query parameters
		$params = [$v['email'], $hash, $v['name'], $v['surname'], $v['email']];
		
		// do the query and log
		try {
			$sth = $this->dbh->prepare($query);
			if (!$sth->execute($params)) {
				throw new \PDOException($sth->errorInfo()[2]);
			}
			$this->addNotification('Created a new user, please keep note.<br>Username: ' . $v['email'] . '<br>Password: ' . $password);
		} catch (Exception $e) {
			$this->addError('User creation failed: ' . $e->getMessage());
		}
		
	}
	
	public function printNotifications() {
		
		if (count($this->notifications)) {
			
			foreach ($this->notifications as $p) {
				?><div class="alert alert-info" role="alert"><?php print $p ?></div><?php
			}
		    ?><hr class="mb-4"><?php
		    
		}
	          
	}
	
	public function printErrors() {
		
		if (count($this->errors)) {
			
			foreach ($this->errors as $p) {
				?><div class="alert alert-danger" role="alert"><?php print $p ?></div><?php
			}
		    ?><hr class="mb-4"><?php
		    
		}
	          
	}
	
	public function printSetupPage() {
		
		$v = $this->getPostVars();
		
		if (!$v['productVersion']) {
			$v['productVersion'] = '1.0';
		}

		if (!$v['baseUri']) {
			$v['baseUri'] = $this->baseUri;
		}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Pair example installer</title>
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	    <style>
			body {
				background: #005AA7;
				background: -webkit-linear-gradient(to bottom, #005AA7, #EBF9F9);
				background: linear-gradient(to bottom, #005AA7, #EBF9F9);
				color: white; 
			}
			.text-muted { color: white !important; opacity: .5; }
			footer { color: #777; }
			hr { border-top: 1px solid rgba(255,255,255,.2); }
		</style>
	</head>
 	<body>
	    <div class="container">
	      <div class="py-5 text-center">
	        <img class="d-block mx-auto mb-4" src="installer/pair-logo.png" alt="" width="160" height="89">
	        <h2>Example installer</h2>
	        <p class="lead">Please fill-in all required data about your new product based on Pair PHP framework</p>
	      </div>
	      <div class="row">
	      	<div class="col-md-2 order-md-1"></div>
	        <div class="col-md-8 order-md-2">
	        	<?php $this->printErrors() ?>
	        	<?php $this->printNotifications() ?>
	          <form action="<?php print $this->baseUri ?>/" method="post">

	            <div class="row">
	              <div class="col-md-9 mb-3">
	                <label for="productName">Product name</label>
	                <input type="text" class="form-control" name="productName" id="productName" value="<?php print htmlspecialchars($v['productName']) ?>" required>
	                <div class="invalid-feedback">
	                  Valid product name is required.
	                </div>
	              </div>
	              <div class="col-md-3 mb-3">
	                <label for="productVersion">Version</label>
	                <input type="text" class="form-control" name="productVersion" id="productVersion" value="<?php print htmlspecialchars($v['productVersion']) ?>" required>
	                <div class="invalid-feedback">
	                  Valid product version is required.
	                </div>
	              </div>
	            </div>

	            <div class="mb-3">
	                <label for="baseUri">URL folder <small class="text-muted">/subpath or empty if installing in web-server root</small></label>
	                <input type="text" class="form-control" name="baseUri" id="baseUri" value="<?php print htmlspecialchars($this->baseUri) ?>">
	            </div>
	            
				<h4 class="mb-3 mt-5">Database</h4>

				<div class="row">
	            	<div class="col-md-6 mb-3">
		                <label for="dbHost">Host <small class="text-muted">name or IP address</small></label>
		                <input type="text" class="form-control" name="dbHost" id="dbHost" value="<?php print htmlspecialchars($v['dbHost']) ?>" required>
		            </div>
	
	            	<div class="col-md-6 mb-3">
		                <label for="dbName">Name <small class="text-muted">create if not exists</small></label>
		                <input type="text" class="form-control" name="dbName" id="dbName" value="<?php print htmlspecialchars($v['dbName']) ?>" required>
		            </div>
		        </div>

				<div class="row">
	            	<div class="col-md-6 mb-3">
		                <label for="dbUser">User</label>
		                <input type="text" class="form-control" name="dbUser" id="dbUser" value="<?php print htmlspecialchars($v['dbUser']) ?>" required>
		            </div>
	
	            	<div class="col-md-6 mb-3">
		                <label for="dbPass">Password</label>
		                <input type="password" class="form-control" name="dbPass" id="dbPass" value="<?php print htmlspecialchars($v['dbPass']) ?>" required>
		            </div>
		        </div>

				<h4 class="mb-3 mt-5">Administrator account</h4>

				<div class="row">
	            	<div class="col-md-6 mb-3">
		                <label for="name">Name</label>
		                <input type="text" class="form-control" name="name" id="name" value="<?php print htmlspecialchars($v['name']) ?>" required>
		              <div class="invalid-feedback">
		                Please enter a valid name.
		              </div>
		            </div>
	
	            	<div class="col-md-6 mb-3">
		                <label for="surname">Surname</label>
		                <input type="text" class="form-control" name="surname" id="surname" value="<?php print htmlspecialchars($v['surname']) ?>" required>
		              <div class="invalid-feedback">
		                Please enter a valid surname.
		              </div>
		            </div>
				</div>

	            <div class="mb-3">
	              <label for="email">Email</label>
	              <input type="email" class="form-control" name="email" id="email" value="<?php print htmlspecialchars($v['email']) ?>" required>
	              <div class="invalid-feedback">
	                Please enter a valid email address for fatal error notification.
	              </div>
	            </div>
	
	            <hr class="mb-4">
	            <button class="btn btn-primary btn-lg btn-block" type="submit">Apply configuration</button>
	          </form>
	        </div>
	        <div class="col-md-2 order-md-3"></div>
	      </div>
	
	      <footer class="my-5 pt-5 text-center text-small">
	        <p class="mb-1"><a href="https://github.com/viames/Pair_example">Pair example</a> - a sample project powered by <a href="https://github.com/viames/Pair">Pair</a> PHP framework</p>
	        <ul class="list-inline">
	          <li class="list-inline-item"><a href="https://github.com/viames/Pair_example/issues">Issues</a></li>
	          <li class="list-inline-item"><a href="https://github.com/viames/Pair_example/wiki">Wiki</a></li>
	          <li class="list-inline-item"><a href="https://github.com/Viames/Pair_example/releases">Releases</a></li>
	        </ul>
	      </footer>
	    </div>
	   	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.1/pwstrength-bootstrap.min.js"></script>
	    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
	</body>    
</html><?php
		
	}
	
	public function printFinalPage() {
		
		$v = $this->getPostVars();
		
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Pair example installer</title>
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	    <style>
			body {
				background: #005AA7;
				background: -webkit-linear-gradient(to bottom, #005AA7, #EBF9F9);
				background: linear-gradient(to bottom, #005AA7, #EBF9F9);
				color: white; 
			}
			.text-muted { color: white !important; opacity: .5; }
			footer { color: #777; }
			hr { border-top: 1px solid rgba(255,255,255,.2); }
		</style>
	</head>
 	<body>
	    <div class="container">
	      <div class="py-5 text-center">
	        <img class="d-block mx-auto mb-4" src="installer/pair-logo.png" alt="" width="160" height="89">
	        <h2>Example installer</h2>
	        <p class="lead">Installation completed succesfully</p>
	      </div>
	      <div class="row">
	      	<div class="col-md-2 order-md-1"></div>
	        <div class="col-md-8 order-md-2">
	        	<?php $this->printErrors() ?>
	        	<?php $this->printNotifications() ?>
	        	<p>You can now use <?php print $v['productName'] . ' v' . $v['productVersion'] ?>. Please login at url: <a href="<?php print htmlspecialchars($this->baseUri) ?>"><?php print htmlspecialchars($this->baseUri) ?></a></p>
	        </div>
	        <div class="col-md-2 order-md-3"></div>
	      </div>
	
	      <footer class="my-5 pt-5 text-center text-small">
	        <p class="mb-1"><a href="https://github.com/viames/Pair_example">Pair example</a> - a sample project powered by <a href="https://github.com/viames/Pair">Pair</a> PHP framework</p>
	        <ul class="list-inline">
	          <li class="list-inline-item"><a href="https://github.com/viames/Pair_example/issues">Issues</a></li>
	          <li class="list-inline-item"><a href="https://github.com/viames/Pair_example/wiki">Wiki</a></li>
	          <li class="list-inline-item"><a href="https://github.com/Viames/Pair_example/releases">Releases</a></li>
	        </ul>
	      </footer>
	    </div>
	   	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.1/pwstrength-bootstrap.min.js"></script>
	    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
	</body>    
</html><?php
		
	}
	
	public function getPostVars() {
		
		// list of required field names
		$postVars = ['productName', 'productVersion', 'baseUri', 'dbHost', 'dbName',
				'dbUser', 'dbPass', 'name', 'surname', 'email'];
		
		$vars = [];
		
		foreach ($postVars as $v) {
			$vars[$v] = isset($_POST[$v]) ? $_POST[$v] : NULL;
		}
			
		return $vars;
		
	}
	
	public function createConfigFile() {
		
		$vars = $this->getPostVars();
		
		$content =
			"<?php\n\n// product\n" .
			"define ('PRODUCT_VERSION', '" . $vars['productVersion'] . "');\n" .
			"define ('PRODUCT_NAME', '" . $vars['productName'] . "');\n" .
			"define ('BASE_URI', '" . $vars['baseUri'] . "');\n\n" .
			"// database\n" .
			"define ('DB_HOST', '" . $vars['dbHost'] . "');\n" .
			"define ('DB_NAME', '" . $vars['dbName'] . "');\n" .
			"define ('DB_USER', '" . $vars['dbUser'] . "');\n" .
			"define ('DB_PASS', '" . $vars['dbPass'] . "');\n";
		
		if ($this->forceDbUtf8) {
			$content .= "define ('DB_UTF8', TRUE);\n";
		}
		
		$res = file_put_contents($this->rootFolder . '/config.php', $content);
		
		if (FALSE === $res) {
			$this->addError('Write of config.php file failed');
		}
		
	}
	
	public function createTempFolder() {
		
		$tempFolder = $this->rootFolder . '/temp';
		
		if (!(file_exists($tempFolder) and is_dir($tempFolder))) {
			$old = umask(0);
			mkdir($tempFolder, 0777, TRUE);
			umask($old);
		}
		
	}
	
	public static function deleteDir($dirPath) {
		
		if (!is_dir($dirPath)) {
			return;
		}
		
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		
		$files = glob($dirPath . '*', GLOB_MARK);
		
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
		}
		
		rmdir($dirPath);
	}
	
	public function selfRemove() {
		
		$folder = $this->rootFolder . '/installer';
		$files = ['dump.sql', 'pair-logo.png', 'start.php'];

		foreach ($files as $file) {
			try {
				unlink($folder . '/' . $file);
			} catch (Exception $e) {
				$this->addError('File ' . $folder . '/' . $file . 'deletion failed: ' . $e->getMessage());
				return;
			}
		}
		
		try {
			rmdir($folder);
		} catch (Exception $e) {
			$this->addError('Folder ' . $folder . ' deletion failed: ' . $e->getMessage());
			return;
		}
		
		$this->addNotification('Installer was deleted');
		
	}
	
}

$installer = new Installer();

$installer->checkFoldersErrors();
$installer->checkApacheErrors();
$installer->checkPhpErrors('5.6', ['fileinfo','json','mcrypt','pcre','PDO','pdo_mysql','Reflection']);

// form is submitted, check if can proceed to install
if (count($_POST)) {
	
	$installer->checkRequiredFields();
	
	$connected = $installer->connectToDbms();
	
	// only if db connected
	if ($connected) {
		
		// check and configure db
		$installer->checkDbmsVersion('5.6');
		$installer->createDb();
		$installer->checkDbUtf8();
		$installer->createUser();
		
		if (!count($installer->getErrors())) {
		
			// create a temporary empty folder
			$installer->createTempFolder();
			
			// create config.php file
			$installer->createConfigFile();
			
		}
		
	}
	
}

// show the setup page
if (!isset($_POST) or !count($_POST) or count($installer->getErrors())) {
	
	$installer->printSetupPage();

} else {
	
	// delete itself
	$installer->selfRemove();
	
	// show all notifications and print link to application root
	$installer->printFinalPage();
	
}