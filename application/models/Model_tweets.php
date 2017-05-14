<?php

Class Model_tweets extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

	/**
	* Get all tweets of all movies
	* From table tweets
	* @return
	*/
    public function getAllTweets(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->order_by('f.title');
		$this->db->order_by('t.timestamp', 'desc');
		return $this->db->get()->result_array();
	}
	
	/**
	* Get all tweets for a spesific movie (by film_id)
	* From table tweets
	* @param int $film_id
	* 
	* @return
	*/
	public function getAllMovieTweets($film_id){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.film_id', $film_id);
		$this->db->order_by('t.timestamp', 'desc');
		return $this->db->get()->result_array();
	}
	
	/**
	* Get a spesific information about of a tweet (by id)
	* From table tweets
	* @param int $id
	* 
	* @return
	*/
	public function getTweet($id){
		$this->db->where('id', $id);
		return $this->db->get('tweets')->result_array();
	}
	
	/**
	* Insert a tweet into table tweets
	* @param int $film_id
	* @param string $tweet
	* @param boolean $status
	* 
	* @return
	*/
	public function insertTweet($film_id, $tweet, $status){
        $myArr = array(
			'film_id' 	=> $film_id,
        	'tweet' 	=> $tweet,
        	'status' 	=> $status
        );

        $this->db->insert('tweets', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	* Insert a tweet non-review into table tweets
	* @param int $film_id
	* @param string $tweet
	* @param boolean $status
	* 
	* @return
	*/
	public function insertTweetButNotAReview($film_id, $tweet, $status){
        $myArr = array(
			'film_id' 		=> $film_id,
        	'tweet' 		=> $tweet,
        	'status' 		=> $status,
        	'truth_rule'	=> 0,
        	'truth_naive'	=> 0
        );

        $this->db->insert('tweets', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	* Update a tweet's status (negate it!) (by id)
	* From table tweets
	* @param int $id
	* @param boolean $status
	* 
	* @return
	*/
	public function updateStatusTweet($id, $status){
		$myArr = array(
        	'status' 	=> $status
        );
	
		$this->db->where('id', $id);
		$this->db->update('tweets', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	* Delete a tweet (by id)
	* From table tweets
	* @param int $id
	* 
	* @return
	*/
	public function deleteTweet($id){
		$this->db->where('id', $id);
		$this->db->delete('tweets');
		return $this->db->affected_rows();
	}
	
	/**
	* Check if tweet is already in database
	* From table tweets
	* @param int $film_id
	* @param string $tweet
	* 
	* @return
	*/
	public function isExist($film_id, $tweet){
		$tweets = $this->getAllMovieTweets($film_id);
		
		$result = 0;
		for ($i=0; $i<sizeof($tweets); $i++){
			if (mb_strtolower($tweets[$i]['tweet']) == mb_strtolower($tweet)){
				$result = 1;
				break;
			}
		}
		
		return $result;
	}
	
	/**
	* Negate the ground truth about a rule-based system (by id)
	* From table tweets
	* @param int $id
	* @param boolean $truth_rule
	* 
	* @return
	*/
	public function updateTruthRuleTweet($id, $truth_rule){
		$myArr = array(
        	'truth_rule' 	=> $truth_rule,
        	'status' 		=> 1,
        	'truth_naive' 	=> 0
        );
	
		$this->db->where('id', $id);
		$this->db->update('tweets', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	* Negate the ground truth about a naive bayes result (by id)
	* From table tweets
	* @param int $id
	* @param boolean $truth_naive
	* 
	* @return
	*/
	public function updateTruthNaiveTweet($id, $truth_naive){
		$myArr = array(
        	'truth_naive' 	=> $truth_naive
        );
	
		$this->db->where('id', $id);
		$this->db->update('tweets', $myArr);
	
		return $this->db->affected_rows();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function getGroundTruthAll(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->order_by('t.id');
		$this->db->limit(2000, 1); // limit 2000 data id
		return $this->db->get()->result_array();
	}
	
	public function getGroundTruthReview(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->order_by('t.id');
		$this->db->limit(1000, 1); // limit 1000 data id
		return $this->db->get()->result_array();
	}
	
	public function getGroundTruthNotReview(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 0);
		$this->db->order_by('t.id');
		$this->db->limit(1000, 1); // limit 1000 data id
		return $this->db->get()->result_array();
	}
	
	public function getGroundTruthPositive(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.truth_naive', 1);
		$this->db->order_by('t.id');
		$this->db->limit(500, 1); // limit 500 data id
		return $this->db->get()->result_array();
	}
	
	public function getGroundTruthNegative(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.truth_naive', 0);
		$this->db->order_by('t.id');
		$this->db->limit(500, 1); // limit 500 data id
		return $this->db->get()->result_array();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	public function getTruePositive(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.status', 1);
		$this->db->where('t.truth_naive', 1);
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getTrueNegative(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.status', 0);
		$this->db->where('t.truth_naive', 0);
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getFalsePositive(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.status', 1);
		$this->db->where('t.truth_naive', 0);
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getFalseNegative(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.status', 0);
		$this->db->where('t.truth_naive', 1);
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getTrueReview(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('(t.status = 1 OR t.status = 0)');
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getTrueNonReview(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 0);
		$this->db->where('t.status', 2);
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getFalseReview(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 0);
		$this->db->where('(t.status = 1 OR t.status = 0)');
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	public function getFalseNonReview(){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status, t.truth_rule as truth_rule, t.truth_naive as truth_naive', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('t.truth_rule', 1);
		$this->db->where('t.status', 2);
		$this->db->order_by('t.id');
		return $this->db->get()->result_array();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	
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
	* Insert into table tweets_regex (step 4)
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
	* Insert into table tweets_lexicon (step 5)
	* @param int $twitter_id
	* @param string $intersect
	* 
	* @return
	*/
	public function insertTweetLexicon($twitter_id, $intersect){
        $myArr = array(
			'ori_id' 		=> $twitter_id,
			'intersect' 	=> $intersect
		);
		$this->db->insert('tweets_lexicon', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Insert tweet into 2 diffenret tables
	* with value of $table_name only accept 2 values: 'tweets_replaced' or 'tweets_stopword'
	* @param string $table_name
	* @param int $twitter_id
	* @param string $text
	* @param string $intersect
	* 
	* @return
	*/
	public function insertTweetRS($table_name, $twitter_id, $text, $intersect){
        if ($table_name == 'tweets_replaced' || $table_name == 'tweets_stopword'){
			$myArr = array(
				'ori_id' 		=> $twitter_id,
				'text' 			=> $text,
				'intersect' 	=> $intersect
			);
			$this->db->insert($table_name, $myArr);
			return $this->db->affected_rows();
		} else
			return false;
	}
	
	/**
	* Insert into table tweets_final
	* @param int $twitter_id
	* @param string $text
	* @param boolean $is_review
	* @param boolean $is_positive
	* 
	* @return
	*/
	public function insertTweetFinal($twitter_id, $text, $is_review, $is_positive){
        $myArr = array(
        	'ori_id' 		=> $twitter_id,
        	'text' 			=> $text,
        	'is_review' 	=> $is_review,
        	'is_positive' 	=> $is_positive
        );
        $this->db->insert('tweets_final', $myArr);
		return $this->db->affected_rows();
	}
	
	/**
	* Get value of a certain tweets from 6 different tables (by id)
	* with value of $table_name only accept 6 values: 'tweets_ori', tweets_replaced', 'tweets_stopword', 'tweets_regex', 'tweets_lexicon', and 'tweets_final'
	* @param string $table_name
	* @param int $id
	* 
	* @return
	*/
	public function getTweetFORSL($table_name, $id){
		$this->db->where('id', $id);
		return $this->db->get($table_name)->result_array();
	}
	
	/**
	* Get value of a certain tweets from 5 different tables (by twitter_id)
	* with value of $table_name only accept 5 values: 'tweets_replaced', 'tweets_stopword', 'tweets_regex', 'tweets_lexicon', and 'tweets_final'
	* @param string $table_name
	* @param int $twitter_id
	* 
	* @return
	*/
	public function getTweetFRSLbyOri($table_name, $twitter_id){
		$this->db->where('ori_id', $twitter_id);
		return $this->db->get($table_name)->result_array();
	}
	
	/**
	* Get a value of a certain tweets from table tweets_ori
	* @param int $twitter_id
	* 
	* @return
	*/
	public function getTweetOri($twitter_id){
		$this->db->where('twitter_id', $twitter_id);
		return $this->db->get('tweets_ori')->result_array();
	}
	
	/**
	* Update value of yes_review in table tweets_final
	* @param int $id
	* @param boolean $yes_review
	* 
	* @return
	*/
	public function updateReviewTweetFinal($id, $yes_review){
		$myArr = array( 'yes_review' => $yes_review );
		$this->db->where('id', $id);
		$this->db->update('tweets_final', $myArr);
		$this->updateConfirmedTweetFinal($id);
		return $this->db->affected_rows();
	}
	
	/**
	* Update value of yes_positive in table tweets_final
	* @param int $id
	* @param boolean $yes_positive
	* 
	* @return
	*/
	public function updatePositiveTweetFinal($id, $yes_positive){
		$myArr = array( 'yes_positive' => $yes_positive );
		$this->db->where('id', $id);
		$this->db->update('tweets_final', $myArr);
		$this->updateConfirmedTweetFinal($id);
		return $this->db->affected_rows();
	}
	
	/**
	* Update value of confirmed in table tweets_final
	* @param int $id
	* 
	* @return
	*/
	public function updateConfirmedTweetFinal($id){
		$check = $this->getTweetFORSL('tweets_final', $id);
		if ($check[0]['yes_review'] != null && $check[0]['yes_positive'] != null) {
			$myArr = array( 'confirmed' => 1 );
			$this->db->where('id', $id);
			$this->db->update('tweets_final', $myArr);
		}
	}
	
	public function getAllUncheckedTweet(){
		$returnArray[0]['title'] = NULL;
		$returnArray[0]['id'] = NULL;
		$returnArray[0]['ori_id'] = NULL;
		$returnArray[0]['text'] = NULL;
		$returnArray[0]['alay_text'] = NULL;
		$returnArray[0]['alay_intersect'] = NULL;
		$returnArray[0]['stop_text'] = NULL;
		$returnArray[0]['stop_intersect'] = NULL;
		$returnArray[0]['regex'] = NULL;
		$returnArray[0]['lexicon'] = NULL;
		$returnArray[0]['final_text'] = NULL;
		$returnArray[0]['is_review'] = NULL;
		$returnArray[0]['is_positive'] = NULL;
		$returnArray[0]['yes_review'] = NULL;
		$returnArray[0]['yes_positive'] = NULL;
		$returnArray[0]['confirmed'] = NULL;
		
		// get all unconfirmed tweets_final
		$this->db->limit(5);
		$this->db->where('confirmed', 0);
		$this->db->order_by('ori_id', 'desc');
		$final = $this->db->get('tweets_final')->result_array();
		for ($i=0; $i<sizeof($final); $i++){
			$returnArray[$i]['id']			 = $final[$i]['id'];
			$returnArray[$i]['ori_id']		 = $final[$i]['ori_id'];
			$returnArray[$i]['final_text']	 = $final[$i]['text'];
			$returnArray[$i]['is_review'] 	 = $final[$i]['is_review'];
			$returnArray[$i]['is_positive']  = $final[$i]['is_positive'];
			$returnArray[$i]['yes_review']   = $final[$i]['yes_review'];
			$returnArray[$i]['yes_positive'] = $final[$i]['yes_positive'];
			$returnArray[$i]['confirmed']	 = $final[$i]['confirmed'];
			
			$hasil = $this->getTweetFRSLbyOri('tweets_lexicon', $returnArray[$i]['ori_id']);
			if ($hasil) $returnArray[$i]['lexicon'] = $hasil[0]['intersect'];
			else $returnArray[$i]['lexicon'] = ' - ';
			
			$hasil = $this->getTweetFRSLbyOri('tweets_regex', $returnArray[$i]['ori_id']);
			if ($hasil) $returnArray[$i]['regex'] = $hasil[0]['text'];
			else $returnArray[$i]['regex'] = ' - ';
			
			$hasil = $this->getTweetFRSLbyOri('tweets_stopword', $returnArray[$i]['ori_id']);
			if ($hasil){
				$returnArray[$i]['stop_intersect'] = $hasil[0]['intersect'];
				$returnArray[$i]['stop_text'] = $hasil[0]['text'];
			} else {
				$returnArray[$i]['stop_intersect'] = NULL;
				$returnArray[$i]['stop_text'] = ' -> tidak terdapat stopwords ';
			}
			
			$hasil = $this->getTweetFRSLbyOri('tweets_replaced', $returnArray[$i]['ori_id']);
			if ($hasil){
				$returnArray[$i]['alay_intersect'] = $hasil[0]['intersect'];
				$returnArray[$i]['alay_text'] = $hasil[0]['text'];
			} else {
				$returnArray[$i]['alay_intersect'] = NULL;
				$returnArray[$i]['alay_text'] = ' -> tidak terdapat kata alay ';
			}
			
			$hasil = $this->getTweetOri($returnArray[$i]['ori_id']);
			$returnArray[$i]['text'] = $hasil[0]['text'];
			
			$this->db->where('id', $hasil[0]['film_id']);
			$hasil = $this->db->get('film')->result_array();
			$returnArray[$i]['title'] = $hasil[0]['title'];
		}
		
        return $returnArray;
	}
	
	/**
	* Count how many negative tweets there are about a film (by film_id)
	* From table tweets + table tweets_final
	* @param int $film_id
	* 
	* @return
	*/
	public function getMovieCountNegTweet($film_id){
		$sum = 0;
		
		$this->db->select('COUNT(id) as negative', FALSE); 
		$this->db->where('truth_rule', 1);
		$this->db->where('truth_naive', 0);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets')->row_array();
		$hasil = $hasil['negative'];
		$sum += $hasil;
		
		$this->db->select('COUNT(fin.id) as negative', FALSE); 
		$this->db->from('tweets_final as fin, film as f, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('ori.film_id = f.id');
		$this->db->where('is_review', 1);
		$this->db->where('is_positive', 0);
		$this->db->where('confirmed', 0);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets_final')->row_array();
		$hasil = $hasil['negative'];
		$sum += $hasil;
		
		$this->db->select('COUNT(fin.id) as negative', FALSE); 
		$this->db->from('tweets_final as fin, film as f, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('ori.film_id = f.id');
		$this->db->where('yes_review', 1);
		$this->db->where('yes_positive', 0);
		$this->db->where('confirmed', 1);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets_final')->row_array();
		$hasil = $hasil['negative'];
		$sum += $hasil;
		
		return $sum;
	}
	
	/**
	* Count how many positive tweets there are about a film (by film_id)
	* From table tweets + table tweets_final
	* @param int $film_id
	* 
	* @return
	*/
	public function getMovieCountPosTweet($film_id){
		$sum = 0;
		
		$this->db->select('COUNT(id) as positive', FALSE); 
		$this->db->where('truth_rule', 1);
		$this->db->where('truth_naive', 1);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets')->row_array();
		$hasil = $hasil['positive'];
		$sum += $hasil;
		
		$this->db->select('COUNT(fin.id) as positive', FALSE); 
		$this->db->from('tweets_final as fin, film as f, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('ori.film_id = f.id');
		$this->db->where('is_review', 1);
		$this->db->where('is_positive', 1);
		$this->db->where('confirmed', 0);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets_final')->row_array();
		$hasil = $hasil['positive'];
		$sum += $hasil;
		
		$this->db->select('COUNT(fin.id) as positive', FALSE); 
		$this->db->from('tweets_final as fin, film as f, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('ori.film_id = f.id');
		$this->db->where('yes_review', 1);
		$this->db->where('yes_positive', 1);
		$this->db->where('confirmed', 1);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets_final')->row_array();
		$hasil = $hasil['positive'];
		$sum += $hasil;
		
		return $sum;
	}
	
}
?>
