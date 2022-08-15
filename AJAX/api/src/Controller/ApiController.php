<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

use Cake\Utility\Security;
use Cake\View\JsonView;
use Cake\Auth\WeakPasswordHasher;

class ApiController extends Controller {
	public function initialize(): void {
		parent::initialize();
		$this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
	}
	public function beforeFilter(EventInterface $event) {
		if($this->_isPushingData()) {

		} //else if ($this->_isListingData()) {
			$this->set('csrfToken', $this->request->getAttribute('csrfToken'));
		//}
		$this->_returnJSON();
	}
	protected function _returnJSON() {
		$this->response = $this->response->cors($this->request)
		->allowOrigin('*')
		->build();
		$this->viewBuilder()->setOption('serialize',true);
	}
	protected function _isPushingData() {
		return ($this->request->is('post') || $this->request->is('patch'));
	}
	protected function _isListingData() {
		return ($this->request->is('get') || $this->request->is('list'));
	}
}

?>