<?php

Class Model_film extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllFilm(){
    	$this->db->order_by('playing_date', 'desc');
    	return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get spesific movie's information (by id)
	 * @param int $id
	 */
	public function getFilmById($id){
		$this->db->where('id', $id);
		return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get spesific movie's informatin (by title)
	 * @param string $title
	 */
	public function getFilmByTitle($title){
		$this->db->where('title', $title);
		return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get all 'Coming Soon' movies
	 */
	public function getComingSoonMovies(){
		$this->db->where('status', 0);
		return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get all 'Now Playing' movies
	 */
	public function getOnGoingMovies(){
		$this->db->where('status', 1);
		return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get all old movies
	 */
	public function getOldMovies(){
		$this->db->where('status', 2);
		return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get all Coming Soon movies that needs admin's approval 
	 */
	public function getUncheckedComingSoonMovies(){
		$this->db->where('status', 3);
		return $this->db->get('film')->result_array();
	}
	
	/**
	 * Get all Now Playing movies that needs admin's approval 
	 */
	public function getUncheckedNowPlayingMovies(){
		$this->db->where('status', 4);
		return $this->db->get('film')->result_array();
	}

	/**
	 * Get a movie's information
	 * @param int $id
	 */
	public function getFilm($id){
		$this->db->where('id', $id);
		return $this->db->get('film')->result_array();
	}

	/**
	* Insert film
	* @param string $title
	* @param string $summary
	* @param string $genre
	* @param int $year
	* @param datetime $playing_date
	* @param int $length
	* @param string $director
	* @param string $writer
	* @param string $actors
	* @param string $poster
	* @param string $trailer
	* @param string $imdb_id
	* @param double $imdb_rating
	* @param int $metascore
	* @param int $status
	* 
	* @return
	*/
	public function insertFilm($title, $summary, $genre, $year, $playing_date, $length, $director, $writer, $actors, $poster, $trailer, $imdb_id, $imdb_rating, $metascore, $status){
		
        $myArr = array(
        	'title' 			=> $title,
        	'summary' 			=> $summary,
			'genre' 			=> $genre,
			'year' 				=> $year,
			'playing_date' 		=> $playing_date,
			'length' 			=> $length,
			'director' 			=> $director,
			'writer' 			=> $writer,
			'actors' 			=> $actors,
			'poster' 			=> $poster,
        	'trailer' 			=> $trailer,
			'imdb_id' 			=> $imdb_id,
			'imdb_rating' 		=> $imdb_rating,
			'metascore' 		=> $metascore,
			'status' 			=> $status
        );

        $this->db->insert('film', $myArr);

        return $this->db->affected_rows();
	}
	
	/**
	* Update film (by id)
	* @param int $id
	* @param string $title
	* @param string $summary
	* @param string $genre
	* @param int $year
	* @param datetime $playing_date
	* @param int $length
	* @param string $director
	* @param string $writer
	* @param string $actors
	* @param string $poster
	* @param string $trailer
	* @param string $imdb_id
	* @param double $imdb_rating
	* @param int $metascore
	* @param int $status
	* 
	* @return
	*/
	public function updateFilm($id, $title, $summary, $genre, $year, $playing_date, $length, $director, $writer, $actors, $poster, $trailer, $imdb_id, $imdb_rating, $metascore, $status){
		
		$myArr = array(
        	'title' 			=> $title,
        	'summary' 			=> $summary,
			'genre' 			=> $genre,
			'year' 				=> $year,
			'playing_date' 		=> $playing_date,
			'length' 			=> $length,
			'director' 			=> $director,
			'writer' 			=> $writer,
			'actors' 			=> $actors,
			'poster' 			=> $poster,
			'trailer' 			=> $trailer,
			'imdb_id' 			=> $imdb_id,
			'imdb_rating' 		=> $imdb_rating,
			'metascore' 		=> $metascore,
			'status' 			=> $status
        );
	
		$this->db->where('id', $id);
		$this->db->update('film', $myArr);
	
		return $this->db->affected_rows();
	}
	
	/**
	 * Update a movie's status
	 * @param int $id
	 * @param int $status
	 */
	public function updateStatusFilm($id, $status){
		$myArr = array(
			'status' => $status
		);
		
		$this->db->where('id', $id);
		$this->db->update('film', $myArr);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Delete film (by id)
	 * @param int $id
	 */
	public function deleteFilm($id){
		$this->db->where('id', $id);
		$this->db->delete('film');
		return $this->db->affected_rows();
	}
}
?>
