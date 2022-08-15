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
		$pass = $this->request->getData('password');
		$validUser = (new AuthenticationController)->validUser('admin',$pass);
		$this->set('valid',$validUser);

		if($validUser == true) {
			$query = $this->Users->find('all')
				->where(['Users.username = ' => $username])
				->limit(1);
			$data = $query->all()->toArray();
			$user = $data[0];

			$this->_loginAuth($user);
		} else {
			$this->set('code',403);
		}
	}

	private function _loginAuth($newUser) {
		$table = $this->getTableLocator()->get('Users');
		$user = $table->get((new EncryptionController)->decrypt($newUser->id));
		$user->token = (new AuthenticationController)->generateToken() . '';
		$this->set('newuser', $user);

		// $result = $table->save($user);

		// if($result != false) {
		// 	$this->set('code',200);
		// } else {
		// 	$this->set('code',400);
		// }
	}


}