<?php 
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once( dirname(dirname(__FILE__)) . '/third_party/TwitterAPIExchange.php' );
include_once( dirname(dirname(__FILE__)) . '/third_party/SentimentAnalyzer.php' );
require_once( dirname(dirname(__FILE__)) . '/third_party/imdb.class.php' );

class WebSystem extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	public function checkNewMovies(){
		// old code YQL
		$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
	    $yql_query = "select * from html where url='http://www.21cineplex.com/nowplaying/'";
	    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
	    
		// new code YQL
		$site = "http://www.21cineplex.com/nowplaying/";
		$yql = "select * from htmlstring where url='" . $site . "'";
		$resturl = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
	    
	    // make call with cURL
	    $session = curl_init($resturl);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	    $json = curl_exec($session);
	    
	    // convert JSON to PHP object
	    $phpObj =  json_decode($json); 
	    
	    /***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****
	     ***** ***** ***** ***** ***** ***** ***** ***** CHECK NOW PLAYING ***** ***** ***** ***** ***** ***** ***** ***** *****
	     ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****/
	     
	    $playing = $phpObj->query->results->result;
		$playing = explode('<div id="playing">', $playing);
		$playing = explode('</div>', $playing[1]);
		//echo $playing[0].'<hr>';
	   	
	   	$nowPlaying = [];
	   	
	   	$title = explode('title', $playing[0]);
	   	$img = explode('src', $playing[0]);
		for ($i=1; $i<sizeof($title); $i++){
			$trueTitle = explode('"', $title[$i]);
			$trueImg = explode('"', $img[$i]);
			//echo $i.' - '.$trueTitle[1].'<br><img src="'.$trueImg[1].'"/><br/><br/>';
			$nowPlaying[$i]['title'] = $trueTitle[1];
			$nowPlaying[$i]['img'] = $trueImg[1];
		}
	    
	    if ($nowPlaying != NULL){
			// if there's a title in db with status NOW PLAYING, but doesnt exist in the data we got from 21, then change the movie's status into OLD
			$moviesinDB = $this->model_film->getOnGoingMovies();	
			for ($j=0; $j<sizeof($moviesinDB); $j++){
				$isStillPlaying = FALSE;
				for ($i=1; $i<sizeof($nowPlaying); $i++){
					if (mb_strtolower($moviesinDB[$j]['title']) == mb_strtolower($nowPlaying[$i]['title'])){
						$isStillPlaying = TRUE;
						break;
					}
			    }
				if (!$isStillPlaying) // if not exist, change status into old
					$this->model_film->updateStatusFilm($moviesinDB[$j]['id'], 2);
			}
			
			// explode each movie to get informations
			for ($i=1; $i<sizeof($nowPlaying); $i++){
				$getdata['title'] = $nowPlaying[$i]['title'];
				$getdata['poster'] = htmlspecialchars_decode(str_replace('100x147','300x430',$nowPlaying[$i]['img']));
				
				// so if the movie title has any 3D or IMAX in it, we dismiss it (because it's definitely a double)
				if (strpos($getdata['title'], '3D') == FALSE && strpos($getdata['title'], 'IMAX') == FALSE){
					// check with all now playing movies
					$moviesinDB = $this->model_film->getAllFilm();
					$alreadyinDB = FALSE;
					$inDB_id = NULL; 
					for ($j=0; $j<sizeof($moviesinDB); $j++){
						if (mb_strtolower($moviesinDB[$j]['title']) == mb_strtolower($getdata['title'])){
							$alreadyinDB = TRUE;
							$inDB_id = $moviesinDB[$j]['id'];
							break;
						}
					}
					if (!$alreadyinDB){ // add it to database
						$getdata['summary'] = NULL;
						$getdata['genre'] = NULL;
						$getdata['year'] = NULL;
						$getdata['playing_date'] = NULL;
						$getdata['length'] = NULL;
						$getdata['director'] = NULL;
						$getdata['writer'] = NULL;
						$getdata['actors'] = NULL;
						$getdata['imdb_id'] = NULL;
						$getdata['imdb_rating'] = NULL;
						$getdata['trailer'] = NULL;
						$getdata['metascore'] = NULL;
						$getdata['status'] = 4;
							
						// get movie's information from imdb
						$oIMDB = new IMDB($getdata['title']);
						if ($oIMDB->isReady) {
						    foreach ($oIMDB->getAll() as $aItem) {
						    	//echo '<b>' . $aItem['name'] . '</b>: ' . $aItem['value'] . '<br><br>';
						        if ($aItem['name'] == 'Cast')				$getdata['actors'] = $aItem['value'];
						        else if ($aItem['name'] == 'Director')		$getdata['director'] = $aItem['value'];
						        else if ($aItem['name'] == 'Plot')			$getdata['summary'] = $aItem['value'];
						        else if ($aItem['name'] == 'Poster')		$getdata['poster'] = htmlspecialchars_decode($aItem['value']);
						        else if ($aItem['name'] == 'Rating')		$getdata['imdb_rating'] = $aItem['value'];
						        else if ($aItem['name'] == 'Runtime')		$getdata['length'] = $aItem['value'];
						        else if ($aItem['name'] == 'Writer')		$getdata['writer'] = $aItem['value'];
						        else if ($aItem['name'] == 'Year')			$getdata['year'] = $aItem['value'];
						        else if ($aItem['name'] == 'Genre')			$getdata['genre'] = $aItem['value'];
						        else if ($aItem['name'] == 'Trailer'){
						        	// <iframe width="854" height="480" src="https://www.youtube.com/embed/G4VmJcZR0Yg" frameborder="0" allowfullscreen></iframe>
						        	$getdata['trailer'] = htmlspecialchars_decode('<iframe width="894" height="520" src="'.$aItem['value'].'imdb/embed?autoplay=false&width=854" frameborder="0" allowfullscreen></iframe>');
						        } else if ($aItem['name'] == 'Url'){
						        	// ex: http://www.imdb.com/title/tt4981636/
									$temp = explode('/', $aItem['value']);
									$getdata['imdb_id'] = $temp[4];
								} else if ($aItem['name'] == 'Release Date'){
						        	$temp = $aItem['value'];
						        	if (strpos($temp, '(') == TRUE){ // ex: 7 October 2016 (USA)
										$temp = explode('(', $temp);
										$temp = trim($temp[0]);
									}
									$getdata['playing_date'] = date("Y-m-d", strtotime($temp));	
						        }
						        
								// translate movie's summary from imdb
								$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($getdata['summary']).'&lang=en-id';
								$json = file_get_contents($url);
								$yandex = json_decode($json);
								if ($yandex->code == 200) $getdata['summary'] = $yandex->text[0];
						    }
						} 
						
						$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
							$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],NULL,$getdata['status']);
					} else { // already in db, just change status
						$this->model_film->updateStatusFilm($inDB_id, 1);
					}
					
					//echo $getdata['title'].'<br>';
				}
		    }
		}
		
		/***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****
	     ***** ***** ***** ***** ***** ***** ***** ***** CHECK COMING SOON ***** ***** ***** ***** ***** ***** ***** ***** *****
	     ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****/
	     
	    $coming = $phpObj->query->results->result;
		$coming = explode('<div id="coming">', $coming);
		$coming = explode('</div>', $coming[1]);
		//echo $coming[0].'<hr>';
	   	
	   	$comingSoon = [];
	   	
	   	$title = explode('title', $coming[0]);
	   	$img = explode('src', $coming[0]);
		for ($i=1; $i<sizeof($title); $i++){
			$trueTitle = explode('"', $title[$i]);
			$trueImg = explode('"', $img[$i]);
			//echo $i.' - '.$trueTitle[1].'<br><img src="'.$trueImg[1].'"/><br/><br/>';
			$comingSoon[$i]['title'] = $trueTitle[1];
			$comingSoon[$i]['img'] = $trueImg[1];
		}
	    
	    if ($comingSoon != NULL){
			// explode each movie to get informations
			for ($i=1; $i<sizeof($comingSoon); $i++){
				$getdata['title'] = $comingSoon[$i]['title'];
				$getdata['poster'] = htmlspecialchars_decode(str_replace('100x147','300x430',$comingSoon[$i]['img']));
				
				// so if the movie title has any 3D or IMAX in it, we dismiss it (because it's definitely a double)
				if (strpos($getdata['title'], '3D') == FALSE && strpos($getdata['title'], 'IMAX') == FALSE){
					// check if title already exist in database
					if (!$this->model_film->getFilmByTitle($getdata['title'])){
						$getdata['summary'] = NULL;
						$getdata['genre'] = NULL;
						$getdata['year'] = NULL;
						$getdata['playing_date'] = NULL;
						$getdata['length'] = NULL;
						$getdata['director'] = NULL;
						$getdata['writer'] = NULL;
						$getdata['actors'] = NULL;
						$getdata['imdb_id'] = NULL;
						$getdata['imdb_rating'] = NULL;
						$getdata['trailer'] = NULL;
						$getdata['metascore'] = NULL;
						$getdata['status'] = 3;
						
						// get movie's information from imdb
						$oIMDB = new IMDB($getdata['title']);
						if ($oIMDB->isReady) {
						    foreach ($oIMDB->getAll() as $aItem) {
						    	//echo '<b>' . $aItem['name'] . '</b>: ' . $aItem['value'] . '<br><br>';
						        if ($aItem['name'] == 'Cast')				$getdata['actors'] = $aItem['value'];
						        else if ($aItem['name'] == 'Director')		$getdata['director'] = $aItem['value'];
						        else if ($aItem['name'] == 'Plot')			$getdata['summary'] = $aItem['value'];
						        else if ($aItem['name'] == 'Poster')		$getdata['poster'] = htmlspecialchars_decode($aItem['value']);
						        else if ($aItem['name'] == 'Rating')		$getdata['imdb_rating'] = $aItem['value'];
						        else if ($aItem['name'] == 'Runtime')		$getdata['length'] = $aItem['value'];
						        else if ($aItem['name'] == 'Writer')		$getdata['writer'] = $aItem['value'];
						        else if ($aItem['name'] == 'Year')			$getdata['year'] = $aItem['value'];
						        else if ($aItem['name'] == 'Genre')			$getdata['genre'] = $aItem['value'];
						        else if ($aItem['name'] == 'Trailer'){
						        	// <iframe width="854" height="480" src="https://www.youtube.com/embed/G4VmJcZR0Yg" frameborder="0" allowfullscreen></iframe>
						        	$getdata['trailer'] = htmlspecialchars_decode('<iframe width="894" height="520" src="'.$aItem['value'].'imdb/embed?autoplay=false&width=854" frameborder="0" allowfullscreen></iframe>');
						        } else if ($aItem['name'] == 'Url'){
						        	// ex: http://www.imdb.com/title/tt4981636/
									$temp = explode('/', $aItem['value']);
									$getdata['imdb_id'] = $temp[4];
								} else if ($aItem['name'] == 'Release Date'){
						        	$temp = $aItem['value'];
						        	if (strpos($temp, '(') == TRUE){ // ex: 7 October 2016 (USA)
										$temp = explode('(', $temp);
										$temp = trim($temp[0]);
									}
									$getdata['playing_date'] = date("Y-m-d", strtotime($temp));	
						        }
						        
								// translate movie's summary from imdb
								$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($getdata['summary']).'&lang=en-id';
								$json = file_get_contents($url);
								$yandex = json_decode($json);
								if ($yandex->code == 200) $getdata['summary'] = $yandex->text[0];
						    }
						}
						
						$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
							$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],NULL,$getdata['status']);
					} 
				}
		    }
		}
	}
	
	private function splitSentence($words){
		preg_match_all('/\w+/', $words, $matches);
		return $matches[0];
	}

	public function getTweets($film_id = NULL){
		$result = []; $index = 0;
		
		$movie = $this->model_film->getFilm($film_id);
		$title = $movie[0]['title'];
		$param = explode(',', trim($movie[0]['twitter_search']));
		
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$requestMethod = 'GET';
		$settings = array(
		    'oauth_access_token' => "1430750114-nsW0ODE88uJsy68jd6xJqB2HJIWlrDKAE3DOzQW",
		    'oauth_access_token_secret' => "BzZSA3Z0rcYcGBaTBRjbn3FjOSvIKMqGGAPNZPMv3VI76",
		    'consumer_key' => "Tweak7j9XE7hcMWnrKoPTvFZW",
		    'consumer_secret' => "5d7WLg2jSRZQCRvC3yyS3ZlhGuFDnXGaOCF1Cunearu1d0akLu"
		);
		
		// search with tag movieTitle minus RT, only in bahasa indonesia, sorted by recent one
		$getfield = '?count=100&q=#'.str_replace(' ', '', $title).'+-RT&lang=id&result_type=recent';
		$twitter = new TwitterAPIExchange($settings);
		$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		$response = json_decode($response);
		
		for ($i=0; $i<sizeof($response->statuses); $i++){ // put response into array
			$result[$index]['twitter_id'] = $response->statuses[$i]->id;
			$result[$index]['text'] = strtolower($response->statuses[$i]->text);
			$result[$index]['created_at'] = date("Y-m-d H:i:s", strtotime($response->statuses[$i]->created_at));
			$result[$index]['is_review'] = 0;
			$result[$index]['is_positive'] = 0;
			
			// if twitter id and text doesnt exist in db
			if (!$this->model_tweets_new->getTweetByOri('tweets_ori',$result[$index]['twitter_id'])){
				$this->model_tweets_new->insertTweetOri($film_id, $result[$index]['twitter_id'], $result[$index]['text'], $result[$index]['created_at']);
			} 
			
			$index ++;
		}
		
		// search with exact words of "movie's title" minus RT, only in bahasa indonesia, sorted by recent one
		$getfield = '?count=100&q="'.$title.'"+-RT&lang=id&result_type=recent';
		$twitter = new TwitterAPIExchange($settings);
		$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		$response = json_decode($response);
		
		for ($i=0; $i<sizeof($response->statuses); $i++){ // put response into array
			$result[$index]['twitter_id'] = $response->statuses[$i]->id;
			$result[$index]['text'] = strtolower($response->statuses[$i]->text);//$result[$index]['text'] = strtolower(html_entity_decode($response->statuses[$i]->text, ENT_QUOTES | ENT_XML1, 'UTF-8')); // decode html characters
			$result[$index]['created_at'] = date("Y-m-d H:i:s", strtotime($response->statuses[$i]->created_at));
			$result[$index]['is_review'] = 0;
			$result[$index]['is_positive'] = 0;
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_ori',$result[$index]['twitter_id'])){
				$this->model_tweets_new->insertTweetOri($film_id, $result[$index]['twitter_id'], $result[$index]['text'], $result[$index]['created_at']);
			}
			
			$index ++;
		}
		
		// search with param
		for ($a=0; $a<sizeof($param); $a++){
			$getfield = '?count=100&q="'.$param[$a].'"+-RT&lang=id&result_type=recent';
			$twitter = new TwitterAPIExchange($settings);
			$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
			$response = json_decode($response);
			
			for ($i=0; $i<sizeof($response->statuses); $i++){ // put response into array
				$result[$index]['twitter_id'] = $response->statuses[$i]->id;
				$result[$index]['text'] = strtolower($response->statuses[$i]->text);
				$result[$index]['created_at'] = date("Y-m-d H:i:s", strtotime($response->statuses[$i]->created_at));
				$result[$index]['is_review'] = 0;
				$result[$index]['is_positive'] = 0;
				
				if (!$this->model_tweets_new->getTweetByOri('tweets_ori',$result[$index]['twitter_id'])){
					$this->model_tweets_new->insertTweetOri($film_id, $result[$index]['twitter_id'], $result[$index]['text'], $result[$index]['created_at']);
				}
				
				$index ++;
			}
		}
		
		$this->calculateTweets($film_id);
	}
	
	public function calculateTweets($film_id){
		// for rule-based system
		$commonWordsOrdinary = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words-review.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($commonWordsOrdinary, trim($activeLine));	
		}
		
		$commonWords = []; $commonWordsValue = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words-value.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($commonWords, $temp[0]);
			array_push($commonWordsValue, $temp[1]);
		}
		
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon-minus-common_words_review.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
		}
		
		$singkatan = []; $singkatan_replace = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($singkatan, $temp[0]);
			array_push($singkatan_replace, $temp[1]);
		}
		
		$listPos = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/sentiment_pos.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($listPos, trim($activeLine));	
		}
		
		$listNeg = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/sentiment_neg.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($listNeg, trim($activeLine));	
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		$result = [];
		$tweets = $this->model_tweets_new->getAllOriTweetsByMovie($film_id);
		
		// put into associative array
		for ($i=0; $i<sizeof($tweets); $i++){
			$result[$i]['twitter_id'] = $tweets[$i]['twitter_id'];
			$result[$i]['text'] = strtolower($tweets[$i]['text']);
			$result[$i]['created_at'] = date("Y-m-d H:i:s", strtotime($tweets[$i]['created_at']));
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0; 
		}
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$movie = $this->model_film->getFilm($film_id);
			$title = $movie[0]['title'];
			
			$editedResult = $result[$i]['text'];
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			$editedResult = str_ireplace($title, 'JUDULFILM', $editedResult);
			$editedResult = preg_replace('/(.)\\1+/', '$1', $editedResult); // delete double characters in a word
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult);  // delete everything except a-z & 0-9
			
			$result[$i]['text'] = $editedResult;
			if (!$this->model_tweets_new->getTweetByOri('tweets_regex', $result[$i]['twitter_id'])){
				$this->model_tweets_new->insertTweetRegex($result[$i]['twitter_id'], $result[$i]['text']);
			}
			
			// replace bahasa bukan baku
			for ($a=1; $a<=3; $a++){ // cek bahasa bukan 3x
				$words = $this->splitSentence($result[$i]['text']);
				$intersectsWith = array_intersect($singkatan, $words);
				if ($intersectsWith) {
					$intersectStr = null;
					for ($j=0; $j<sizeof($intersectsWith); $j++){
						$arrayKey = key($intersectsWith);
						$result[$i]['text'] = preg_replace('/\b'.$singkatan[$arrayKey].'\b/u', $singkatan_replace[$arrayKey], $result[$i]['text']);
						
						if ($intersectStr == null)
							$intersectStr = $singkatan[$arrayKey];
						else 
							$intersectStr .= ',' . $singkatan[$arrayKey];
						
						// get next key array
						next($intersectsWith);
					}
					
					if (!$this->model_tweets_new->getTweetByOri('tweets_replaced', $result[$i]['twitter_id'])){
						$this->model_tweets_new->insertTweetReplaced($result[$i]['twitter_id'], $result[$i]['text'], $intersectStr);
					}
				}
			}
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			$value = 0;
			$intersectStr = null;
			$words = $this->splitSentence($result[$i]['text']);
			
			// compare with lexicon data
			$intersectsWith = array_intersect($lexicon, $words);
			if ($intersectsWith) {
				$value += sizeof($intersectsWith); // if intersect with lexicon, give 1 for every word
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					if ($intersectStr == null)
						$intersectStr = $lexicon[$arrayKey];
					else 
						$intersectStr .= ',' . $lexicon[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
			}
			
			// compare with ordinary common words data
			$intersectsWith = array_intersect($commonWordsOrdinary, $words);
			if ($intersectsWith) {
				$value += sizeof($intersectsWith); // if intersect with ordinary common words, give 1 for every word
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					if ($intersectStr == null)
						$intersectStr = $commonWordsOrdinary[$arrayKey];
					else 
						$intersectStr .= ',' . $commonWordsOrdinary[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
			}
			
			// compare with valued common words data
			$intersectsWith = array_intersect($commonWords, $words);
			if ($intersectsWith) {
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					$value += $commonWordsValue[$arrayKey];
					
					if ($intersectStr == null)
						$intersectStr = $commonWords[$arrayKey];
					else 
						$intersectStr .= ',' . $commonWords[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_lexicon', $result[$i]['twitter_id']) && $value >= 8){
				$result[$i]['is_review'] = 1;
				$this->model_tweets_new->insertTweetLexicon($result[$i]['twitter_id'], $intersectStr);
			}
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['is_review'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($result[$i]['text']);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$probabilityofSentenceBeingPositive = $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$probabilityofSentenceBeingNegative = $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				// compare with common words also	
				$words = $this->splitSentence($result[$i]['text']);
				if (array_intersect($listPos, $words))
					$probabilityofSentenceBeingPositive += sizeof(array_intersect($listPos, $words))*0.25;
				if (array_intersect($listNeg, $words))
					$probabilityofSentenceBeingNegative += sizeof(array_intersect($listNeg, $words))*0.25;
				
				// set is_positive value
				if ($resultofAnalyzingSentence == "positive" || $probabilityofSentenceBeingPositive > $probabilityofSentenceBeingNegative)
					$result[$i]['is_positive'] = 1;
				else 
					$result[$i]['is_positive'] = 0;
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_final', $result[$i]['twitter_id'])){
				if (!$this->model_tweets_new->getTweetByText('tweets_final', $result[$i]['text']))
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $film_id, $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 0);
				else 
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $film_id, $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 1);
			}
		}
		
		// update film
		$this->model_film->updateTwitterFilm($film_id, $this->model_tweets_old->getMovieCountNegTweet($film_id), $this->model_tweets_old->getMovieCountPosTweet($film_id));	
		
		redirect('admin/detailTweets');
	}
	
}

?>
