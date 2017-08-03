<?php

Class Model_user extends CI_Model {

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
	 * @param string $email
	 */
	public function getUser($email){
		$this->db->where('email', $email);
		return $this->db->get('user')->result_array();
	}

	/**
	 * Checks if EMAIL exists
	 * If exists, cannot be registered
	 * @param string $email
	 * @return boolean
	 */
	public function isUserExist($email){
		$isExist = FALSE;

		$allUser = $this->getAllUser();
		for ($i = 0; $i < count($allUser); $i++){
			if (mb_strtolower($allUser[$i]['email']) == mb_strtolower($email)){
				$isExist = TRUE;
				break;
			}
		}

		return $isExist;
	}
	
	/**
	 * Checks if user is allowed to login (login with email)
	 * @param string(max 32) $email
	 * @param string(max 32) $password
	 * @return boolean
	 */
	public function checkLogin($email, $password){
		$isRegistered = FALSE;
		
		$allUser = $this->getAllUser();
		for ($i = 0; $i < count($allUser); $i++){
			if (mb_strtolower($allUser[$i]['email']) == mb_strtolower($email)){
				if ($allUser[$i]['password'] == md5($password)){
					$isRegistered = TRUE;
					break;
				}
			}
		}
		
		return $isRegistered;
	}
	
	public function is_admin($email){
		$isAdmin = FALSE;
		
		$myArr = array('role' => 0);
		$this->db->where($myArr);
		$allAdmin = $this->db->get('user')->result_array();
		
		for ($i = 0; $i < count($allAdmin); $i++){
			if (mb_strtolower($allAdmin[$i]['email']) == mb_strtolower($email)){
				$isAdmin = TRUE;
			}
		}
		
		return $isAdmin;
	}
	
	/**
	* Return all email of any admin found in database
	* 
	* @return
	*/
	public function getAdminEmail(){
		$this->db->select('email');
		$this->db->from('user');
		$this->db->where('role', 0);
		return $this->db->get()->result_array();
	}
	
	/**
	* Insert User (REGISTER)
	* @param string $email
	* @param string(max 32) $password
	* @param string $name
	* @param date $date
	* 
	* @return
	*/
	public function insertUser($email, $password, $name, $date){
        $myArr = array(
        	'email' 	=> $email,
        	'password' 	=> $password,
        	'name' 		=> $name,
        	'birthdate' => $date
        );

        $this->db->insert('user', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	 * Update User (EDIT PROFILE)
	 * Can only update name and birthdate
	 * @param string $email
	 * @param string $name
	 * @param date $date
	 * @param file $picture
	 */
	public function updateUser($email, $name, $date){
		$myArr = array(
			'name' 		=> $name,
        	'birthdate' => $date
        );
	
		$this->db->where('email', $email);
		$this->db->update('user', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	* Update user's profile picture
	* Can only update user's profile picture
	* @param string $email
	* @param string $picture
	* 
	* @return
	*/
	public function updateUserPicture($email, $picture){
		$myArr = array(
        	'picture' => $picture
        );
	
		$this->db->where('email', $email);
		$this->db->update('user', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	* Update user's password
	* Can only update user's password
	* @param string $email
	* @param string(max 32) $password
	* 
	* @return
	*/
	public function updateUserPassword($email, $password){
		$myArr = array(
        	'password' 	=> $password
        );
	
		$this->db->where('email', $email);
		$this->db->update('user', $myArr);
	
		return $this->db->affected_rows();
	}
}
?>
