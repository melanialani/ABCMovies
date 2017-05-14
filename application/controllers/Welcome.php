<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once( dirname(dirname(__FILE__)) . '/third_party/TwitterAPIExchange.php' );
include_once( dirname(dirname(__FILE__)) . '/third_party/SentimentAnalyzer.php' );

class Welcome extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		redirect('film/index');		
	}
	
	private function splitSentence($words){
		preg_match_all('/\w+/', $words, $matches);
		return $matches;
	}
	
}
