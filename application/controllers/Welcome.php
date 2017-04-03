<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once (dirname(__FILE__) . "/Admin.php");

include_once( dirname(dirname(__FILE__)) . '/libraries/TwitterAPIExchange.php' );
include_once( dirname(dirname(__FILE__)) . '/libraries/DataValidator.php' );
include_once( dirname(dirname(__FILE__)) . '/libraries/DataValidatorLib.php' );

class Welcome extends Admin {
	
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->checkNewComingSoonMovies();
		$this->checkNewNowPlayingMovies();
		
		redirect('film/index');
	}
	
	public function coba(){
		$settings = array(
		    'oauth_access_token' => "1430750114-nsW0ODE88uJsy68jd6xJqB2HJIWlrDKAE3DOzQW",
		    'oauth_access_token_secret' => "BzZSA3Z0rcYcGBaTBRjbn3FjOSvIKMqGGAPNZPMv3VI76",
		    'consumer_key' => "Tweak7j9XE7hcMWnrKoPTvFZW",
		    'consumer_secret' => "5d7WLg2jSRZQCRvC3yyS3ZlhGuFDnXGaOCF1Cunearu1d0akLu"
		);

		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$requestMethod = 'GET';
		
		// with tag beautyAndTheBeast or exact words "beauty and the beast" minus RT, only in bahasa indonesia, sorted by recent one
		// count doesnt work with popular tweet
		$getfield = '?count=100&q=#beautyAndTheBeast+OR+"beauty and the beast"+-RT&lang=id&result_type=recent';
		
		$twitter = new TwitterAPIExchange($settings);
		$response = $twitter->setGetfield($getfield)
		    ->buildOauth($url, $requestMethod)
		    ->performRequest();
		
		$response = json_decode($response);
		
		//var_dump($response);
		//echo "<pre>"; print_r($response); echo "</pre><br/>";
		
		for ($i=0; $i<sizeof($response->statuses); $i++){
			echo $i." - ".$response->statuses[$i]->text."<hr/>";
		}
		
		/**
		* Begin feature reduction
		* Delete username, url, hashtag, and any punctuations
		*/
		
		$result = [];
		
		echo "<br><br><br><br>";
		
		for ($i=0; $i<sizeof($response->statuses); $i++){
			$editedResult = $response->statuses[$i]->text;
			
			// replace any url with word URL
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			// replace any hashtag with word HASHTAG 
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			// replace any username (@..) with word USERNAME
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			
			// replace all characters that are not number or alphabet with a single space
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult); 
			// the line above will result with double spaces if there are any punctuation, now we will remove any double spaces here
			$editedResult = str_replace('  ', ' ', $editedResult); 
			
			// replace movie's title with word JUDULFILM
			$editedResult = str_ireplace('beauty and the beast', 'JUDULFILM', $editedResult);
			
			// put the result into array $editedResult
			array_push($result, $editedResult);
		}
		
		// print the result after feature reduction
		for ($i=0; $i<sizeof($result); $i++){
			echo $i." - ".$result[$i]."<hr/>";
		}
	}
	
}
