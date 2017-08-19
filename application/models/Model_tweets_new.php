<?php

Class Model_tweets_new extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

	/**
	* Insert into table tweets_ori (step 1)
	* @param int $film_id
	* @param int $twitter_id
	* @param string $text
	* @param datetime $created_at
	* 
	* @return
	*/
	public function insertTweetOri($film_id, $twitter_id, $text, $created_at){
        $myArr = array(
			'film_id' 		=> $film_id,
			'twitter_id' 	=> $twitter_id,
			'text' 			=> $text,
			'created_at' 	=> $created_at
		);
		$this->db->insert('tweets_ori', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Insert into table tweets_regex
	* @param int $twitter_id
	* @param string $text
	* 
	* @return
	*/
	public function insertTweetRegex($twitter_id, $text){
        $myArr = array(
			'ori_id' 	=> $twitter_id,
			'text' 		=> $text
		);
		$this->db->insert('tweets_regex', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Insert into table tweets_lexicon
	* @param int $twitter_id
	* @param string $intersect
	* @param int $score
	* 
	* @return
	*/
	public function insertTweetLexicon($twitter_id, $intersect, $score){
        $myArr = array(
			'ori_id' 		=> $twitter_id,
			'intersect' 	=> $intersect,
			'score' 		=> $score
		);
		$this->db->insert('tweets_lexicon', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Insert into table tweets_replaced
	* @param int $twitter_id
	* @param string $text
	* @param string $intersect
	* 
	* @return
	*/
	public function insertTweetReplaced($twitter_id, $text, $intersect){
        $myArr = array(
			'ori_id' 		=> $twitter_id,
			'text' 			=> $text,
			'intersect' 	=> $intersect
		);
		$this->db->insert('tweets_replaced', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Insert into table tweets_final
	* @param string $twitter_id
	* @param int $film_id
	* @param string $text
	* @param boolean $is_review
	* @param boolean $is_positive
	* @param boolean $yes_review
	* @param boolean $yes_positive
	* @param boolean $confirmed
	* @param boolean $duplicate
	* @param float $persen_pos
	* @param float $persen_neg
	* 
	* @return
	*/
	public function insertTweetFinal($twitter_id, $film_id, $text, $is_review, $is_positive, $yes_review, $yes_positive, $confirmed, $duplicate, $persen_pos, $persen_neg){
        $myArr = array(
        	'ori_id' 		=> $twitter_id,
        	'film_id' 		=> $film_id,
        	'text' 			=> $text,
        	'is_review' 	=> $is_review,
        	'is_positive' 	=> $is_positive,
        	'yes_review' 	=> $yes_review,
        	'yes_positive' 	=> $yes_positive,
        	'confirmed' 	=> $confirmed,
        	'duplicate' 	=> $duplicate,
        	'persen_pos' 	=> $persen_pos,
        	'persen_neg' 	=> $persen_neg
        );
        $this->db->insert('tweets_final', $myArr);
		return $this->db->affected_rows();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	/**
	* Update yes_positive in table tweets_final
	* @param int $id
	* @param boolean $yes_positive
	* 
	* @return
	*/
	public function updateTweetFinal($id, $yes_positive){
		$myArr = array(
        	'yes_review' 	=> 1,
        	'yes_positive' 	=> $yes_positive,
        	'confirmed' 	=> 1
        );
	
		$this->db->where('id', $id);
		$this->db->update('tweets_final', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Mark a tweet in table tweets_final as non-review
	* The value of yes_review & yes_positive will be 0, and confirmed = 1
	* @param int $id
	* 
	* @return
	*/
	public function deleteTweetFinal($id){
		$myArr = array(
        	'yes_review' 	=> 0,
        	'yes_positive' 	=> 0,
        	'confirmed' 	=> 1
        );
	
		$this->db->where('id', $id);
		$this->db->update('tweets_final', $myArr);
		return $this->db->affected_rows();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	/**
	* Get all data from a table
	* Param $table_name accepts: tweets, tweets_ori, tweets_final, tweets_lexicon, tweets_regex, tweets_replaced
	* @param string $table_name
	* 
	* @return
	*/
	public function getAllTweetsFrom($table_name){
        return $this->db->get($table_name)->result_array();
	}
	
	/**
	* Get all tweets from table tweets_ori where film_id = param
	* @param int $film_id
	* 
	* @return
	*/
	public function getAllOriTweetsByMovie($film_id){
		$this->db->where('film_id', $film_id);
        return $this->db->get('tweets_ori')->result_array();
	}
	
	/**
	* Count how many data is in a table
	* Param $table_name accepts: tweets, tweets_ori, tweets_final, tweets_lexicon, tweets_regex, tweets_replaced
	* @param string $table_name
	* 
	* @return
	*/
	public function getCountTweetsFrom($table_name){
		return $this->db->count_all($table_name);
	}
	
	/**
	* Get data from a table with pagination
	* Param $table_name accepts: tweets, tweets_ori, tweets_final, tweets_lexicon, tweets_regex, tweets_replaced
	* @param string $table_name
	* @param int $limit
	* @param int $start
	* 
	* @return
	*/
	public function get_data($table_name, $limit, $start){
		$this->db->limit($limit, $start);
		$query = $this->db->get($table_name);
		return $query->result_array();
	}
	
	/**
	* Get a certain tweet from 5 different tables (by id)
	* with value of $table_name accepts: 'tweets_ori', tweets_replaced', 'tweets_regex', 'tweets_lexicon', and 'tweets_final'
	* @param string $table_name
	* @param int $id
	* 
	* @return
	*/
	public function getTweet($table_name, $id){
		$this->db->where('id', $id);
		return $this->db->get($table_name)->result_array();
	}
	
	/**
	* Get a certain tweet from 5 different tables (by twitter_id)
	* with value of $table_name accepts: 'tweets_ori', tweets_replaced', 'tweets_regex', 'tweets_lexicon', and 'tweets_final'
	* @param string $table_name
	* @param int $twitter_id
	* 
	* @return
	*/
	public function getTweetByOri($table_name, $twitter_id){
		if ($table_name == 'tweets_ori')
			$this->db->where('twitter_id', $twitter_id);
		else 
			$this->db->where('ori_id', $twitter_id);
		return $this->db->get($table_name)->result_array();
	}
	
	/**
	* Check if a text is already exist in a table
	* with value of $table_name accepts: 'tweets_ori', tweets_replaced', 'tweets_regex', 'tweets_lexicon', and 'tweets_final'
	* @param string $table_name
	* @param string $text
	* 
	* @return
	*/
	public function getTweetByText($table_name, $text){
		$this->db->where('text', $text);
		return $this->db->get($table_name)->result_array();
	}
	
	/**
	* Return all unconfirmed tweets
	* @param int $film_id
	* 
	* @return
	*/
	public function getAllTweetByMovieUnconfirmed($film_id){
		$index = 0;
		$returnArray[$index]['id'] = NULL;
		$returnArray[$index]['ori_id'] = NULL;
		$returnArray[$index]['text'] = NULL;
		$returnArray[$index]['is_review'] = NULL;
		$returnArray[$index]['is_positive'] = NULL;
		$returnArray[$index]['yes_review'] = NULL;
		$returnArray[$index]['yes_positive'] = NULL;
		$returnArray[$index]['confirmed'] = NULL;
		
		// get all unconfirmed tweets_final
		$this->db->select('fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_review, fin.is_positive as is_positive, fin.yes_review as yes_review, fin.yes_positive as yes_positive, fin.confirmed as confirmed', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.confirmed', 0);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$this->db->order_by('fin.ori_id','desc');
		$hasil = $this->db->get()->result_array();
		
		for ($i=0; $i<sizeof($hasil); $i++){
			$returnArray[$index]['id']			 = $hasil[$i]['id'];
			$returnArray[$index]['ori_id']		 = $hasil[$i]['ori_id'];
			$returnArray[$index]['text']		 = $hasil[$i]['text'];
			$returnArray[$index]['is_review'] 	 = $hasil[$i]['is_review'];
			$returnArray[$index]['is_positive']  = $hasil[$i]['is_positive'];
			$returnArray[$index]['yes_review']   = $hasil[$i]['yes_review'];
			$returnArray[$index]['yes_positive'] = $hasil[$i]['yes_positive'];
			$returnArray[$index]['confirmed'] 	 = $hasil[$i]['confirmed'];
			
			$index++;
		}
		
        return $returnArray;
	}
	
	/**
	* Return all tweets that has been confirmed true by admin
	* @param int $film_id
	* 
	* @return
	*/
	public function getAllTweetByMovieConfirmed($film_id){
		$index = 0;
		$returnArray[$index]['id'] = NULL;
		$returnArray[$index]['ori_id'] = NULL;
		$returnArray[$index]['text'] = NULL;
		$returnArray[$index]['is_review'] = NULL;
		$returnArray[$index]['is_positive'] = NULL;
		$returnArray[$index]['yes_review'] = NULL;
		$returnArray[$index]['yes_positive'] = NULL;
		$returnArray[$index]['confirmed'] = NULL;
		
		// get all confirmed tweets_final
		$this->db->select('fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_review, fin.is_positive as is_positive, fin.yes_review as yes_review, fin.yes_positive as yes_positive, fin.confirmed as confirmed', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$this->db->order_by('fin.ori_id','desc');
		$hasil = $this->db->get()->result_array();
		
		for ($i=0; $i<sizeof($hasil); $i++){
			$returnArray[$index]['id']			 = $hasil[$i]['id'];
			$returnArray[$index]['ori_id']		 = $hasil[$i]['ori_id'];
			$returnArray[$index]['text']		 = $hasil[$i]['text'];
			$returnArray[$index]['is_review'] 	 = $hasil[$i]['is_review'];
			$returnArray[$index]['is_positive']  = $hasil[$i]['is_positive'];
			$returnArray[$index]['yes_review']   = $hasil[$i]['yes_review'];
			$returnArray[$index]['yes_positive'] = $hasil[$i]['yes_positive'];
			$returnArray[$index]['confirmed'] 	 = $hasil[$i]['confirmed'];
			
			$index++;
		}
		
		// get old tweets and merge it with the new ones
		$this->db->select('t.id as id, t.tweet as text, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.film_id', $film_id);
		$this->db->order_by('t.timestamp', 'desc');
		$hasil = $this->db->get()->result_array();
		
		for ($i=0; $i<sizeof($hasil); $i++){
			if ($hasil[$i]['truth_rule'] == 1 && ($hasil[$i]['status'] == 1 || $hasil[$i]['status'] == 0)){
				$returnArray[$index]['ori_id']		 = NULL;
				$returnArray[$index]['is_review'] 	 = 1;
				$returnArray[$index]['yes_review'] 	 = 1;
				$returnArray[$index]['confirmed'] 	 = 1;
				$returnArray[$index]['id']			 = $hasil[$i]['id'];
				$returnArray[$index]['text']		 = $hasil[$i]['text'];				
				$returnArray[$index]['is_positive']  = $hasil[$i]['status'];
				
				if ($hasil[$i]['truth_naive'] == 1)
					$returnArray[$index]['yes_positive']  = $hasil[$i]['status'];
				else
					$returnArray[$index]['yes_positive']  = !$hasil[$i]['status'];
				
				$index++;
			}
		}
		
        return $returnArray;
	}
	
	/**
	* Return both confirmed and unconfirmed tweets
	* @param int $film_id
	* 
	* @return
	*/
	public function getAllTweetByMovie($film_id){
		$index = 0;
		$returnArray[$index]['id'] = NULL;
		$returnArray[$index]['ori_id'] = NULL;
		$returnArray[$index]['text'] = NULL;
		$returnArray[$index]['is_review'] = NULL;
		$returnArray[$index]['is_positive'] = NULL;
		$returnArray[$index]['yes_review'] = NULL;
		$returnArray[$index]['yes_positive'] = NULL;
		$returnArray[$index]['confirmed'] = NULL;
		
		// get all unconfirmed tweets_final
		$this->db->select('fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_review, fin.is_positive as is_positive, fin.yes_review as yes_review, fin.yes_positive as yes_positive, fin.confirmed as confirmed', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.confirmed', 0);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$this->db->order_by('fin.ori_id','desc');
		$hasil = $this->db->get()->result_array();
		
		for ($i=0; $i<sizeof($hasil); $i++){
			$returnArray[$index]['id']			 = $hasil[$i]['id'];
			$returnArray[$index]['ori_id']		 = $hasil[$i]['ori_id'];
			$returnArray[$index]['text']		 = $hasil[$i]['text'];
			$returnArray[$index]['is_review'] 	 = $hasil[$i]['is_review'];
			$returnArray[$index]['is_positive']  = $hasil[$i]['is_positive'];
			$returnArray[$index]['yes_review']   = $hasil[$i]['yes_review'];
			$returnArray[$index]['yes_positive'] = $hasil[$i]['yes_positive'];
			$returnArray[$index]['confirmed'] 	 = $hasil[$i]['confirmed'];
			
			$index++;
		}
		
		// get all confirmed tweets_final
		$this->db->select('fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_review, fin.is_positive as is_positive, fin.yes_review as yes_review, fin.yes_positive as yes_positive, fin.confirmed as confirmed', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$this->db->order_by('fin.ori_id','desc');
		$hasil = $this->db->get()->result_array();
		
		for ($i=0; $i<sizeof($hasil); $i++){
			$returnArray[$index]['id']			 = $hasil[$i]['id'];
			$returnArray[$index]['ori_id']		 = $hasil[$i]['ori_id'];
			$returnArray[$index]['text']		 = $hasil[$i]['text'];
			$returnArray[$index]['is_review'] 	 = $hasil[$i]['is_review'];
			$returnArray[$index]['is_positive']  = $hasil[$i]['is_positive'];
			$returnArray[$index]['yes_review']   = $hasil[$i]['yes_review'];
			$returnArray[$index]['yes_positive'] = $hasil[$i]['yes_positive'];
			$returnArray[$index]['confirmed'] 	 = $hasil[$i]['confirmed'];
			
			$index++;
		}
		
		// get old tweets and merge it with the new ones
		$this->db->select('t.id as id, t.tweet as text, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.film_id', $film_id);
		$this->db->order_by('t.timestamp', 'desc');
		$hasil = $this->db->get()->result_array();
		
		for ($i=0; $i<sizeof($hasil); $i++){
			if ($hasil[$i]['truth_rule'] == 1 && ($hasil[$i]['status'] == 1 || $hasil[$i]['status'] == 0)){
				$returnArray[$index]['ori_id']		 = NULL;
				$returnArray[$index]['is_review'] 	 = 1;
				$returnArray[$index]['yes_review'] 	 = 1;
				$returnArray[$index]['confirmed'] 	 = 1;
				$returnArray[$index]['id']			 = $hasil[$i]['id'];
				$returnArray[$index]['text']		 = $hasil[$i]['text'];				
				$returnArray[$index]['is_positive']  = $hasil[$i]['status'];
				
				if ($hasil[$i]['truth_naive'] == 1)
					$returnArray[$index]['yes_positive']  = $hasil[$i]['status'];
				else
					$returnArray[$index]['yes_positive']  = !$hasil[$i]['status'];
				
				$index++;
			}
		}
		
        return $returnArray;
	}
	
	public function getAllUncheckedTweet(){
		$returnArray[0]['title'] = NULL;
		$returnArray[0]['id'] = NULL;
		$returnArray[0]['ori_id'] = NULL;
		$returnArray[0]['film_id'] = NULL;
		$returnArray[0]['title'] = NULL;
		$returnArray[0]['text'] = NULL;
		$returnArray[0]['singkatan_text'] = NULL;
		$returnArray[0]['singkatan_intersect'] = NULL;
		$returnArray[0]['regex'] = NULL;
		$returnArray[0]['lexicon'] = NULL;
		$returnArray[0]['final_text'] = NULL;
		$returnArray[0]['is_review'] = NULL;
		$returnArray[0]['is_positive'] = NULL;
		$returnArray[0]['yes_review'] = NULL;
		$returnArray[0]['yes_positive'] = NULL;
		$returnArray[0]['confirmed'] = NULL;
		
		// get all unconfirmed tweets_final
		$this->db->where('confirmed', 0);
		$this->db->order_by('ori_id', 'desc');
		$final = $this->db->get('tweets_final')->result_array();
		for ($i=0; $i<sizeof($final); $i++){
			$returnArray[$i]['id']			 = $final[$i]['id'];
			$returnArray[$i]['ori_id']		 = $final[$i]['ori_id'];
			$returnArray[$i]['film_id']		 = $final[$i]['film_id'];
			$returnArray[$i]['final_text']	 = $final[$i]['text'];
			$returnArray[$i]['is_review'] 	 = $final[$i]['is_review'];
			$returnArray[$i]['is_positive']  = $final[$i]['is_positive'];
			$returnArray[$i]['yes_review']   = $final[$i]['yes_review'];
			$returnArray[$i]['yes_positive'] = $final[$i]['yes_positive'];
			$returnArray[$i]['persen_pos'] 	 = $final[$i]['persen_pos'];
			$returnArray[$i]['persen_neg'] 	 = $final[$i]['persen_neg'];
			$returnArray[$i]['confirmed']	 = $final[$i]['confirmed'];
			
			$this->db->where('id', $returnArray[$i]['film_id']);
			$hasil = $this->db->get('film')->result_array();
			$returnArray[$i]['title'] = $hasil[0]['title'];
			
			$hasil = $this->getTweetByOri('tweets_ori', $returnArray[$i]['ori_id']);
			$returnArray[$i]['text'] = $hasil[0]['text'];
			
			$hasil = $this->getTweetByOri('tweets_lexicon', $returnArray[$i]['ori_id']);
			$returnArray[$i]['score'] = $hasil[0]['score'];
			if ($hasil) $returnArray[$i]['lexicon'] = $hasil[0]['intersect'];
			else $returnArray[$i]['lexicon'] = ' - ';
			
			$hasil = $this->getTweetByOri('tweets_regex', $returnArray[$i]['ori_id']);
			if ($hasil) $returnArray[$i]['regex'] = $hasil[0]['text'];
			else $returnArray[$i]['regex'] = ' - ';
			
			$hasil = $this->getTweetByOri('tweets_replaced', $returnArray[$i]['ori_id']);
			if ($hasil){
				$returnArray[$i]['singkatan_intersect'] = $hasil[0]['intersect'];
				$returnArray[$i]['singkatan_text'] = $hasil[0]['text'];
			} else {
				$returnArray[$i]['singkatan_intersect'] = NULL;
				$returnArray[$i]['singkatan_text'] = ' - ';
			}
		}
		
        return $returnArray;
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function getTruePositive(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_positive as is_true, fin.yes_positive as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.is_positive', 1);
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.yes_positive', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	public function getTrueNegative(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_positive as is_true, fin.yes_positive as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.is_positive', 0);
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.yes_positive', 0);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	public function getFalsePositive(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_positive as is_true, fin.yes_positive as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.is_positive', 1);
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.yes_positive', 0);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	public function getFalseNegative(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_positive as is_true, fin.yes_positive as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.is_positive', 0);
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.yes_positive', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function getTrueReview(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_true, fin.yes_review as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	public function getTrueNonReview(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_true, fin.yes_review as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 0);
		$this->db->where('fin.yes_review', 0);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	public function getFalseReview(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_true, fin.yes_review as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.yes_review', 0);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	public function getFalseNonReview(){
        $this->db->select('f.title as title, fin.id as id, fin.ori_id as ori_id, fin.text as text, fin.is_review as is_true, fin.yes_review as yes_true', FALSE); 
		$this->db->from('tweets_final as fin, film as f');
		$this->db->where('f.id = fin.film_id');
		$this->db->where('fin.is_review', 0);
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->order_by('fin.film_id','desc');
		return $this->db->get()->result_array();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	/**
	* Get both tp, tn, fp, fn from both old & new tweet
	* with param $type only accepts: tp, tn, fp, fn, tr, tnr, fr, fnr
	* @param String $type
	* 
	* @return
	*/
	public function getBoth($type){
		$index = 0;
		$returnArray[$index]['id'] = NULL;
		$returnArray[$index]['ori_id'] = NULL;
		$returnArray[$index]['title'] = NULL;
		$returnArray[$index]['text'] = NULL;
		$returnArray[$index]['is_true'] = NULL;
		$returnArray[$index]['yes_true'] = NULL;
		
		$hasil = NULL;
		if ($type == 'tp')			$hasil = $this->getTruePositive();
		else if ($type == 'tn')		$hasil = $this->getTrueNegative();
		else if ($type == 'fp')		$hasil = $this->getFalsePositive();
		else if ($type == 'fn') 	$hasil = $this->getFalseNegative();
		else if ($type == 'tr')		$hasil = $this->getTrueReview();
		else if ($type == 'tnr')	$hasil = $this->getTrueNonReview();
		else if ($type == 'fr')		$hasil = $this->getFalseReview();
		else if ($type == 'fnr')	$hasil = $this->getFalseNonReview();
			
		for ($i=0; $i<sizeof($hasil); $i++){
			$returnArray[$index]['id']			 = $hasil[$i]['id'];
			$returnArray[$index]['ori_id']		 = $hasil[$i]['ori_id'];
			$returnArray[$index]['title']		 = $hasil[$i]['title'];
			$returnArray[$index]['text']		 = $hasil[$i]['text'];
			$returnArray[$index]['is_true'] 	 = $hasil[$i]['is_true'];
			$returnArray[$index]['yes_true'] 	 = $hasil[$i]['yes_true'];
			
			$index++;
		}
		
		$hasil = NULL;
		if ($type == 'tp')			$hasil = $this->model_tweets_old->getTruePositive();
		else if ($type == 'tn')		$hasil = $this->model_tweets_old->getTrueNegative();
		else if ($type == 'fp')		$hasil = $this->model_tweets_old->getFalsePositive();
		else if ($type == 'fn') 	$hasil = $this->model_tweets_old->getFalseNegative();
		else if ($type == 'tr')		$hasil = $this->model_tweets_old->getTrueReview();
		else if ($type == 'tnr')	$hasil = $this->model_tweets_old->getTrueNonReview();
		else if ($type == 'fr')		$hasil = $this->model_tweets_old->getFalseReview();
		else if ($type == 'fnr')	$hasil = $this->model_tweets_old->getFalseNonReview();
		for ($i=0; $i<sizeof($hasil); $i++){
			$returnArray[$index]['id']			 = $hasil[$i]['id'];
			$returnArray[$index]['ori_id']		 = NULL;
			$returnArray[$index]['title']		 = $hasil[$i]['title'];
			$returnArray[$index]['text']		 = $hasil[$i]['tweet'];
			$returnArray[$index]['is_true'] 	 = $hasil[$i]['status'];
			$returnArray[$index]['yes_true'] 	 = $hasil[$i]['truth_naive'];
			
			$index++;
		}
		
        return $returnArray;
	}
	
}
?>
