<?php
namespace App\Controller\Security;

use Cake\Controller\Controller;
use App\Controller\UsersController;

class AuthenticationController extends Controller {

	public function initialize(): void {
		parent::initialize();
	}
	public function generateToken(): string {
		return bin2hex(random_bytes(16));
	}

	public function validToken($token): bool {

	}

	public function validUser($username, $password) {
		$passwordHash = (new EncryptionController)->hashPassword($password);
		$userDB = new UsersController();
		$dbPass = $userDB->Users->find('all')
			->where(['Users.username = ' => $username])
			->limit(1)
			->all()
			->toArray();
		if($passwordHash == $dbPass[0]->password) {
			return true;
		}
		return false;
	}
}
?>