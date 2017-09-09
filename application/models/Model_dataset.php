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
	
	/**
	* Write dataset into text file
	* @param string $status - POS or NEG
	* 
	* @return
	*/
	public function writeDataset($status){
		$dataset = $this->getAllDatasetWithStatus($status);
		if ($status == 'neg'){
			$file_path = FCPATH . "/application/third_party/data_training_twitter_neg.txt";
			
			// overwrite file with first line
			write_file($file_path, $dataset[0]['text']."\r\n");
			
			// append the rest
			for ($i=1; $i<sizeof($dataset); $i++){
				write_file($file_path, $dataset[$i]['text']."\r\n", 'a+');
			}
		} else if ($status == 'pos'){
			$file_path = FCPATH . "/application/third_party/data_training_twitter_pos.txt";
			
			// overwrite file with first line
			write_file($file_path, $dataset[0]['text']."\r\n");
			
			// append the rest
			for ($i=1; $i<sizeof($dataset); $i++){
				write_file($file_path, $dataset[$i]['text']."\r\n", 'a+');
			}
		}
	}
	
}
?>
