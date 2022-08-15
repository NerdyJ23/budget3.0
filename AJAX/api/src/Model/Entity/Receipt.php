<?php

namespace App\Model\Entity;
use Cake\ORM\Entity;
use App\Controller\EncryptionController;

class Receipt extends Entity {
	protected $_accessible = [
		'ID' => false,
		'User' => true,
		'Location' => true,
		'Cost' => true,
		'Date' => true,
		'Category' => true,
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getUserId() {
		return ((new EncryptionController)->encrypt($this->_fields['User']));
	}
	protected function _getCategory() {
		return $this->_fields['Category'] == null ? 'None' : $this->_fields['Category'];
	}

	public function setName($name) {
		$this->_fields['Location'] = $name;
		$this->setDirty('Location');
	}
	public function setDate($date) {
		$this->_fields['Date'] = $date;
		$this->setDirty('Date');
	}
}
?>