<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		//redirect('film/index');
		
		$this->load->library('simple_html_dom');
		$this->load->library('curl');
		
		// http://www.21cineplex.com/nowplaying || http://www.21cineplex.com/comingsoon
		
		
		$raw = file_get_html('http://www.21cineplex.com/nowplaying');
		foreach($raw->find('img') as $element){
			echo $element->src . '<br>';
		}
		echo "<br/>========================================================================================================================<br/>";
		$raw = file_get_html('http://www.21cineplex.com/comingsoon');
		$raw = $raw->find('#mvlist');
		print_r($raw);
		echo "<br/>========================================================================================================================<br/>";
		$homepage = file_get_contents('http://www.21cineplex.com/nowplaying/');
		//echo $homepage;
		echo "<br/>========================================================================================================================<br/>";
		// Simple call to remote URL
		echo $this->curl->simple_get('http://www.21cineplex.com/nowplaying/');

		// Set advanced options in simple calls
		// Can use any of these flags http://uk3.php.net/manual/en/function.curl-setopt.php

		$this->curl->simple_get('http://www.21cineplex.com/nowplaying/', array(CURLOPT_PORT => 8080));
		//$this->curl->simple_post('http://www.21cineplex.com/nowplaying/', array('foo'=>'bar'), array(CURLOPT_BUFFERSIZE => 10)); 
		echo "<br/>========================================================================================================================<br/>";
		$url = "http://www.google.com";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		echo $data;
		echo "<br/>========================================================================================================================<br/>";
		$this->load->view('welcome_message');
	}
	
}
