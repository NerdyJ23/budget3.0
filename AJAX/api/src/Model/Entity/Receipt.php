<?php 

namespace App\Model\Entity;
use Cake\ORM\Entity;
use App\Controller\EncryptionController;

class Receipt extends Entity {
	protected $_accessible = [
		'ID' => true,
		'User' => false,
		'Location' => false,
		'Cost' => false,
		'Date' => false,
		'Category' => false,
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
}
?>