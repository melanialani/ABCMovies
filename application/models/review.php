<?php

Class Review extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllReview(){
        return $this->db->get('review')->result_array();
	}
	
	/**
	 * Returns spesific review (by id)
	 * To fill the fields in edit review
	 * @param int $id
	 */
	public function getReview($id){
		$this->db->select('f.title as title, f.poster as poster, r.id as id, r.rating as rating, r.review as review', FALSE);
		$this->db->from('review as r, film as f');
		$this->db->where('r.film_id = f.id');
		$this->db->where('r.id', $id);
		return $this->db->get()->result_array();
	}
	
	/**
	 * Returns all reviews for spesific film
	 * @param int $film_id
	 */
	public function getAllReviewFilm($film_id){
		$this->db->select('u.name as name, u.picture as picture, r.id as id, r.rating as rating, r.review as review', FALSE);
		$this->db->from('review as r, username as u');
		$this->db->where('r.username = u.username');
		$this->db->where('r.film_id', $film_id);
		$this->db->order_by('r.tanggal', 'desc');
		return $this->db->get()->result_array();
	}
	
	/**
	 * Returns all review by user
	 * @param string $username
	 */
	public function getAllReviewUser($username){
		$this->db->select('f.title as title, f.poster as poster, r.id as id, r.rating as rating, r.review as review', FALSE);
		$this->db->from('review as r, film as f');
		$this->db->where('r.film_id = f.id');
		$this->db->where('r.username', $username);
		$this->db->order_by('r.tanggal', 'desc');
		return $this->db->get()->result_array();
	}


	/**
	 * Insert Review
	 * @param int $film_id
	 * @param string $username
	 * @param int $rating
	 * @param string $review
	 */
	public function insertReview($film_id, $username, $rating, $review){
        $myArr = array(
        	'film_id' 	=> $film_id,
        	'username' 	=> $username,
        	'rating' 	=> $rating,
        	'review' 	=> $review
        );

        $this->db->insert('review', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	 * Update Review (by id)
	 * Can only update rating & review
	 * @param int $id
	 * @param int $rating
	 * @param string $review
	 */
	public function updateReview($id, $film_id, $username, $rating, $review){
		$myArr = array(
        	'rating' 	=> $rating,
        	'review' 	=> $review
        );
	
		$this->db->where('id', $id);
		$this->db->update('review', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	 * Delete review (by id)
	 * @param int $id
	 */
	public function deleteReview($id){
		$this->db->where('id', $id);
		$this->db->delete('review');
		return $this->db->affected_rows();
	}
}
?>
