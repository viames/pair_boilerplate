<?php

use Pair\Form;
use Pair\Locale;
use Pair\Model;
use Pair\Translator;

class UserModel extends Model {

	/**
	 * Try the user login on LDAP system taking setting from configuration.
	 * 
	 * @param	string	LDAP’s username
	 * @param	string	LDAP’s password.
	 * @param	number Offset Time Zone.
	 * @return	boolean
	 * 
	 * @todo Move into User class
	 */
	/*
	public function ldapLogin($username, $password, $offset, $tzName) {
		
		// checks if LDAP PHP extension is configured properly
		if (!function_exists('ldap_connect')) {
			$this->addError('SERVER_IS_NOT_PROPERLY_SET_FOR_AUTHENTICATION');
			$this->logError('LDAP extension is not available on server');
			return FALSE;
		}
			
		// loads user row
		$this->db->setQuery('SELECT * FROM users WHERE ldap_user=?');
		$row = $this->db->loadObject(array($username));
		
		if (is_object($row)) {
				
			$user = new User($row);
		
			// user disabled
			if ('0'==$user->enabled) {
					
				$this->addError('USER_IS_DISABLED');
				return FALSE;

			}

			// server connection
			$ds = ldap_connect(LDAP_HOST, LDAP_PORT);
			
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			
			if (!ldap_set_option($ds, LDAP_OPT_REFERRALS, 0)) {
				$this->addError('Failed to set opt referrals to 0');
				return FALSE;
			}
			
			// server binding
			if (!@ldap_bind($ds, LDAP_USERBIND, LDAP_BINDPW)){
				$this->addError('Authentication is required, but LDAP server is not available');
				return FALSE;
			}
			
			$filter = 'sAMAccountName='. $username;
	
			$res = ldap_search($ds, LDAP_BASEDN, $filter);
			
			if ($res) {
			
				$result = ldap_get_entries($ds, $res);
				
				if ($result['count']) {
					
					try {
						$resBind = @ldap_bind($ds, $result[0]['dn'], $password);
					} catch(Exception $e) {
						exit();
					}
					
					if ($resBind) {
						
						ldap_unbind($ds);

						$ldapUser	= $result[0]['samaccountname'][0];
						$name		= ucwords(strtolower($result[0]['sn'][0] .' '. $result[0]['givenname'][0]));

						// creates session for this user
						$user->createSession($name, $offset, $tzName);
						
						return (bool)$result['count'];
						
					} else {
						
						$this->addError('Password entered is not valid');
						
					}
	
				} else {
					
					$this->addError('USERNAME_NOT_VALID');
					
				}
				
			} else {
				
				$this->addError('LDAP search resource identifier not found');
				
			}
			
		} else {
			
			$this->addError('USERNAME_NOT_VALID');
			
		}
		
		return FALSE;

	}
	*/
	
	/**
	 * Returns login form.
	 * 
	 * @return Form
	 */
	public function getLoginForm() {
		
		$tran = Translator::getInstance();
		$form = new Form();
		
		$form->addControlClass('form-control');
		
		$form->addInput('username', array('autocorrect'=>'off', 'autocapitalize'=>'off'))
			->setRequired()->setMinLength(3)->setPlaceholder($tran->get('USERNAME'));
		$form->addInput('password', array('autocorrect'=>'off', 'autocapitalize'=>'off'))
			->setType('password')->setRequired()
			->setPlaceholder($tran->get('PASSWORD'));
		$form->addInput('referer')->setType('hidden');
		$form->addInput('timezone')->setType('hidden');
		
		return $form;
		
	}
	
	/**
	 * Returns the Form object for create/edit Users objects.
	 *
	 * @return Form
	 */
	public function getUserForm() {

		$form = new Form();
		
		$form->addControlClass('form-control');
		
		$query =
			'SELECT lo.*, CONCAT(la.english_name, " (", co.english_name, ")") AS language_country ' .
			' FROM `locales` AS lo' .
			' INNER JOIN languages AS la ON lo.language_id = la.id' .
			' INNER JOIN countries AS co ON lo.country_id = co.id' .
			' ORDER BY la.english_name';
		
		$locales = Locale::getObjectsByQuery($query);

		$form->addInput('name')->setRequired()->setMinLength(2);
		$form->addInput('surname')->setRequired()->setMinLength(2);
		$form->addInput('email')->setType('email');
		$form->addInput('ldapUser')->setMinLength(2);
		$form->addInput('username')->setRequired()->setMinLength(3)->setPlaceholder('Username');
		$form->addInput('password', array('autocomplete'=>'off', 'autocorrect'=>'off', 'autocapitalize'=>'off'))
			->setType('password')->setMinLength(8)->setPlaceholder('Password');
		$form->addInput('showPassword')->setType('bool');
		$form->addSelect('localeId')->setRequired()->setListByObjectArray($locales,'id','languageCountry');

		return $form;

	}
}
