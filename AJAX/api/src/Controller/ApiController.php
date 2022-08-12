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
		$this->_returnJSON();
	}
	protected function _returnJSON() {
		$this->response = $this->response->cors($this->request)
		->allowOrigin('*')
		->build();
		$this->viewBuilder()->setOption('serialize',true);
	}
}

?>