<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Admin.php");

class Welcome extends Admin {
	
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->checkNewComingSoonMovies();
		$this->checkNewNowPlayingMovies();
		
		redirect('film/index');
	}
	
}
