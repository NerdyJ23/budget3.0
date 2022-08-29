<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;

use Cake\Utility\Security;
use Cake\Core\Configure;

use App\Controller\Security\EncryptionController;

class ItemsController extends ApiController {

	public function initialize(): void {
		parent::initialize();
	}

	public function update($item) {
		$table = $this->getTableLocator()->get('Items');
		if((new EncryptionController)->decrypt($item->id) == false) {
			$this->setSuccess(false);
		} else {
			$newItem = $table->get((new EncryptionController)->decrypt($item->id));
			$newItem->Name = $item->name;
			$newItem->Count = $item->count;
			$newItem->Cost = $item->cost;
			$newItem->Category = $item->category;

			$this->setSuccess($table->save($newItem));
		}
	}

	public function create($item, $receiptId) {
		$newItem = $this->fetchTable('Items')->newEntity([
			'Receipt' => $receiptId,
			'Name' => $item->name,
			'Count' => $item->count,
			'Cost' => $item->cost
		]);

		if($item->category !== '' && $item->category !== null ) {
			$newItem->Category = $item->category;
		}
		$this->setSuccess($this->fetchTable('Items')->save($newItem));
	}

}

?>