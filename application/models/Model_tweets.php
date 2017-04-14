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
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->order_by('f.title');
		$this->db->order_by('t.status');
		return $this->db->get()->result_array();
	}
	
	/**
	* Get all tweets for a spesific movie (by film_id)
	* @param int $film_id
	* 
	* @return
	*/
	public function getAllMovieTweets($film_id){
        $this->db->select('f.title as title, t.id as id, t.tweet as tweet, t.status as status', FALSE);
		$this->db->from('tweets as t, film as f');
		$this->db->where('t.film_id = f.id');
		$this->db->where('film_id', $film_id);
		$this->db->order_by('t.status');
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
	
}
?>
