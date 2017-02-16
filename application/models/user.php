<?php

Class User extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllUser(){
        return $this->db->get('user')->result_array();
	}

	/**
	 * Return user's information
	 * To fill the fields in User's Profile
	 * @param string $username
	 */
	public function getUser($username){
		$this->db->where('username', $username);
		return $this->db->get('user')->result_array();
	}

	/**
	 * Checks if USERNAME and EMAIL exists
	 * If exists, cannot be registered
	 * @param string(max 32) $username
	 * @param string $email
	 * @return boolean
	 */
	public function isUserExist($username, $email){
		$isExist = FALSE;

		$allUser = $this->getAllUser();
		for ($i = 0; $i < count($allUser); $i++){
			if ($allUser[$i]['email'] == $email || $allUser[$i]['username'] == $username){
				$isExist = TRUE;
				break;
			}
		}

		return $isExist;
	}
	
	/**
	 * Checks if user is allowed to login
	 * Can login with either username and email
	 * @param string(max 32) $username
	 * @param string(max 32) $password
	 * @return boolean
	 */
	public function checkLogin($username, $password){
		$isRegistered = FALSE;
		
		$allUser = $this->getAllUser();
		for ($i = 0; $i < count($allUser); $i++){
			if ($allUser[$i]['email'] == $username || $allUser[$i]['username'] == $username){
				if ($allUser[$i]['password'] == md5($password)){
					$isRegistered = TRUE;
					break;
				}
			}
		}
		
		return $isRegistered;
	}

	/**
	 * Insert User (REGISTER)
	 * @param string(max 32) $username
	 * @param string(max 32) $password
	 * @param string $email
	 * @param string $name
	 * @param date $date
	 * @param file $picture
	 */
	public function insertUser($username, $password, $email, $name, $date, $picture){
        $myArr = array(
        	'username' 	=> $username,
        	'email' 	=> $email,
        	'password' 	=> md5($password),
        	'name' 		=> $name,
        	'birthdate' => $date,
        	'picture'	=> $picture
        );

        $this->db->insert('user', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	 * Update User (EDIT PROFILE)
	 * Can only update password, name, birthdate, profile picture
	 * @param string $username
	 * @param string(max 32) $password
	 * @param string $name
	 * @param date $date
	 * @param file $picture
	 */
	public function updateUser($username, $password, $name, $date, $picture){
		$myArr = array(
        	'password' 	=> md5($password),
			'name' 		=> $name,
        	'birthdate' => $date,
        	'picture'	=> $picture
        );
	
		$this->db->where('username', $username);
		$this->db->update('user', $myArr);
	
		return $this->db->affected_rows();
	}
}
?>
