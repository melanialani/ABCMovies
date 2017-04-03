<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class User extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('model_user');
	}
	
	public function login(){
		$oke = 0;
		
		// empty the error message
		$data['message'] = NULL;
		
		// take user's input from form
		$data['email'] = $this->input->post('email', TRUE);
		$data['password'] = md5($this->input->post('password', TRUE));
		$data['rememberMe'] = $this->input->post('rememberMe', TRUE);
		
		// if button LOGIN is pressed
		if ($this->input->post('login', TRUE)) {
			// cek kesesuaian email dan password
			$result = $this->model_user->getAllUser();
			
			for ($i = 0; $i < count($result); $i++){
				if ($data['email'] == $result[$i]['email']) { // if email found
					// if password is correct
					if ($data['password'] == $result[$i]['password']) 
						$oke = 1;
					else $data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>Password salah</div>";
					
					break;
				} else $data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>Email tidak ditemukan</div>";
			}
		}
		
		if ($oke){
			if ((int) $data['rememberMe'] == 1) {
				// set cookies
				$cookie = array('name' => 'abcmovies', 'value' => $data['email'], 'expire' => 60*60*24*3 ); // expire in 3 days
				set_cookie($cookie);
			} else {
				// set session -- pake metode cookie, tapi expire nya diset jadi 0
				$cookie = array('name' => 'abcmovies', 'value' => $data['email'], 'expire' => 0 ); // expire when browser closed
				set_cookie($cookie);
			}
			
			// go to home page
			redirect('film/index');
		} else { // load login page
			// fetch user's name
			if ($this->input->cookie('abcmovies')){
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
			} else $data['name'] = null;
			
			$this->load->view('includes/header', $data);
			$this->load->view('login', $data);
			$this->load->view('includes/footer');
		}
		
	}

	public function register(){
		$oke = 0;
		
		// empty the error message
		$data['message'] = NULL;
		
		// take user's input from form
		$data['email'] = $this->input->post('email', TRUE);
		$data['password'] = md5($this->input->post('password', TRUE));
		$data['repassword'] = md5($this->input->post('repassword', TRUE));
		$data['name'] = $this->input->post('name', TRUE);
		$data['birthdate'] = date("Y-m-d", strtotime($this->input->post('birthdate', TRUE)));
		
		// if button LOGIN is pressed
		if ($this->input->post('register', TRUE)) {
			// cek password & re-password
			if ($this->input->post('password', TRUE) == $this->input->post('repassword', TRUE)){
				$ada = 0;
				
				// cek email sudah ada atau belum
				$result = $this->model_user->getAllUser();
				for ($i = 0; $i < count($result); $i++){
					if ($data['email'] == $result[$i]['email']) { // if email found
						$ada = 1;
						$data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>Email sudah dipakai</div>";
						break;
					}
				}
				
				// email belum ada
				if (!$ada) { 
					if ($this->model_user->insertUser($data['email'],$data['password'],$data['name'],$data['birthdate'])) $oke = 1;
					else $data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>Register gagal</div>"; 
				}
			} else $data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>Password yang dimasukan tidak sama</div>";
		}
		
		if ($oke){
			// set session -- pake metode cookie, tapi expire nya diset jadi 0
			$cookie = array('name' => 'abcmovies', 'value' => $data['email'], 'expire' => 0 ); // expire when browser closed
			set_cookie($cookie);
			
			// go to home page
			redirect('film/index');
		} else { // load page again
			// fetch user's name
			if ($this->input->cookie('abcmovies')){
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
			} else $data['name'] = null;
			
			$this->load->view('includes/header', $data);
			$this->load->view('register', $data);
			$this->load->view('includes/footer');
		}
		
	}
	
	public function logout(){
		delete_cookie('abcmovies'); // clear cookie
		redirect('film/index'); // go back to home page
	}
	
	public function profile(){
		// check session -> have the user logged in properly?
		if ($this->input->cookie('abcmovies')){
			
			// if button update clicked
			if ($this->input->post('update')){
				redirect('user/updateProfile');
			} 
			
			// if button update_picture clicked
			else if ($this->input->post('update_picture')){
				redirect('user/updateProfilePicture');
			} 
			
			// if button update_password clicked
			else if ($this->input->post('update_password')){
				redirect('user/updateProfilePassword');
			} 
			
			// fetch user's profile
			$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
			$data['password'] = $user[0]['password'];
			$data['birthdate'] = $user[0]['birthdate'];
			$data['picture'] = $user[0]['picture'];
			
			$this->load->view('includes/header', $data);
			$this->load->view('user/profile', $data);
			$this->load->view('includes/footer');
		} 
		else { // not logged in yet
			redirect('user/login');
		}
	}
	
	public function updateProfile(){
		// check session -> have the user logged in properly?
		if ($this->input->cookie('abcmovies')){
			
			// if button save clicked
			if ($this->input->post('save')){
				// fetch user input
				$data['name'] = $this->input->post('name', TRUE);
				$data['birthdate'] = date("Y-m-d", strtotime($this->input->post('birthdate', TRUE)));
				
				// update user profile
				if ($this->model_user->updateUser($this->input->cookie('abcmovies'),$data['name'],$data['birthdate'])) 
					redirect('user/profile'); // update success, go back to user profile
					
			} else { // load page as usual
				// fetch user's profile
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
				$data['birthdate'] = $user[0]['birthdate'];
				
				$this->load->view('includes/header', $data);
				$this->load->view('user/edit_profile', $data);
				$this->load->view('includes/footer');
			} 
		} else { // users have not logged in yet
			redirect('user/login');
		}
	}
	
	public function updateProfilePassword(){
		// check session -> have the user logged in properly?
		if ($this->input->cookie('abcmovies')){
			$okay = FALSE;
			$data['message'] = NULL;
			
			// if button save clicked
			if ($this->input->post('save')){
				// fetch user input
				$data['password'] = md5($this->input->post('password', TRUE));
				$data['repassword'] = md5($this->input->post('repassword', TRUE));
				
				// cek password & re-password
				if ($this->input->post('password', TRUE) == $this->input->post('repassword', TRUE)){
					// update user profile
					if ($this->model_user->updateUserPassword($this->input->cookie('abcmovies'),$data['password']))
						$okay = TRUE;
				} else $data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>Password yang dimasukan tidak sama</div>";
				
			}
			
			if ($okay){ // update success, go back to user profile
				redirect('user/profile'); 
			} else {
				// fetch user's profile
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
				$data['password'] = $user[0]['password'];
				
				$this->load->view('includes/header', $data);
				$this->load->view('user/edit_password', $data);
				$this->load->view('includes/footer');
			}
		} else { // users have not logged in yet
			redirect('user/login');
		}
	}
	
	public function updateProfilePicture(){
		// check session -> have the user logged in properly?
		if ($this->input->cookie('abcmovies')){
			$okay = FALSE;
			$data['message'] = NULL;
			
			// if button save clicked
			if ($this->input->post('save')){
				
				// generate file name for picture
				$date = new DateTime();
				$file_name = $date->getTimestamp() . substr($this->input->cookie('abcmovies'),0,3);
				
				// upload photo configuration
				$config =  array(
					'upload_path'     => "./pictures/",
					'allowed_types'   => "gif|jpg|png|jpeg", //gif|jpg|png|jpeg
					'overwrite'       => TRUE,
					'file_name'       => $file_name
				);
				
				$this->upload->initialize($config);
				
				if($this->upload->do_upload('profilePicture')){ // photo uploaded successfully
					$temp = $this->upload->data();
					
					if ($this->model_user->updateUserPicture($this->input->cookie('abcmovies'),$config['upload_path'].$temp['file_name'])) 
						$okay = TRUE;
				} else {
					$data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>".$this->upload->display_errors()."</div>";
				}
			} 
			
			if ($okay){
				redirect('user/profile'); // update success, go back to user profile
			} else { // load page as usual
				// fetch user's profile picture
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
				$data['picture'] = $user[0]['picture'];
				
				$this->load->view('includes/header', $data);
				$this->load->view('user/edit_picture', $data);
				$this->load->view('includes/footer');
			} 
		} else { // users have not logged in yet
			redirect('user/login');
		}
	}
	
}
	
?>