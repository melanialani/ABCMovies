<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once( dirname(dirname(__FILE__)) . '/third_party/TwitterAPIExchange.php' );
include_once( dirname(dirname(__FILE__)) . '/third_party/SentimentAnalyzer.php' );

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
						// get movie's information from omdb api
						$foundInImdb = FALSE;
						for ($j=date('Y'); $j>(date('Y')-5); $j--){
							$url = 'http://www.omdbapi.com/?t='.urlencode($getdata['title']).'&y='.$j.'&plot=full';
							
							//$json = file_get_contents($url);
							$session = curl_init($url);
						    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
						    $json = curl_exec($session);
						    $omdb =  json_decode($json); 
							
							if ($omdb->Response == "True"){
								$foundInImdb = TRUE;
								break;
							}
						}
						
						if ($foundInImdb){
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
							
							// translate movie's summary from omdb api
							$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($omdb->Plot).'&lang=en-id';
							$json = file_get_contents($url);
							$yandex = json_decode($json);
							$getdata['summary'] = $yandex->text[0];
						} else {
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
							$getdata['metascore'] = NULL;
						}
						
						$getdata['trailer'] = NULL;
						$getdata['status'] = 4;
						
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
						// get movie's information from omdb api
						$foundInImdb = FALSE;
						for ($j=date('Y'); $j>(date('Y')-5); $j--){
							$url = 'http://www.omdbapi.com/?t='.urlencode($getdata['title']).'&y='.$j.'&plot=full';
							
							//$json = file_get_contents($url);
							$session = curl_init($url);
						    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
						    $json = curl_exec($session);
						    $omdb =  json_decode($json); 
							
							if ($omdb->Response == "True"){
								$foundInImdb = TRUE;
								break;
							}
						}
						
						if ($foundInImdb){
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
							
							// translate movie's summary from omdb api
							$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($omdb->Plot).'&lang=en-id';
							$json = file_get_contents($url);
							$yandex = json_decode($json);
							$getdata['summary'] = $yandex->text[0];
						} else {
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
							$getdata['metascore'] = NULL;
						}
						
						$getdata['trailer'] = NULL;
						$getdata['status'] = 3;
						
						$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
							$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],NULL,$getdata['status']);
					
						//echo $getdata['title'].'<br>';
					} 
				}
		    }
		}
	}
	
	private function splitSentence($words){
		preg_match_all('/\w+/', $words, $matches);
		return $matches;
	}

	public function getTweets($film_id = NULL){
		$result = []; $index = 0;
		
		$movie = $this->model_film->getFilm($film_id);
		$title = $movie[0]['title'];
		$param = trim(explode(',', $movie[0]['twitter_search']));
		
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
			if (!$this->model_tweets->getTweetOri($result[$index]['twitter_id']) && !$this->model_tweets->getTweetFORSLbyText('tweets_ori', $result[$index]['text'])){
				$this->model_tweets->insertTweetOri($film_id, $result[$index]['twitter_id'], $result[$index]['text'], $result[$index]['created_at']);
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
			$result[$index]['text'] = strtolower($response->statuses[$i]->text);
			$result[$index]['created_at'] = date("Y-m-d H:i:s", strtotime($response->statuses[$i]->created_at));
			$result[$index]['is_review'] = 0;
			$result[$index]['is_positive'] = 0;
			
			if (!$this->model_tweets->getTweetOri($result[$index]['twitter_id'])){
				$this->model_tweets->insertTweetOri($film_id, $result[$index]['twitter_id'], $result[$index]['text'], $result[$index]['created_at']);
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
				
				if (!$this->model_tweets->getTweetOri($result[$index]['twitter_id'])){
					$this->model_tweets->insertTweetOri($film_id, $result[$index]['twitter_id'], $result[$index]['text'], $result[$index]['created_at']);
				} 
				
				$index ++;
			}
		}
		
		return $result;
	}
	
	public function calculateTweets(){
		// for rule-based system
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
		}
		
		$stopword = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/stopword.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($stopword, trim($activeLine));	
		}
		
		$alay = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/alay.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($alay, trim($activeLine));	
		}
		
		$alay_replace = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/alay_arti.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($alay_replace, trim($activeLine));	
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //trainign with positive data	
		
		// get movie's title
		$film_id = $this->input->post('film_id', TRUE); 
		$movie = $this->model_film->getFilm($film_id);
		$title = $movie[0]['title'];
		
		$result = $this->getTweets($film_id);
		
		// !!! === !!! === begin rule-based system === !!! === !!!
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// split camel case words
			$result[$i]['text'] = preg_split('/(?=[A-Z])/', $result[$i]['text']);
			
			// split sentence
			$target = $this->splitSentence($result[$i]['text']);
			$target = $target[0];
			
			// replace bahasa alay
			if (array_intersect($alay, $target)) {
				$intersectStr = null;
				$intersectsWith = array_intersect($alay, $target);
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					$result[$i]['text'] = str_ireplace($alay[$arrayKey], $alay_replace[$arrayKey], $result[$i]['text']);
					
					if ($intersectStr == null)
						$intersectStr = $alay[$arrayKey];
					else 
						$intersectStr .= ',' . $alay[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
				
				if (!$this->model_tweets->getTweetFRSLbyOri('tweets_replaced', $result[$i]['twitter_id'])){
					$this->model_tweets->insertTweetRS('tweets_replaced', $result[$i]['twitter_id'], $result[$i]['text'], $intersectStr);
				}
			}
			
			// delete stopword
			if (array_intersect($stopword, $target)) {
				$intersectStr = null;
				$intersectsWith = array_intersect($stopword, $target);
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					$result[$i]['text'] = str_ireplace($stopword[$arrayKey], '', $result[$i]['text']);
					
					if ($intersectStr == null)
						$intersectStr = $stopword[$arrayKey];
					else 
						$intersectStr .= ',' . $stopword[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
				
				if (!$this->model_tweets->getTweetFRSLbyOri('tweets_stopword', $result[$i]['twitter_id'])){
					$this->model_tweets->insertTweetRS('tweets_stopword', $result[$i]['twitter_id'], $result[$i]['text'], $intersectStr);
				}
			}
			
			// compare with lexicon data
			if (array_intersect($lexicon, $target)) {
				$result[$i]['is_review'] = 1;
				
				$intersectStr = null;
				$intersectsWith = array_intersect($lexicon, $target);
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					if ($intersectStr == null)
						$intersectStr = $lexicon[$arrayKey];
					else 
						$intersectStr .= ',' . $lexicon[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
				
				if (!$this->model_tweets->getTweetFRSLbyOri('tweets_lexicon', $result[$i]['twitter_id'])){
					$this->model_tweets->insertTweetLexicon($result[$i]['twitter_id'], $intersectStr);
				}
			}
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$editedResult = $result[$i]['text'];
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult); 
			$editedResult = str_ireplace($title, 'JUDULFILM', $editedResult);
			
			$result[$i]['text'] = $editedResult;
			if (!$this->model_tweets->getTweetFRSLbyOri('tweets_regex', $result[$i]['twitter_id'])){
				$this->model_tweets->insertTweetRegex($result[$i]['twitter_id'], $result[$i]['text']);
			}
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['is_review'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($result[$i]['text']);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$probabilityofSentenceBeingPositive = $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$probabilityofSentenceBeingNegative = $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				if ($resultofAnalyzingSentence == "positive")
					$result[$i]['is_positive'] = 1;
			}
			
			if (!$this->model_tweets->getTweetFRSLbyOri('tweets_final', $result[$i]['twitter_id'])){
				$this->model_tweets->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive']);
			}
		}
		
		// update film
		$this->model_film->updateTwitterFilm($film_id, $this->model_tweets->getMovieCountNegTweet($film_id), $this->model_tweets->getMovieCountPosTweet($film_id));
		
		redirect('admin/detailTweets');
	}
	
}

?>
