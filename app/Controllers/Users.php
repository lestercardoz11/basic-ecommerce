<?php namespace App\Controllers;

class Users extends BaseController
{
	public function index()
	{
		$data = [];
		helper(['form']);

		if ($this->request->getMethod() == 'post') {
			$rules = [
				'email' => 'required|min_length[6]|max_length[50]|valid_email',
				'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Email or Password does not match'
				]
			];

			if (! $this->validate($rules, $errors)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new UserModel();

				$user = $model->where('email', $this->request->getVar('email'))
							  ->first();

				$this->setUserSession($user);

				return redirect()->to('Dashboard');
			}
		}
		echo view('templates/header', $data);
		echo view('login', $data);
		echo view('templates/footer', $data);
	}

	private function setUserSession($user){
		$data = [
			'id' => $user['id'],
			'name' => $user['name'],
			'email' => $user['email'],
			'isLoggedIn' => true,
		];

		session()->set($data);
		return true;
	}

}
