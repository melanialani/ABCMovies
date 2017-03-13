<?php

Class Model_banner extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllBanner(){
        return $this->db->get('banner')->result_array();
	}

	/**
	* Get a banner information
	* @param int $id
	* 
	* @return
	*/
	public function getBanner($id){
		$this->db->where('id', $id);
		return $this->db->get('banner')->result_array();
	}
	
	/**
	* Get all active banner
	* Returns all active banner
	* @return
	*/
	public function getAllActiveBanner(){
		$this->db->where('status', 1);
		return $this->db->get('banner')->result_array();
	}
	
	/**
	* Insert new banner
	* @param string $name
	* @param string(path) $picture
	* @param boolean $status
	* 
	* @return
	*/
	public function insertBanner($name, $picture, $status){
        $myArr = array(
			'name' 		=> $name,
        	'picture' 	=> $picture,
        	'status' 	=> $status
        );

        $this->db->insert('banner', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	* Update banner's information (by id)
	* @param int $id
	* @param string $name
	* @param string(path) $picture
	* @param boolean $status
	* 
	* @return
	*/
	public function updateBanner($id, $name, $picture, $status){
		$myArr = array(
			'name' 		=> $name,
        	'picture' 	=> $picture,
        	'status' 	=> $status
        );
	
		$this->db->where('id', $id);
		$this->db->update('banner', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	* Delete a banner by id
	* @param int $id
	* 
	* @return
	*/
	public function deleteBanner($id){
		$this->db->where('id', $id);
		$this->db->delete('banner');
		return $this->db->affected_rows();
	}
	
	/**
	* Activate a banner (by id)
	* @param int $id
	* 
	* @return
	*/
	public function activateBanner($id){
		$myArr = array( 'status' => 1 );
	
		$this->db->where('id', $id);
		$this->db->update('banner', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	* Deactivate a banner (by id)
	* @param int $id
	* 
	* @return
	*/
	public function deactivateBanner($id){
		$myArr = array( 'status' => 0 );
	
		$this->db->where('id', $id);
		$this->db->update('banner', $myArr);
	
		return $this->db->affected_rows();
	}
	
}
?>
