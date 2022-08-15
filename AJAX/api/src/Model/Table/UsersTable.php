<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;

class UsersTable extends Table {
	public function initialize(array $config): void {

		$this->setDisplayField('first_name');
		$this->setTable('Users');

		$this->hasMany('Receipts')
			->setForeignKey('ID')
			->setBindingKey('id');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;

	}
}

?>