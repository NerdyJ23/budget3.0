<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;

class UsersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$this->set('result', (new AuthenticationController)->validUser('admin','PASSWORD'));
		// $query = $this->Users->find('all');

		// $data = $query->all()->toArray();
		// $this->set('users', $data);
	}

	public function get($user) {

	}

	public function login($username) {

	}


}