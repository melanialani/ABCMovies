
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (dirname(__FILE__) . "/Film.php");

Class Admin extends Film {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('model_banner');
		$this->load->model('model_film');
		$this->load->model('model_user');
		$this->load->model('model_tweets');
	}
	
	public function masterFilm(){
		// checks if it's admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			
			// get information from form view
			$data['id'] = $this->input->post('id', TRUE);
			
			// button clicked actions
			if ($this->input->post('update')){
				$this->updateFilm($data['id']);
			}  else if ($this->input->post('delete') == TRUE){ 
				$this->model_film->deleteFilm($data['id']);
				redirect('admin/masterFilm');
			} else if ($this->input->post('detail') == TRUE){ 
				$this->detail($data['id']);
			} else if ($this->input->post('tweets') == TRUE){ 
				set_cookie(array('name' => 'abcmovies_movie_id', 'value' => $data['id'], 'expire' => 0 ));
				redirect('admin/detailTweets');
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get information from database
				$data['movies'] = $this->model_film->getAllFilm();
				
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
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			
			if ($this->input->post('insert')){ // button save on click
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
				$data['poster'] = htmlspecialchars_decode($this->input->post('poster', TRUE));
				$data['trailer'] = htmlspecialchars_decode($this->input->post('trailer', TRUE));
				$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
				$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
				$data['metascore'] = $this->input->post('metascore', TRUE);
				$data['twitter_search'] = $this->input->post('twitter_search', TRUE);
				$data['status'] = $this->input->post('status', TRUE);
				
				// insert button on click
				if ($this->input->post('insert')){
					if ($this->model_film->insertFilm($data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
							$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
							$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['twitter_search'],$data['status'])) 
						
						// success, go to master film
						$this->masterFilm();
				}
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
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
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			
			if ($this->input->post('save')){ // button save on click
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
				$data['poster'] = htmlspecialchars_decode($this->input->post('poster', TRUE));
				$data['trailer'] = htmlspecialchars_decode($this->input->post('trailer', TRUE));
				$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
				$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
				$data['metascore'] = $this->input->post('metascore', TRUE);
				$data['twitter_search'] = $this->input->post('twitter_search', TRUE);
				$data['status'] = $this->input->post('status', TRUE);
				
				// update the movie
				if ($this->model_film->updateFilm($data['id'],$data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
					$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
					$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['twitter_search'],$data['status'])) 
					
					// success -> go back to master voucher
					redirect('admin/masterFilm');
			}
			
			// load page as usual
			else {
				$dataFilm = $this->model_film->getFilm($id);
				
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
				$data['twitter_search'] = $dataFilm[0]['twitter_search'];
				$data['rating'] = $dataFilm[0]['rating'];
				$data['status'] = $dataFilm[0]['status'];
				
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
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
	
	public function masterBanner(){
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			$data['message'] = NULL;
			
			if ($this->input->post('insert')){ // if button insert clicked
				
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
					
					if ($this->model_banner->insertBanner($data['banner_name'],$config['upload_path'].$temp['file_name'],$data['banner_status'])) 
						redirect('admin/masterBanner'); // insert success, go back to master banner
				} else {
					$data['message'] = "<div class='btn btn-danger' style='width:90%; margin-bottom:15px;'>".$this->upload->display_errors()."</div>";
				}
			} 
			
			// activate & deactivate button on click
			else if ($this->input->post('activate')){
				$this->model_banner->activateBanner($this->input->post('id', TRUE));
				redirect('admin/masterBanner');
			} else if ($this->input->post('deactivate')){
				$this->model_banner->deactivateBanner($this->input->post('id', TRUE));
				redirect('admin/masterBanner');
			} 
			
			// delete button on click 
			else if ($this->input->post('delete') == TRUE){ 
				$this->model_banner->deleteBanner($this->input->post('id', TRUE));
				redirect('admin/masterBanner');
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get information from database
				$data['banners'] = $this->model_banner->getAllBanner();
				
				$this->load->view('includes/header', $data);
				$this->load->view('admin/master_banner', $data);
			}
			
		} else { // not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function detailTweets(){
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			$data['film_id'] = $this->input->cookie('abcmovies_movie_id');
			
			// negate a tweet' status
			if ($this->input->post('negate1')){
				$this->model_tweets->updateStatusTweet($this->input->post('id', TRUE), !$this->input->post('status', TRUE));
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/detailTweets');
			} else if ($this->input->post('negate2')){
				$this->model_tweets->updateTruthRuleTweet($this->input->post('id', TRUE), !$this->input->post('truth_rule', TRUE));
				redirect('admin/detailTweets');
			} else if ($this->input->post('negate3')){
				$this->model_tweets->updateTruthNaiveTweet($this->input->post('id', TRUE), !$this->input->post('truth_naive', TRUE));
				redirect('admin/detailTweets');
			} 
			
			// delete button on click 
			else if ($this->input->post('delete') == TRUE){ 
				$this->model_tweets->deleteTweet($this->input->post('id', TRUE));
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/detailTweets');
			}
			
			// load page as usual
			else {
				//fetch user's name
				if ($this->input->cookie('abcmovies')){
					$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
					$data['name'] = $user[0]['name'];
				} else $data['name'] = null;
				
				// get information from database
				$data['tweets'] = $this->model_tweets->getAllMovieTweets($data['film_id']);
				$data['movie'] = $this->model_film->getFilm($data['film_id']);
				
				$this->load->view('includes/header', $data);
				$this->load->view('admin/detail_tweets', $data);
			}
			
		} else { // not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function report(){
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			$data['film_id'] = $this->input->cookie('abcmovies_movie_id');
			
			if ($this->input->post('negate1')){
				$this->model_tweets->updateStatusTweet($this->input->post('id', TRUE), !$this->input->post('status', TRUE));
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/detailRule');
			} else if ($this->input->post('negate2')){
				$this->model_tweets->updateTruthRuleTweet($this->input->post('id', TRUE), !$this->input->post('truth_rule', TRUE));
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/detailRule');
			} else if ($this->input->post('negate3')){
				$this->model_tweets->updateTruthNaiveTweet($this->input->post('id', TRUE), !$this->input->post('truth_naive', TRUE));
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/detailRule');
			} else if ($this->input->post('delete') == TRUE){ 
				$this->model_tweets->deleteTweet($this->input->post('id', TRUE));
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/detailRule');
			}
			
			else if ($this->input->post('true_pos')){
				$data['title'] = 'True Positive';
				$data['tweets'] = $this->model_tweets->getTruePositive();
			} else if ($this->input->post('true_neg')){
				$data['title'] = 'True Negative';
				$data['tweets'] = $this->model_tweets->getTrueNegative();
			} else if ($this->input->post('false_pos')){
				$data['title'] = 'False Positive';
				$data['tweets'] = $this->model_tweets->getFalsePositive();
			} else if ($this->input->post('false_neg')){
				$data['title'] = 'False Negative';
				$data['tweets'] = $this->model_tweets->getFalseNegative();
			} 
			
			else if ($this->input->post('true_review')){
				$data['title'] = 'True Review';
				$data['tweets'] = $this->model_tweets->getTrueReview();
			} else if ($this->input->post('true_non')){
				$data['title'] = 'True Non-Review';
				$data['tweets'] = $this->model_tweets->getTrueNonReview();
			} else if ($this->input->post('false_review')){
				$data['title'] = 'False Review';
				$data['tweets'] = $this->model_tweets->getFalseReview();
			} else if ($this->input->post('false_non')){
				$data['title'] = 'False Non-Review';
				$data['tweets'] = $this->model_tweets->getFalseNonReview();
			} 
			
			else if ($this->input->post('groundtruth') == TRUE){ 
				$data['title'] = 'All Checked Data';
				$data['tweets'] = $this->model_tweets->getGroundTruthAll();
			} else if ($this->input->post('review') == TRUE){ 
				$data['title'] = 'All Review Tweets';
				$data['tweets'] = $this->model_tweets->getGroundTruthReview();
			} else if ($this->input->post('nonreview') == TRUE){ 
				$data['title'] = 'All Non-review Tweets';
				$data['tweets'] = $this->model_tweets->getGroundTruthNotReview();
			} else if ($this->input->post('positive') == TRUE){ 
				$data['title'] = 'All Positive Reviews';
				$data['tweets'] = $this->model_tweets->getGroundTruthPositive();
			} else if ($this->input->post('negative') == TRUE){ 
				$data['title'] = 'All Negative Reviews';
				$data['tweets'] = $this->model_tweets->getGroundTruthNegative();
			} else if ($this->input->post('all') == TRUE){ 
				$data['title'] = 'All Tweets';
				$data['tweets'] = $this->model_tweets->getAllTweets();
			} 
			
			else if ($this->input->post('unchecked') == TRUE){ 
				redirect('admin/unchecked');
			}
			
			else { // load page as usual = load ground truth review
				$data['title'] = 'Laporan';
				$data['tweets'] = NULL;
			}
			
			//fetch user's name
			if ($this->input->cookie('abcmovies')){
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
			} else $data['name'] = null;
			
			// calculate accuracy, recall, precision
			$data['tp'] = sizeof($this->model_tweets->getTruePositive());
			$data['tn'] = sizeof($this->model_tweets->getTrueNegative());
			$data['fp'] = sizeof($this->model_tweets->getFalsePositive());
			$data['fn'] = sizeof($this->model_tweets->getFalseNegative());
			$data['accuracy'] = (($data['tn']+$data['tp'])*100) / ($data['tn']+$data['tp']+$data['fn']+$data['fp']);
			$data['recall_pos'] = ($data['tp']*100) / ($data['tp']+$data['fn']);
			$data['recall_neg'] = ($data['tn']*100) / ($data['tn']+$data['fp']);
			$data['precision_pos'] = ($data['tp']*100) / ($data['tn']+$data['tp']+$data['fn']+$data['fp']);
			$data['precision_neg'] = ($data['tn']*100) / ($data['tn']+$data['tp']+$data['fn']+$data['fp']);
			
			$data['review_tp'] = sizeof($this->model_tweets->getTrueReview());
			$data['review_tn'] = sizeof($this->model_tweets->getTrueNonReview());
			$data['review_fp'] = sizeof($this->model_tweets->getFalseReview());
			$data['review_fn'] = sizeof($this->model_tweets->getFalseNonReview());
			$data['review_accuracy'] = (($data['review_tn']+$data['review_tp'])*100) / ($data['review_tn']+$data['review_tp']+$data['review_fn']+$data['review_fp']);
			$data['review_recall_pos'] = ($data['review_tp']*100) / ($data['review_tp']+$data['review_fn']);
			$data['review_recall_neg'] = ($data['review_tn']*100) / ($data['review_tn']+$data['review_fp']);
			$data['review_precision_pos'] = ($data['review_tp']*100) / ($data['review_tn']+$data['review_tp']+$data['review_fn']+$data['review_fp']);
			$data['review_precision_neg'] = ($data['review_tn']*100) / ($data['review_tn']+$data['review_tp']+$data['review_fn']+$data['review_fp']);
			
			$this->load->view('includes/header', $data);
			$this->load->view('admin/laporan', $data);			
		} else { // not admin, go back to login page
			redirect('user/login');
		}
	}
	
	public function unchecked(){
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			$data['is_admin'] = TRUE;
			$data['title'] = 'Unchecked Tweets';
			if ($this->input->post('review1')){
				$this->model_tweets->updateReviewTweetFinal($this->input->post('id', TRUE), 1);
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/unchecked');
			} else if ($this->input->post('review0')){
				$this->model_tweets->updateReviewTweetFinal($this->input->post('id', TRUE), 0);
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/unchecked');
			} else if ($this->input->post('positive1')){
				$this->model_tweets->updatePositiveTweetFinal($this->input->post('id', TRUE), 1);
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/unchecked');
			} else if ($this->input->post('positive0') == TRUE){ 
				$this->model_tweets->updatePositiveTweetFinal($this->input->post('id', TRUE), 0);
				$this->model_film->updateTwitterFilm($data['film_id'], $this->model_tweets->getMovieCountNegTweet($data['film_id']), $this->model_tweets->getMovieCountPosTweet($data['film_id']));
				redirect('admin/unchecked');
			}
			
			//fetch user's name
			if ($this->input->cookie('abcmovies')){
				$user = $this->model_user->getUser($this->input->cookie('abcmovies'));
				$data['name'] = $user[0]['name'];
			} else $data['name'] = null;
			
			$data['tweets'] = $this->model_tweets->getAllUncheckedTweet();
			
			$this->load->view('includes/header', $data);
			$this->load->view('admin/steps', $data);			
		} else { // not admin, go back to login page
			redirect('user/login');
		}
	}
	
}
?>