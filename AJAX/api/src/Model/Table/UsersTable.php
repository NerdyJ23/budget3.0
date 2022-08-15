<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;

class UsersTable extends Table {
	public function initialize(array $config): void {

		$this->setTable('Users');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {

	}
}

?>