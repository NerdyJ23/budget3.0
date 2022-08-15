<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;

use Cake\Utility\Security;
use Cake\Core\Configure;
use App\Controller\Security\EncryptionController;

class ReceiptsController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function index() {
		$this->set(['message','view help docs for details']);
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$year = $this->request->getQuery('year') == null ?  date('Y') : $this->request->getQuery('year');
		$month = $this->request->getQuery('month') == null ?  date('m') : $this->request->getQuery('month');

		$query = $this->Receipts->find('all')
			->where(['month(Receipts.Date) =' => $month, 'year(Receipts.Date) =' => $year])
			->contain(['Items'])
			->limit($limit)
			->page($page);

		$data = $query->all()->toArray();
		$result = [];

		foreach($data as $item) {
			$receipt = $this->encodeReceipt($item);
			$result[] = $receipt;
		}
		$this->set('result', $result);

		if($count) {
			$this->set('count',$query->all()->count());
		}

		$this->getYears();
	}

	public function get($id) {
		$query = $this->Receipts->find('all')
			->where(['Receipts.ID =' => (new EncryptionController)->decrypt($id)])
			->contain(['Items'])
			->limit(1);

		$data = $query->all()->toArray();
		$this->set('result', $this->encodeReceipt($data[0]));
	}
	public function getYears() {
		$query = $this->Receipts->find()
		->select([
			'date' => 'year(Date)'
		])
		->group('Year(date)');
		$data = $query->all()->toArray();
		$this->set('years', $data);
	}

	public function edit($id) {
		$safeid = (new EncryptionController)->decrypt($id);
		if(is_bool($safeid) == true) {
			$this->set('code',200);
			return;
		}
		$receivedReceipt = json_decode($this->request->getData('receipt'));
		$this->saveToReceipts($receivedReceipt);

	}
	private function encodeReceipt($receipt) {
		return [
			'id' => $receipt->get('id'),
			'userid' => $receipt->get('userId'),
			'name' => $receipt->Location,
			'cost' => $receipt->Cost,
			'date' => $receipt->Date,
			'category' => $receipt->get('category'),
			'items' => $this->encodeItem($receipt)
		];
	}

	private function encodeItem($receipt) {
		$items = [];
		foreach($receipt->items as $item) {
			$items[] = [
				'id' => $item->get('id'),
				'name' => $item->Name,
				'count' => $item->Count,
				'cost' => $item->Cost,
				'category' => $item->Category
			];
		}
		return $items;
	}

	private function saveToReceipts($newReceipt) {
		$table = $this->getTableLocator()->get('Receipts');
		$receipt = $table->get((new EncryptionController)->decrypt($newReceipt->id));

		$receipt->setName($newReceipt->name);
		$receipt->setDate($newReceipt->date);
		$result = $table->save($receipt);

		if($result != false) {
			$this->set('code',200);
		} else {
			$this->set('code',400);
		}
	}
}

?>