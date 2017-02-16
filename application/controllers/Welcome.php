<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->masterReview();
	}
	
	public function masterUser(){
		$this->load->model('User');
		
		// global rules for form validation
		$this->form_validation->set_rules('username','Username','required');
		
		// fetch data from form
		$data['username'] = $this->input->post('username', TRUE);
		$data['password'] = $this->input->post('password', TRUE);
		$data['email'] = $this->input->post('email', TRUE);
		$data['name'] = $this->input->post('name', TRUE);
		$data['birthdate'] = date("Y-m-d", strtotime($this->input->post('birthdate', TRUE)));
		
		if ($this->input->post('insert') == TRUE){
			// other rules for form validation
			$this->form_validation->set_rules('password','Password','required');
			$this->form_validation->set_rules('email','E-mail','required');
			$this->form_validation->set_rules('name','Full name','required');
			$this->form_validation->set_rules('birthdate','Birthdate','required');
			
			if ($this->form_validation->run() == TRUE){
				// upload photo configuration
				$config =  array(
					'upload_path'     => "./pictures/",
					'allowed_types'   => "gif|jpg|png|jpeg",
					'overwrite'       => TRUE,
					'file_name'        => $data['username'],
				);
				
				$this->upload->initialize($config);
				
				if($this->upload->do_upload('profilePicture')){ // photo uploaded successfully
					$temp = $this->upload->data();
					
					if ($this->User->insertUser($data['username'],$data['password'],$data['email'],$data['name'],$data['birthdate'],$config['upload_path'].$temp['file_name'])) 
						$data['conf'] = "Insert berhasil";
					else $data['conf'] = "Insert gagal";
				} else {
					$data['conf'] = $this->upload->display_errors();
				}
			} else {
				$data['conf'] = "All fields required";
			}
		} 
		else if ($this->input->post('update') == TRUE){
			if ($this->form_validation->run() == TRUE){
				// upload photo configuration
				$config =  array(
					'upload_path'     => "./pictures/",
					'allowed_types'   => "gif|jpg|png|jpeg",
					'overwrite'       => TRUE,
					'file_name'        => $data['username'],
				);
				
				$this->upload->initialize($config);
				
				if($this->upload->do_upload('profilePicture')){ // photo uploaded successfully
					$temp = $this->upload->data();
					
					if ($this->User->updateUser($data['username'],$data['password'],$data['name'],$data['birthdate'],$config['upload_path'].$temp['file_name'])) 
						$data['conf'] = "Update berhasil";
					else $data['conf'] = "Update gagal";
				} else {
					$data['conf'] = $this->upload->display_errors();
				}
			} else {
				$data['conf'] = "Username is required";
			}
		}
		else { // jika form pertama kali di load -> set nilai default
			$data = NULL; $data['conf'] = "";
		}
		
		$data['b_url'] = base_url();
		$data['users'] = $this->User->getAllUser();
		
		//$this->load->view('navigation'); 
		$this->load->view('crud/user', $data);
	}

	public function masterFilm(){
		$this->load->model('Film');
		
		// fetch data from form
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
		$data['trailer'] = $this->input->post('trailer', TRUE);
		$data['imdb_id'] = $this->input->post('imdb_id', TRUE);
		$data['imdb_rating'] = $this->input->post('imdb_rating', TRUE);
		$data['metascore'] = $this->input->post('metascore', TRUE);
		$data['twitter_positif'] = $this->input->post('twitter_positif', TRUE);
		$data['twitter_negatif'] = $this->input->post('twitter_negatif', TRUE);
		$data['rating'] = $this->input->post('rating', TRUE);
		$data['status'] = $this->input->post('status', TRUE);
		
		if ($this->input->post('insert') == TRUE){
			// rules for form validation
			$this->form_validation->set_rules('title','Title','required');
			$this->form_validation->set_rules('summary','Summary','required');
			$this->form_validation->set_rules('status','Status','required');
			
			if ($this->form_validation->run() == TRUE){
				if ($this->Film->insertFilm($data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
					$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
					$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['twitter_positif'],$data['twitter_negatif'],
					$data['rating'],$data['status'])) 
					
					$data['conf'] = "Insert berhasil";
				else $data['conf'] = "Insert gagal";
			} else {
				$data['conf'] = "Field Title, Summary, & Status are required";
			}
		} 
		else if ($this->input->post('update') == TRUE){
			// rules for form validation
			$this->form_validation->set_rules('id','ID','required');
			
			if ($this->form_validation->run() == TRUE){
				if ($this->Film->updateFilm($data['id'],$data['title'],$data['summary'],$data['genre'],$data['year'],$data['playing_date'],
					$data['length'],$data['director'],$data['writer'],$data['actors'],$data['poster'],$data['trailer'],
					$data['imdb_id'],$data['imdb_rating'],$data['metascore'],$data['twitter_positif'],$data['twitter_negatif'],
					$data['rating'],$data['status'])) 
					
					$data['conf'] = "Update berhasil";
				else $data['conf'] = "Update gagal";
			} else {
				$data['conf'] = "Field ID is required";
			}
		}
		else if ($this->input->post('delete') == TRUE){ 
			// rules for form validation
			$this->form_validation->set_rules('id','ID','required');
			
			if ($this->form_validation->run() == TRUE){
				if ($this->Film->deleteFilm($data['id'])) 
					$data['conf'] = "Delete berhasil";
				else $data['conf'] = "Delete gagal";
			} else {
				$data['conf'] = "Field ID is required";
			}
		}
		else { // jika form pertama kali di load -> set nilai default
			$data = NULL; $data['conf'] = "";
		}
		
		$data['b_url'] = base_url();
		$data['movies'] = $this->Film->getAllFilm();
		
		//$this->load->view('navigation'); 
		$this->load->view('crud/film', $data);
	}

	public function masterReview(){
		$this->load->model('Review');
		$this->load->model('Film');
		$this->load->model('User');
		
		// fetch data from form
		$data['id'] = $this->input->post('id', TRUE);
		$data['film_id'] = $this->input->post('film_id', TRUE);
		$data['username'] = $this->input->post('username', TRUE);
		$data['rating'] = $this->input->post('rating', TRUE);
		$data['review'] = $this->input->post('review', TRUE);
		$data['tanggal'] = date("Y-m-d", strtotime($this->input->post('tanggal', TRUE)));
		
		if ($this->input->post('insert') == TRUE){
			// other rules for form validation
			$this->form_validation->set_rules('film_id','ID Film','required');
			$this->form_validation->set_rules('username','Username','required');
			$this->form_validation->set_rules('rating','Rating','required');
			
			if ($this->form_validation->run() == TRUE){
				if ($this->Review->insertReview($data['film_id'],$data['username'],$data['rating'],$data['review'])) 
					$data['conf'] = "Insert berhasil";
				else $data['conf'] = "Insert gagal";
			} else {
				$data['conf'] = "Fields ID Film, Username, & Rating are required";
			}
		} 
		else if ($this->input->post('update') == TRUE){
			// other rules for form validation
			$this->form_validation->set_rules('id','ID','required');
			$this->form_validation->set_rules('film_id','ID Film','required');
			$this->form_validation->set_rules('username','Username','required');
			$this->form_validation->set_rules('rating','Rating','required');
			
			if ($this->form_validation->run() == TRUE){
				if ($this->Review->updateReview($data['id'],$data['film_id'],$data['username'],$data['rating'],$data['review'])) 
					$data['conf'] = "Update berhasil";
				else $data['conf'] = "Update gagal";
			} else {
				$data['conf'] = "ID, ID Film, Username, & Rating are required";
			}
		}
		else if ($this->input->post('delete') == TRUE){ 
			// other rules for form validation
			$this->form_validation->set_rules('id','ID','required');
			
			if ($this->form_validation->run() == TRUE){
				if ($this->Review->deleteReview($data['id'])) 
					$data['conf'] = "Delete berhasil";
				else $data['conf'] = "Delete gagal";
			} else {
				$data['conf'] = "ID is required";
			}
		}
		else { // jika form pertama kali di load -> set nilai default
			$data = NULL; $data['conf'] = "";
		}
		
		$data['b_url'] = base_url();
		$data['users'] = $this->User->getAllUser();
		$data['movies'] = $this->Film->getAllFilm();
		$data['reviews'] = $this->Review->getAllReview();
		
		//$this->load->view('navigation'); 
		$this->load->view('crud/review', $data);
	}
}
