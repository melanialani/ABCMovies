<?php

Class Model_tweets extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

	/**
	* Get all tweets from all movies
	* 
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
	* @param int $id
	* 
	* @return
	*/
	public function getTweet($id){
		$this->db->where('id', $id);
		return $this->db->get('tweets')->result_array();
	}
	
	/**
	* Count how many negative tweets there are about a film (by film_id)
	* @param int $film_id
	* 
	* @return
	*/
	public function getMovieCountNegTweet($film_id){
		$this->db->select('COUNT(id) as negative', FALSE); 
		$this->db->where('film_id', $film_id);
		$this->db->where('status', 0);
		//$this->db->where('truth_rule', 1);
		$hasil = $this->db->get('tweets')->row_array();
		return $hasil['negative'];
	}
	
	/**
	* Count how many positive tweets there are about a film (by film_id)
	* @param int $film_id
	* 
	* @return
	*/
	public function getMovieCountPosTweet($film_id){
		$this->db->select('COUNT(id) as positive', FALSE); 
		$this->db->where('film_id', $film_id);
		$this->db->where('status', 1);
		//$this->db->where('truth_rule', 1);
		$hasil = $this->db->get('tweets')->row_array();
		return $hasil['positive'];
	}
	
	/**
	* Insert a tweet
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
	* @param int $film_id
	* @param string $tweet
	* 
	* @return
	*/
	public function isExist($film_id, $tweet){
		$tweets = $this->getAllMovieTweets($film_id);
		
		$result = 0;
		for ($i=0; $i<sizeof($tweets); $i++){
			if ($tweets[$i]['tweet'] == $tweet){
				$result = 1;
				break;
			}
		}
		
		return $result;
	}
	
	/**
	* Negate the ground truth about a rule-based system (by id)
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
	
	/**
	* Insert tweet into 3 diffenret tables
	* with value of $table_name only accept 3 values: 'tweets_ori','tweets_regex','tweets_replaced'
	* @param int $film_id
	* @param int $twitter_id
	* @param string $text
	* @param datetime $created_at
	* @param string $table_name
	* 
	* @return
	*/
	public function insertTweetInto($film_id, $twitter_id, $text, $created_at, $table_name){
        $myArr = array(
			'film_id' 		=> $film_id,
        	'twitter_id' 	=> $twitter_id,
        	'text' 			=> $text,
        	'created_at' 	=> $created_at
        );
        $this->db->insert($table_name, $myArr);
        return $this->db->affected_rows();
	}
	
	public function insertTweetFinal($film_id, $twitter_id, $text, $created_at, $system_says, $yes_review, $yes_positive){
        $myArr = array(
			'film_id' 		=> $film_id,
        	'twitter_id' 	=> $twitter_id,
        	'text' 			=> $text,
        	'created_at' 	=> $created_at
        );
        $this->db->insert('tweets_replaced', $myArr);
        return $this->db->affected_rows();
	}
}
?>
