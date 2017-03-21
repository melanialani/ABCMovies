<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Film.php");

Class Admin extends Film {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('model_banner');
		$this->load->model('model_film');
		$this->load->model('model_user');
	}
	
	public function masterFilm(){
		// checks if it's admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			
			// get information from form view
			$data['id'] = $this->input->post('id', TRUE);
			
			// update button on click -> go to update_film page
			if ($this->input->post('update')){
				$this->updateFilm($data['id']);
			} 
			
			// delete button on click 
			else if ($this->input->post('delete') == TRUE){ 
				$this->model_film->deleteFilm($data['id']);
				redirect('admin/masterFilm');
			}
			
			// detail button on click 
			else if ($this->input->post('detail') == TRUE){ 
				$this->detail($data['id']);
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
		// checks if it's admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			
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
				$data['poster'] = htmlspecialchars_decode($this->input->post('poster', TRUE));
				$data['trailer'] = htmlspecialchars_decode($this->input->post('trailer', TRUE));
				$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
				$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
				$data['metascore'] = $this->input->post('metascore', TRUE);
				$data['status'] = $this->input->post('status', TRUE);
				
				// insert button on click
				if ($this->input->post('insert')){
					if ($this->model_film->insertFilm($data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
							$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
							$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['status'])) 
						
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
		// checks if it's admin
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
			
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
				$data['poster'] = htmlspecialchars_decode($this->input->post('poster', TRUE));
				$data['trailer'] = htmlspecialchars_decode($this->input->post('trailer', TRUE));
				$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
				$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
				$data['metascore'] = $this->input->post('metascore', TRUE);
				$data['status'] = $this->input->post('status', TRUE);
				
				// update the movie
				if ($this->model_film->updateFilm($data['id'],$data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
					$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
					$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['status'])) 
					
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
		// is it admin?
		if ($this->model_user->is_admin($this->input->cookie('abcmovies'))){
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
	
	public function checkNewComingSoonMovies(){
		$url = "http://www.21cineplex.com/comingsoon/";
		$timeout = 5;
		
		// create a new cURL resource
		$ch = curl_init(); 
		
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		
		// grab URL and pass it to the browser
		$data = curl_exec($ch); // at this point, $data = a whole webpage source of $url
		
		// close cURL resource, and free up system resources
		curl_close($ch);
		
		// explode string $data to get the inside of var pdata
		$data = explode('pdata=[', $data);
		$data = explode('];', $data[1]);
		$data = "[".$data[0]."]"; // valid json
		
		// explode string $data to divide each movie
		$data = explode('{', $data);
		
		// explode string again to get the information we want
		for ($i=1; $i<sizeof($data); $i++){
			$info = explode('movieTitle":"', $data[$i]);
			$info = explode('","', $info[1]);
			
			// so if the movie title has any (..) in it, we dismiss it
			if (strpos($info[0], '(') == FALSE){
				$getdata['title'] = $info[0];
				
				$info = explode('movieSinopsis":"', $data[$i]);
				$info = explode('","', $info[1]);
				$getdata['summary'] = $info[0];
				
				$info = explode('movieImage":"', $data[$i]);
				$info = explode('.', $info[1]);
				$getdata['poster'] = $info[0];
				
				// check with all coming soon movies
				$moviesinDB = $this->model_film->getComingSoonMovies();
				$alreadyinDB = FALSE;
				for ($j=0; $j<sizeof($moviesinDB); $j++){
					if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
						$alreadyinDB = TRUE;
						break;
					}
				}
				if (!$alreadyinDB){ // check with unchecked coming soon movies
					$moviesinDB = $this->model_film->getUncheckedComingSoonMovies();
					for ($j=0; $j<sizeof($moviesinDB); $j++){
						if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
							$alreadyinDB = TRUE;
							break;
						}
					}
				}
				if (!$alreadyinDB){ // add it to database
					// get movie's information from omdb api
					$url = 'http://www.omdbapi.com/?t='.str_replace(" ", "+", $getdata['title']).'&plot=full';
					$json = file_get_contents($url);
					$omdb = json_decode($json);
					//echo "<pre>"; print_r($omdb); echo "</pre><br/>";
					
					if ($omdb->Response == "True"){
						$getdata['genre'] = $omdb->Genre;
						$getdata['year'] = $omdb->Year;
						$getdata['playing_date'] = date("Y-m-d", strtotime($omdb->Released));
						$getdata['length'] = $omdb->Runtime;
						$getdata['director'] = $omdb->Director;
						$getdata['writer'] = $omdb->Writer;
						$getdata['actors'] = $omdb->Actors;
						$getdata['poster'] = htmlspecialchars_decode($omdb->Poster);
						$getdata['imdb_id'] = $omdb->imdbID;
						$getdata['imdb_rating'] = $omdb->imdbRating;
						$getdata['metascore'] = $omdb->Metascore;
					}
					
					$getdata['trailer'] = NULL;
					$getdata['status'] = 3;
					
					$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],$getdata['writer'],
							$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],$getdata['status']);
				}
			}
		}
		
	}

	public function checkNewNowPlayingMovies(){
		$url = 'http://ibacor.com/api/jadwal-bioskop?id=10&k=f4c162ca97099db0ce393fd06328ebad';
		$json = file_get_contents($url);
		$ibacor = json_decode($json);
		//echo "<pre>"; print_r($ibacor); echo "</pre><br/>";
		
		if ($ibacor->status == "success"){
			for ($i=0; $i<sizeof($ibacor->data); $i++){
				$getdata['title'] = $ibacor->data[$i]->movie;
				
				// so if the movie title has any (..) in it, we dismiss it
				if (strpos($getdata['title'], '(') == FALSE){ 												
					// check with all now playing movies
					$moviesinDB = $this->model_film->getOnGoingMovies();
					$alreadyinDB = FALSE;
					for ($j=0; $j<sizeof($moviesinDB); $j++){
						if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
							$alreadyinDB = TRUE;
							break;
						}
					}
					if (!$alreadyinDB){ // check with unchecked now playing movies
						$moviesinDB = $this->model_film->getUncheckedNowPlayingMovies();						
						for ($j=0; $j<sizeof($moviesinDB); $j++){
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
								$alreadyinDB = TRUE;
								break;
							}
						}
					}
					$isComingSoon = FALSE;
					$isComingSoon_id = 0;
					if (!$alreadyinDB){ // check with coming soon movies										
						$moviesinDB = $this->model_film->getUncheckedNowPlayingMovies();					
						for ($j=0; $j<sizeof($moviesinDB); $j++){
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
								$alreadyinDB = TRUE;
								$isComingSoon = TRUE;
								$isComingSoon_id = $moviesinDB[$j]['id'];
								break;
							}
						}
					}
					if (!$alreadyinDB && !$isComingSoon){ // check with unchecked coming soon movies			
						$moviesinDB = $this->model_film->getUncheckedNowPlayingMovies();						
						for ($j=0; $j<sizeof($moviesinDB); $j++){		
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){			
								$alreadyinDB = TRUE;
								$isComingSoon = TRUE;
								$isComingSoon_id = $moviesinDB[$j]['id'];
								break;
							}
						}
					}
					if (!$alreadyinDB){ // add it to database
						// get movie's information from omdb api
						$url = 'http://www.omdbapi.com/?t='.str_replace(" ", "+", $getdata['title']).'&plot=full';					
						$json = file_get_contents($url);
						$omdb = json_decode($json);
						//echo "<pre>"; print_r($omdb); echo "</pre><br/>";
						
						if ($omdb->Response == "True"){																					
							$getdata['summary'] = $omdb->Plot;
							$getdata['year'] = $omdb->Year;
							$getdata['playing_date'] = date("Y-m-d", strtotime($omdb->Released));
							$getdata['director'] = $omdb->Director;
							$getdata['writer'] = $omdb->Writer;
							$getdata['actors'] = $omdb->Actors;
							$getdata['poster'] = htmlspecialchars_decode($omdb->Poster);
							$getdata['imdb_id'] = $omdb->imdbID;
							$getdata['imdb_rating'] = $omdb->imdbRating;
							$getdata['metascore'] = $omdb->Metascore;
						} else {
							$getdata['summary'] = null;
							$getdata['year'] =null;
							$getdata['playing_date'] = null;
							$getdata['director'] = null;
							$getdata['writer'] = null;
							$getdata['actors'] = null;
							$getdata['poster'] = null;
							$getdata['imdb_id'] = null;
							$getdata['imdb_rating'] = null;
							$getdata['metascore'] = null;
						}
						
						$getdata['title'] = $ibacor->data[$i]->movie;
						$getdata['genre'] = $ibacor->data[$i]->genre;	
						$getdata['length'] = $ibacor->data[$i]->duration;	
						$getdata['trailer'] = NULL;
						$getdata['status'] = 4;
						
						if (!$isComingSoon){ // not in database as coming soon, insert it as new
							$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
								$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],$getdata['status']);
						} else {
							// already in database as coming soon, just change status
							$this->model_film->updateStatusFilm($isComingSoon_id, 1);
						}
					} // end of insert/update new movie to db
				} // end of checking if the new movie's title contains (..)
			} // end of for
		} // end of status ibacor = success
	}
	
}
?>