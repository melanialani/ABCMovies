<?php

Class Model_dataset extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllDataset(){
    	$this->db->order_by('date', 'desc');
        return $this->db->get('dataset')->result_array();
	}

	/**
	* Returns all dataset with certain status 
	* @param string $status - POS, NEG, REVIEW, NONREVIEW, or LEXICON
	* 
	* @return
	*/
	public function getAllDatasetWithStatus($status){
		$this->db->where('status', $status);
		return $this->db->get('dataset')->result_array();
	}
	
	public function getDatasetWithText($text){
		$this->db->where('text', $text);
		return $this->db->get('dataset')->result_array();
	}
	
	/**
	* Insert new dataset 
	* @param string $text
	* @param string $ori_id
	* @param string $status - POS, NEG, REVIEW, NONREVIEW, or LEXICON
	* @param int $score
	* 
	* @return
	*/
	public function insertDataset($text, $ori_id, $status, $score){
		// insert to textfile
		if ($status == 'neg'){
			$file_path = dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt'; // APPPATH . "/assets/login/log_.txt";
			if(file_exists($file_path)) {
			   $file_path = dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt'; // APPPATH . "/assets/login/log_.txt";
				write_file($file_path, $text . "\r\n", 'a+');
			} else {
			    write_file($file_path, $text);
			}
		} else if ($status == 'pos'){
			$file_path = FCPATH . "/application/third_party/data_training_twitter_pos.txt";
			if ( ! write_file($file_path, $text."\r\n", 'a+')) echo 'Unable to write the file';
			else echo 'File written!';
		}
		
		// insert to database
        $myArr = array(
			'text' 		=> $text,
			'ori_id'	=> $ori_id,
        	'status' 	=> $status,
        	'score' 	=> $score
        );

        $this->db->insert('dataset', $myArr);
        return $this->db->affected_rows();
	}
	
	/**
	* Delete a dataset by id
	* @param int $id
	* 
	* @return
	*/
	public function deleteDataset($id){
		$this->db->where('id', $id);
		$this->db->delete('dataset');
		return $this->db->affected_rows();
	}
	
}
?>
