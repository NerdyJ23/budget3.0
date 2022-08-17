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
	}

	public function get($user) {

	}

	public function login() {
		$pass = $this->request->getData('password');
		$username = $this->request->getData('username');
		$validUser = (new AuthenticationController)->validUser('admin',$pass);

		if($validUser == true) {
			$query = $this->Users->find('all')
				->where(['Users.username = ' => $username])
				->limit(1);
			$data = $query->all()->toArray();

			$this->_loginAuth($data[0]);
		} else {
			$this->response = $this->response->withStatus(403);
		}
	}

	private function _loginAuth($user) {
		$decrypted = intval((new EncryptionController)->decrypt($user->encryptedId));

		$userTable = $this->getTableLocator()->get('Users');
		$u = $userTable->get($user->id);
		$userTable->patchEntity($u, ['token' => (new AuthenticationController)->generateToken()]);

		$u->token = (new AuthenticationController)->generateToken();
		$u->token_valid_until = $u->setTokenTimeLimit(7);
		$result = $userTable->saveOrFail($u);

		$aftersave = $this->Users->get($decrypted);

		if($result != false) {
			$this->response = $this->response->withStatus(200);
			$response = $this->response;
			$response = $response->withHeader('Set-Cookie', 'accessToken=' . $u->token . '; HttpOnly; Secure; SameSite=Strict; Max-Age=604800;');
		} else {
			$this->response = $this->response->withStatus(400);
		}
	}


}