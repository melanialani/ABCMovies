<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Film extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('model_banner');
		$this->load->model('model_film');
		$this->load->model('model_review');
		$this->load->model('model_user');
	}
	
	public function index(){
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

	public function now(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->model_film->getOnGoingMovies();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detail($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('now', $data);
			$this->load->view('includes/footer');
		}
	}
	
	public function soon(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->model_film->getComingSoonMovies();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detail($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('soon', $data);
			$this->load->view('includes/footer');
		}
	}

	public function old(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->model_film->getOldMovies();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detail($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('old', $data);
			$this->load->view('includes/footer');
		}
	}

	public function insertReview($id = NULL){
		// checks if user has logged in
		if ($this->input->cookie('abcmovies')){
			
			// button save on click
			if ($this->input->post('save')){
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
		// checks if user has logged in
		if ($this->input->cookie('abcmovies')){
			
			// button save on click
			if ($this->input->post('save')){
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