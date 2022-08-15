<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class User extends Entity {
	protected $_hidden = ['password', 'token'];

	protected $_accessible = [
		'id' => true, //int
		'username' => true, //varchar
		'password' => false, //varchar hashed
		'first_name' => false, //varchar
		'last_name' => false, //varchar nullable
		'token' => false, //varchar nullable
		'token_valid_until' => false, //timestamp nullable
		'last_logged_in' => false //timestamp nullable
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['id']));
	}

	protected function _getFullName() {
		$str = $this->first_name;
		if($this->last_name !== null) {
			$str .= ' ' . $this->last_name;
		}

		return $str;
	}
}
?>