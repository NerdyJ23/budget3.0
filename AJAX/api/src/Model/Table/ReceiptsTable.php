<?php 
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;

class ReceiptsTable extends Table {
	public function initialize(array $config): void {
		$this->setDisplayField('Location');
		$this->setTable('Receipts');

		$this->hasMany('Items')
			->setForeignKey('Receipt')
			->setBindingKey('ID');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {

	}
}

?>