<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Film extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Model_Film');
		$this->load->model('Model_User');
		$this->load->model('Model_Review');
		$this->load->model('Model_Banner');
	}
	
	public function index(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->Model_Film->getOnGoingMovies();
		$data['banners'] = $this->Model_Banner->getAllActiveBanner();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detailFilm($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('index', $data);
			$this->load->view('includes/footer');
		}
	}
	
	public function masterFilm(){
		// checks if it's admin
		if ($this->Model_User->is_admin($this->input->cookie('abcmovies'))){
			
			// get information from form view
			$data['id'] = $this->input->post('id', TRUE);
			
			// update button on click -> go to update_film page
			if ($this->input->post('update')){
				$this->updateFilm($data['id']);
			} 
			
			// delete button on click 
			else if ($this->input->post('delete') == TRUE){ 
				$this->Model_Film->deleteFilm($data['id']);
				redirect('film/masterFilm');
			}
			
			// detail button on click 
			else if ($this->input->post('detail') == TRUE){ 
				$this->detailFilm($data['id']);
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get information from database
				$data['movies'] = $this->Model_Film->getAllFilm();
				
				// loads views
				$this->load->view('includes/header', $data);
				$this->load->view('admin/master_film', $data);
			}
		} 
		else { // no it's not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function insertFilm(){
		// checks if it's admin
		if ($this->Model_User->is_admin($this->input->cookie('abcmovies'))){
			
			// button save on click
			if ($this->input->post('insert')){
				// get input from view
				$data['title'] = $this->input->post('title', TRUE);
				$data['summary'] = $this->input->post('summary', TRUE);
				$data['genre'] = $this->input->post('genre', TRUE);
				$data['year'] = $this->input->post('year', TRUE);
				$data['playing_date'] = date("Y-m-d", strtotime($this->input->post('playing_date', TRUE)));
				$data['length'] = $this->input->post('length', TRUE);
				$data['director'] = $this->input->post('director', TRUE);
				$data['writer'] = $this->input->post('writer', TRUE);
				$data['actors'] = $this->input->post('actors', TRUE);
				$data['poster'] = $this->input->post('poster', TRUE);
				$data['trailer'] = htmlspecialchars_decode($this->input->post('trailer', TRUE));
				$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
				$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
				$data['metascore'] = $this->input->post('metascore', TRUE);
				$data['twitter_positif'] = $this->input->post('twitter_positif', TRUE);
				$data['twitter_negatif'] = $this->input->post('twitter_negatif', TRUE);
				$data['rating'] = $this->input->post('rating', TRUE);
				$data['status'] = $this->input->post('status', TRUE);
				
				// insert button on click
				if ($this->input->post('insert')){
					if ($this->Model_Film->insertFilm($data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
							$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
							$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['twitter_positif'],$data['twitter_negatif'],
							$data['rating'],$data['status'])) 
						
						// success, go to master film
						$this->masterFilm();
				}
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				$this->load->view('includes/header', $data);
				$this->load->view('admin/insert_film', $data);
				$this->load->view('includes/footer');
			}
		} 
		else { // no it's not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function updateFilm($id = NULL){
		// checks if it's admin
		if ($this->Model_User->is_admin($this->input->cookie('abcmovies'))){
			
			// button save on click
			if ($this->input->post('save')){
				// get input from view
				$data['id'] = $this->input->post('id', TRUE);
				$data['title'] = $this->input->post('title', TRUE);
				$data['summary'] = $this->input->post('summary', TRUE);
				$data['genre'] = $this->input->post('genre', TRUE);
				$data['year'] = $this->input->post('year', TRUE);
				$data['playing_date'] = date("Y-m-d", strtotime($this->input->post('playing_date', TRUE)));
				$data['length'] = $this->input->post('length', TRUE);
				$data['director'] = $this->input->post('director', TRUE);
				$data['writer'] = $this->input->post('writer', TRUE);
				$data['actors'] = $this->input->post('actors', TRUE);
				$data['poster'] = $this->input->post('poster', TRUE);
				$data['trailer'] = htmlspecialchars_decode($this->input->post('trailer', TRUE));
				$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
				$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
				$data['metascore'] = $this->input->post('metascore', TRUE);
				$data['twitter_positif'] = $this->input->post('twitter_positif', TRUE);
				$data['twitter_negatif'] = $this->input->post('twitter_negatif', TRUE);
				$data['rating'] = $this->input->post('rating', TRUE);
				$data['status'] = $this->input->post('status', TRUE);
				
				// update the movie
				if ($this->Model_Film->updateFilm($data['id'],$data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
					$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
					$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['twitter_positif'],$data['twitter_negatif'],
					$data['rating'],$data['status'])) 
					
					// success -> go back to master voucher
					redirect('film/masterFilm');
			}
			
			// load page as usual
			else {
				$dataFilm = $this->Model_Film->getFilm($id);
				
				// pass the data into view
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
				
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				$this->load->view('includes/header', $data);
				$this->load->view('admin/update_film', $data);
				$this->load->view('includes/footer');
			}
		} 
		else { // no it's not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function detailFilm($id = NULL){
		
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
				$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
			} else $data['name'] = null;
			
			// get film's informations
			$dataFilm = $this->Model_Film->getFilm($id);
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
			
			// fetch review
			$data['reviews'] = $this->Model_Review->getAllReviewFilm($data['id']);
			
			$this->load->view('includes/header', $data);
			$this->load->view('detail', $data);
			$this->load->view('includes/footer');	
		}
	}

	public function now(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->Model_Film->getOnGoingMovies();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detailFilm($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('now', $data);
			$this->load->view('includes/footer');
		}
	}
	
	public function soon(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->Model_Film->getComingSoonMovies();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detailFilm($this->input->post('id', TRUE));
		} else { // load page as usual
			$this->load->view('includes/header', $data);
			$this->load->view('soon', $data);
			$this->load->view('includes/footer');
		}
	}

	public function old(){
		// fetch user's name
		if ($this->input->cookie('abcmovies')){
			$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
			$data['name'] = $user[0]['name'];
		} else $data['name'] = null;
		
		// get information from database
		$data['movies'] = $this->Model_Film->getOldMovies();
		
		// detail button on click 
		if ($this->input->post('detail') == TRUE){ 
			$this->detailFilm($this->input->post('id', TRUE));
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
				
				if ($this->Model_Review->insertReview($data['id'],$this->input->cookie('abcmovies'),$data['rating'],$data['review'])) {
					$this->Model_Review->calculateRating($data['id']); // re-calculate rating film
					$this->detailFilm($data['id']); // success, go back to detail film
				}
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get film's informations
				$dataFilm = $this->Model_Film->getFilm($id);
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
				
				if ($this->Model_Review->updateReview($data['review_id'],$data['review_rating'],$data['review_review'])) {
					$this->Model_Review->calculateRating($this->input->post('film_id', TRUE)); // re-calculate rating film
					$this->detailFilm($this->input->post('film_id', TRUE)); // success, go back to detail film
				}
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get film's information
				$dataFilm = $this->Model_Film->getFilm($film_id);
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
				$dataReview = $this->Model_Review->getReview($id);
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

	public function masterBanner(){
		// is it admin?
		if ($this->Model_User->is_admin($this->input->cookie('abcmovies'))){
			$data['message'] = NULL;
			
			// if button insert clicked
			if ($this->input->post('insert')){
				
				// fetch user input
				$data['banner_name'] = $this->input->post('banner_name', TRUE);
				$data['banner_status'] = $this->input->post('banner_status', TRUE);
				
				// upload photo configuration
				$config =  array(
					'upload_path'     => "./pictures/banner/",
					'allowed_types'   => "gif|jpg|png|jpeg", //gif|jpg|png|jpeg
					'overwrite'       => TRUE,
					'file_name'       => $data['name']
				);
				
				$this->upload->initialize($config);
				
				if($this->upload->do_upload('picture')){ // photo uploaded successfully
					$temp = $this->upload->data();
					
					if ($this->Model_Banner->insertBanner($data['banner_name'],$config['upload_path'].$temp['file_name'],$data['banner_status'])) 
						redirect('film/masterBanner'); // insert success, go back to master banner
				} else {
					$data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>".$this->upload->display_errors()."</div>";
				}
			} 
			
			// activate & deactivate button on click
			else if ($this->input->post('activate')){
				$this->Model_Banner->activateBanner($this->input->post('id', TRUE));
				redirect('film/masterBanner');
			} else if ($this->input->post('deactivate')){
				$this->Model_Banner->deactivateBanner($this->input->post('id', TRUE));
				redirect('film/masterBanner');
			} 
			
			// delete button on click 
			else if ($this->input->post('delete') == TRUE){ 
				$this->Model_Banner->deleteBanner($this->input->post('id', TRUE));
				redirect('film/masterBanner');
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->Model_User->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get information from database
				$data['banners'] = $this->Model_Banner->getAllBanner();
				
				$this->load->view('includes/header', $data);
				$this->load->view('admin/master_banner', $data);
			}
			
		} else { // not admin, go back to login page
			redirect('user/login');
		}
	}
	
}
?>