<?php

Class Model_review extends CI_Model {

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
		$this->db->select('r.id as id, u.email as email, u.name as name, u.picture as picture, r.rating as rating, r.review as review', FALSE);
		$this->db->from('review as r, user as u');
		$this->db->where('r.email = u.email');
		$this->db->where('r.film_id', $film_id);
		$this->db->order_by('r.tanggal', 'desc');
		return $this->db->get()->result_array();
	}
	
	/**
	 * Insert Review
	 * @param int $film_id
	 * @param string $email
	 * @param int $rating
	 * @param string $review
	 */
	public function insertReview($film_id, $email, $rating, $review){
        $myArr = array(
        	'film_id' 	=> $film_id,
        	'email' 	=> $email,
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
	public function updateReview($id, $rating, $review){
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
	
	/**
	* Calculate local rating of a movie (by id)
	* @param int $id
	* 
	* @return
	*/
	public function calculateRating($id = NULL){
		$this->db->select('COUNT(rating)', FALSE); 
		$this->db->where('film_id', $id);
		$count = $this->db->get('review')->row_array();
		$count = $count['COUNT(rating)'];
		
		$this->db->select('SUM(rating)', FALSE); 
		$this->db->where('film_id', $id);
		$sum = $this->db->get('review')->row_array();
		$sum = $sum['SUM(rating)'];
		
		// update movie's rating
		if ($count > 0){
			$myArr = array('rating' => round(($sum/$count),1));
			$this->db->where('id', $id);
			$this->db->update('film', $myArr);
		}
	}
	
	/*public function getNegData(){
		$this->db->select('text', FALSE);
		$this->db->from('moviesentiment');
		$this->db->where('sentiment','neg');
		$this->db->order_by('text');
		return $this->db->get()->result_array();
	}
	
	public function getPosData(){
		$this->db->select('text', FALSE);
		$this->db->from('moviesentiment');
		$this->db->where('sentiment','pos');
		$this->db->order_by('text');
		return $this->db->get()->result_array();
	}*/
	
}
?>
