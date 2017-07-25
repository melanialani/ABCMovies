<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (dirname(__FILE__) . "/WebSystem.php");

Class Film extends WebSystem {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->checkNewMovies();
		
		// check if admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))) $data['is_admin'] = TRUE;
		else $data['is_admin'] = FALSE;
		
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->model_film->getOnGoingMovies();
		$data['banners'] = $this->model_banner->getAllActiveBanner();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detail($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('index', $data);
			$this->load->view('includes/footer');
		}
	}
	
	public function detail($id = NULL){
		// check if admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))) $data['is_admin'] = TRUE;
		else $data['is_admin'] = FALSE;
		
		// insert button on click -> go to insert_review page
		if ($this->input->post('insert')){
			$this->insertReview($this->input->post('id', TRUE));
		} 
		// update button on click -> go to update_review page
		else if ($this->input->post('update') == TRUE){ 
			$this->updateReview($this->input->post('id', TRUE),$this->input->post('film_id', TRUE));
		}
		// load page as usual
		else {
			//fetch user's name
			if ($this->input->cookie('abcmovies')){
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
			} else $data['name'] = null;
			
			// extract id film with explode or from session
			if ($id == NULL) $id = $this->input->cookie('abcmovies_movie_id');
			else {
				$temp = explode('-', $id);
				$id = $temp[0];
			}
			
			// get film's informations
			$dataFilm = $this->model_film->getFilm($id);
			$data['id'] = $dataFilm[0]['id'];
			$data['title'] = $dataFilm[0]['title'];
			$data['summary'] = $dataFilm[0]['summary'];
			$data['genre'] = $dataFilm[0]['genre'];
			$data['year'] = $dataFilm[0]['year'];
			$data['playing_date'] = $dataFilm[0]['playing_date'];
			$data['length'] = $dataFilm[0]['length'];
			$data['director'] = $dataFilm[0]['director'];
			$data['writer'] = $dataFilm[0]['writer'];
			$data['actors'] = $dataFilm[0]['actors'];
			$data['poster'] = $dataFilm[0]['poster'];
			$data['trailer'] = $dataFilm[0]['trailer'];
			$data['imdb_id'] = $dataFilm[0]['imdb_id'];
			$data['imdb_rating'] = $dataFilm[0]['imdb_rating'];
			$data['metascore'] = $dataFilm[0]['metascore'];
			$data['twitter_positif'] = $dataFilm[0]['twitter_positif'];
			$data['twitter_negatif'] = $dataFilm[0]['twitter_negatif'];
			$data['rating'] = $dataFilm[0]['rating'];
			$data['status'] = $dataFilm[0]['status'];
			
			// fetch reviews
			$data['reviews'] = $this->model_review->getAllReviewFilm($data['id']);
			
			$this->load->view('includes/header', $data);
			$this->load->view('detail', $data);
			$this->load->view('includes/footer');	
		}
	}

	public function catalog($time){
		// check if admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))) $data['is_admin'] = TRUE;
		else $data['is_admin'] = FALSE;
		
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		if ($time == 'now'){
			$data['movies'] = $this->model_film->getOnGoingMovies();
			$data['title'] = 'Now Playing';
		} else if ($time == 'soon'){
			$data['movies'] = $this->model_film->getComingSoonMovies();
			$data['title'] = 'Coming Soon';
		} else if ($time == 'old'){
			$data['movies'] = $this->model_film->getOldMovies();
			$data['title'] = 'Not Playing Anymore';
		}
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detail($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('catalog', $data);
			$this->load->view('includes/footer');
		}
	}
	
	public function insertReview($id = NULL){
		if ($this->input->cookie('abcmovies')){ // checks if user has logged in
			if ($this->input->post('save')){ // button save on click
				// get input from view
				$data['id'] = $this->input->post('id', TRUE);
				$data['rating'] = $this->input->post('rating', TRUE);
				$data['review'] = $this->input->post('review', TRUE);
				
				$this->model_review->insertReview($data['id'],$this->input->cookie('abcmovies'),$data['rating'],$data['review']);
				$this->model_review->calculateRating($data['id']); // re-calculate rating film
				$this->detail($data['id']); // success, go back to detail film
			}
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get film's informations
				$dataFilm = $this->model_film->getFilm($id);
				$data['id'] = $dataFilm[0]['id'];
				$data['title'] = $dataFilm[0]['title'];
				$data['summary'] = $dataFilm[0]['summary'];
				$data['genre'] = $dataFilm[0]['genre'];
				$data['year'] = $dataFilm[0]['year'];
				$data['playing_date'] = $dataFilm[0]['playing_date'];
				$data['length'] = $dataFilm[0]['length'];
				$data['director'] = $dataFilm[0]['director'];
				$data['writer'] = $dataFilm[0]['writer'];
				$data['actors'] = $dataFilm[0]['actors'];
				$data['poster'] = $dataFilm[0]['poster'];
				$data['trailer'] = $dataFilm[0]['trailer'];
				$data['imdb_id'] = $dataFilm[0]['imdb_id'];
				$data['imdb_rating'] = $dataFilm[0]['imdb_rating'];
				$data['metascore'] = $dataFilm[0]['metascore'];
				$data['twitter_positif'] = $dataFilm[0]['twitter_positif'];
				$data['twitter_negatif'] = $dataFilm[0]['twitter_negatif'];
				$data['rating'] = $dataFilm[0]['rating'];
				$data['status'] = $dataFilm[0]['status'];
				
				// check if admin
				if ($this->model_user->is_admin($this->input->cookie('abcmovies'))) $data['is_admin'] = TRUE;
				else $data['is_admin'] = FALSE;
				
				$this->load->view('includes/header', $data);
				$this->load->view('review/insert_review', $data);
				$this->load->view('includes/footer');
			}
		} 
		else { // no it's not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function updateReview($id = NULL, $film_id = NULL){
		if ($this->input->cookie('abcmovies')){ // checks if user has logged in
			if ($this->input->post('save')){ // button save on click
				// get input from view
				$data['review_id'] = $this->input->post('review_id', TRUE);
				$data['review_rating'] = $this->input->post('review_rating', TRUE);
				$data['review_review'] = $this->input->post('review_review', TRUE);
				
				$this->model_review->updateReview($data['review_id'],$data['review_rating'],$data['review_review']);
				$this->model_review->calculateRating($this->input->post('film_id', TRUE)); // re-calculate rating film
				$this->detail($this->input->post('film_id', TRUE)); // success, go back to detail film
			}
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get film's information
				$dataFilm = $this->model_film->getFilm($film_id);
				$data['id'] = $dataFilm[0]['id'];
				$data['title'] = $dataFilm[0]['title'];
				$data['summary'] = $dataFilm[0]['summary'];
				$data['genre'] = $dataFilm[0]['genre'];
				$data['year'] = $dataFilm[0]['year'];
				$data['playing_date'] = $dataFilm[0]['playing_date'];
				$data['length'] = $dataFilm[0]['length'];
				$data['director'] = $dataFilm[0]['director'];
				$data['writer'] = $dataFilm[0]['writer'];
				$data['actors'] = $dataFilm[0]['actors'];
				$data['poster'] = $dataFilm[0]['poster'];
				$data['trailer'] = $dataFilm[0]['trailer'];
				$data['imdb_id'] = $dataFilm[0]['imdb_id'];
				$data['imdb_rating'] = $dataFilm[0]['imdb_rating'];
				$data['metascore'] = $dataFilm[0]['metascore'];
				$data['twitter_positif'] = $dataFilm[0]['twitter_positif'];
				$data['twitter_negatif'] = $dataFilm[0]['twitter_negatif'];
				$data['rating'] = $dataFilm[0]['rating'];
				$data['status'] = $dataFilm[0]['status'];
				
				// get review's information
				$dataReview = $this->model_review->getReview($id);
				$data['review_id'] = $dataReview[0]['id'];
				$data['review_rating'] = $dataReview[0]['rating'];
				$data['review_review'] = $dataReview[0]['review'];
				
				// check if admin
				if ($this->model_user->is_admin($this->input->cookie('abcmovies'))) $data['is_admin'] = TRUE;
				else $data['is_admin'] = FALSE;
				
				$this->load->view('includes/header', $data);
				$this->load->view('review/update_review', $data);
				$this->load->view('includes/footer');
			}
		} 
		else { // no it's not admin, go back to login page
			redirect('user/login');
		}
	}
	
}
?>