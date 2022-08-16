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

			$this->set('after3', $this->_loginAuth($data[0]));
		} else {
			$this->set('code',403);
		}
	}

	private function _loginAuth($user) {
		// $this->set('param usr', $newUser);
		$decrypted = intval((new EncryptionController)->decrypt($user->encryptedId));
		$this->set('decrypted',$decrypted);
		$this->set('before', $user->token);

		$userTable = $this->getTableLocator()->get('Users');
		$u = $userTable->get($user->id);
		$userTable->patchEntity($u, ['token' => (new AuthenticationController)->generateToken()]);
		$this->set('user', $u);
		$u->token = (new AuthenticationController)->generateToken();
		$result = $userTable->saveOrFail($u);
		// return $userTable;
		// $query = $this->Users->query();
		// $result = $query->update()
		// 	->set(['token' => (new AuthenticationController)->generateToken()])
		// 	->where(['id' => $decrypted])
		// 	->execute(); //this does not trigger onsave
		// $user = $table->get($decrypted);

		// $user->setId($decrypted);
		$aftersave = $this->Users->get($decrypted);
		$this->set('after', $aftersave->token);
		$this->set('result', $result);
		// $result = $table->save($user);

		// if($result != false) {
		// 	$this->set('code',200);
		// } else {
		// 	$this->set('code',400);
		// }
	}


}