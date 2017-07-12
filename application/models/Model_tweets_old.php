<?php

Class Model_tweets_old extends CI_Model {

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
	* Check if the same text exist
	* @param string $text
	* 
	* @return
	*/
	public function getTweetByText($text){
		$this->db->where('tweet', $text);
		return $this->db->get('tweets')->result_array();
	}
	
	/**
	* Insert a tweet into table tweets
	* @param int $film_id
	* @param string $tweet
	* @param boolean $status
	* @param boolean $truth_rule
	* @param boolean $truth_naive
	* 
	* @return
	*/
	public function insertTweet($film_id, $tweet, $status, $truth_rule, $truth_naive){
        $myArr = array(
			'film_id' 		=> $film_id,
        	'tweet' 		=> $tweet,
        	'status'	 	=> $status,
        	'truth_rule' 	=> $truth_rule,
        	'truth_naive' 	=> $truth_naive
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
	* Count how many negative tweets there are about a film (by film_id)
	* From table tweets + table tweets_final
	* @param int $film_id
	* 
	* @return
	*/
	public function getMovieCountNegTweet($film_id){
		$sum = 0;
		
		// count neg review from OLD twitter
		$this->db->select('COUNT(id) as negative', FALSE); 
		$this->db->where('truth_rule', 1);
		$this->db->where('truth_naive', 0);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets')->row_array();
		$hasil = $hasil['negative'];
		$sum += $hasil;
		
		// get neg review from tweets_final, NOT confirmed yet, and not a duplicate
		$this->db->select('COUNT(fin.id) as negative', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.is_positive', 0);
		$this->db->where('fin.confirmed', 0);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$hasil = $this->db->get()->row_array();
		$hasil = $hasil['negative'];
		$sum += $hasil;
		
		// get neg review from tweets_final, ALREADY confirmed by admin, and the text is not a duplicate of others
		$this->db->select('COUNT(fin.id) as negative', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.yes_positive', 0);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$hasil = $this->db->get()->row_array();
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
		
		// count pos review from OLD twitter
		$this->db->select('COUNT(id) as positive', FALSE); 
		$this->db->where('truth_rule', 1);
		$this->db->where('truth_naive', 1);
		$this->db->where('film_id', $film_id);
		$hasil = $this->db->get('tweets')->row_array();
		$hasil = $hasil['positive'];
		$sum += $hasil;
		
		// get pos review from tweets_final, NOT confirmed yet, and not a duplicate
		$this->db->select('COUNT(fin.id) as positive', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.is_review', 1);
		$this->db->where('fin.is_positive', 1);
		$this->db->where('fin.confirmed', 0);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$hasil = $this->db->get()->row_array();
		$hasil = $hasil['positive'];
		$sum += $hasil;
		
		// get pos review from tweets_final, ALREADY confirmed by admin, and the text is not a duplicate of others
		$this->db->select('COUNT(fin.id) as positive', FALSE); 
		$this->db->from('tweets_final as fin, tweets_ori as ori');
		$this->db->where('fin.ori_id = ori.twitter_id');
		$this->db->where('fin.yes_review', 1);
		$this->db->where('fin.yes_positive', 1);
		$this->db->where('fin.confirmed', 1);
		$this->db->where('fin.duplicate', 0);
		$this->db->where('ori.film_id', $film_id);
		$hasil = $this->db->get()->row_array();
		$hasil = $hasil['positive'];
		$sum += $hasil;
		
		return $sum;
	}
	
}
?>
