<?php

namespace App\Model\Entity;
use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class Item extends Entity {
	protected $_accessible = [
		'ID' => true,
		'Receipt' => true,
		'Name' => false,
		'Count' => false,
		'Cost' => false,
		'Category' => false
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}

	// protected function _getReceipt() {
	// 	return ((new EncryptionController)->encrypt($this->_fields['Receipt']));
	// }
}
?>