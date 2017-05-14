<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once( dirname(dirname(__FILE__)) . '/third_party/TwitterAPIExchange.php' );
include_once( dirname(dirname(__FILE__)) . '/third_party/SentimentAnalyzer.php' );

class WebSystem extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('model_tweets');
		$this->load->model('model_film');
	}
	
	public function checkNewComingSoonMovies(){
		$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
	    $yql_query = "select * from html where url='http://www.21cineplex.com/comingsoon/'";
	    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
	    
	    // make call with cURL
	    $session = curl_init($yql_query_url);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	    $json = curl_exec($session);
	    
	    // convert JSON to PHP object
	    $phpObj =  json_decode($json);
	    
	    if ($phpObj->query->results){
			// get js content from cinema 21
		    $pdata = $phpObj->query->results->body->div[0]->div[1]->div[1]->div[2]->script->content;
		    
		    // explode string to get data -> stored inside var pdata=[..]
		    $pdata = explode(']', $pdata);
		    $pdata = $pdata[0];
		    $pdata = explode('pdata=[', $pdata);
			$pdata = "[".$pdata[1]."]"; // valid json
			$pdata = explode('{', $pdata); // explode to get each movie
			//echo '<pre>'; print_r($pdata); echo '</pre>';
			
			// explode each movie to get informations
			for ($i=1; $i<sizeof($pdata); $i++){
				$info = explode('movieTitle":"', $pdata[$i]);
				$info = explode('","', $info[1]);
				$getdata['title'] = $info[0];
				
				$info = explode('movieSinopsis":"', $pdata[$i]);
				$info = explode('","', $info[1]);
				$getdata['summary'] = $info[0];
				
				$info = explode('movieImage":"', $pdata[$i]);
				$info = explode('","', $info[1]);
				$getdata['poster'] = $info[0];
				
				// so if the movie title has any (..) in it, we dismiss it (because it's definitely a double)
				if (strpos($getdata['title'], '(') == FALSE){
					
					// check with all coming soon movies
					$moviesinDB = $this->model_film->getAllFilm();
					$alreadyinDB = FALSE;
					for ($j=0; $j<sizeof($moviesinDB); $j++){
						if (mb_strtolower($moviesinDB[$j]['title']) == mb_strtolower($getdata['title'])){
							$alreadyinDB = TRUE;
							break;
						}
					}
					if (!$alreadyinDB){ // add it to database
						// get movie's information from omdb api
						$foundInImdb = FALSE;
						for ($j=date('Y'); $j>(date('Y')-5); $j--){
							$url = 'http://www.omdbapi.com/?t='.urlencode($getdata['title']).'&y='.$j.'&plot=full';
							$json = file_get_contents($url);
							$omdb = json_decode($json);
							//echo "<pre>"; print_r($omdb); echo "</pre><br/>";
							
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
							// get poster from cinema 21
							$getdata['poster'] = explode('.', $getdata['poster']);
							$getdata['poster'] =  htmlspecialchars_decode('http://www.21cineplex.com/data/gallery/pictures/'.$getdata['poster'][0].'_300x430.jpg');
							//echo '<br/>'.$getdata['poster'].'<br/>';
						}
						
						$getdata['trailer'] = NULL;
						$getdata['status'] = 3;
						
						$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],$getdata['writer'],
								$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],$getdata['status']);
					}
				}
		    }
		}
	}

	public function checkNewNowPlayingMovies(){
		$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
	    $yql_query = "select * from html where url='http://www.21cineplex.com/nowplaying/'";
	    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
	    
	    # make call with cURL
	    $session = curl_init($yql_query_url);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	    $json = curl_exec($session);
	    
	    # convert JSON to PHP object
	    $phpObj =  json_decode($json);
	    
	    if ($phpObj->query->results){
			$pdata = $phpObj->query->results->body->div[0]->div[1]->div[3]->div[0]->div[0]->div[1]->ul->li;
			
			# if there's a title in db with status NOW PLAYING, but doesnt exist in the data we got from 21, then change the movie's status into OLD
			$moviesinDB = $this->model_film->getOnGoingMovies();	
			for ($j=0; $j<sizeof($moviesinDB); $j++){
				$isStillPlaying = FALSE;
				for ($i=1; $i<sizeof($pdata)-2; $i++){
					if (mb_strtolower($moviesinDB[$j]['title']) == mb_strtolower($pdata[$i]->a->img->title)){
						$isStillPlaying = TRUE;
						break;
					}
			    }
				if (!$isStillPlaying) # if not exist, change status into old
					$this->model_film->updateStatusFilm($moviesinDB[$j]['id'], 2);
			}
			
			# explode each movie to get informations
		}
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
		
		// begin twitter request -> with tag movieTitle or exact words of "movie's title" minus RT, only in bahasa indonesia, sorted by recent one
		$settings = array(
		    'oauth_access_token' => "1430750114-nsW0ODE88uJsy68jd6xJqB2HJIWlrDKAE3DOzQW",
		    'oauth_access_token_secret' => "BzZSA3Z0rcYcGBaTBRjbn3FjOSvIKMqGGAPNZPMv3VI76",
		    'consumer_key' => "Tweak7j9XE7hcMWnrKoPTvFZW",
		    'consumer_secret' => "5d7WLg2jSRZQCRvC3yyS3ZlhGuFDnXGaOCF1Cunearu1d0akLu"
		);
		
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$requestMethod = 'GET';
		$getfield = '?count=100&q=#'.str_replace(' ', '', $title).'+OR+"'.$title.'"+-RT&lang=id&result_type=recent';
		
		$twitter = new TwitterAPIExchange($settings);
		$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		$response = json_decode($response);
		
		// put response into array
		$result = [];
		for ($i=0; $i<sizeof($response->statuses); $i++){
			$result[$i]['twitter_id'] = $response->statuses[$i]->id;
			$result[$i]['text'] = strtolower($response->statuses[$i]->text);
			$result[$i]['created_at'] = date("Y-m-d H:i:s", strtotime($response->statuses[$i]->created_at));
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0;
			
			if (!$this->model_tweets->getTweetOri($result[$i]['twitter_id'])){
				$this->model_tweets->insertTweetOri($film_id, $result[$i]['twitter_id'], $result[$i]['text'], $result[$i]['created_at']);
			} 
		}
		
		// !!! === !!! === begin rule-based system === !!! === !!!
		
		// !!! === !!! === compare with lexicon data
		for ($i=0; $i<sizeof($result); $i++){
			// split tweet word by word and put it into an array, to compare easily
			$target = $result[$i]['text'];
			$target = $this->splitSentence($target);
			$target = $target[0];
			
			// if there's any word that intersects (exist in 2 arrays), do:
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
		}
		
		// !!! === !!! === feature reduction --> delete username, url, hashtag, punctuations
		for ($i=0; $i<sizeof($result); $i++){
			$editedResult = $result[$i]['text'];
			
			// replace any url with word URL
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			// replace any hashtag with word HASHTAG 
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			// replace any username (@..) with word USERNAME
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			// replace all characters that are not number or alphabet with a single space
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult); 
			// replace movie's title with word JUDULFILM
			$editedResult = str_ireplace($title, 'JUDULFILM', $editedResult);
			
			$result[$i]['text'] = $editedResult;
			if (!$this->model_tweets->getTweetFRSLbyOri('tweets_regex', $result[$i]['twitter_id'])){
				$this->model_tweets->insertTweetRegex($result[$i]['twitter_id'], $result[$i]['text']);
			}
		}
		
		// !!! === !!! === replace bahasa alay
		for ($i=0; $i<sizeof($result); $i++){
			// split tweet word by word and put it into an array, to compare easily
			$target = $result[$i]['text'];
			$target = $this->splitSentence($target);
			$target = $target[0];
			
			// if there's any word that intersects (exist in 2 arrays), do:
			if (array_intersect($alay, $target)) {
				$intersectStr = null;
				$intersectsWith = array_intersect($alay, $target);
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					//$result[$i]['text'] = str_ireplace($alay[$arrayKey], $alay_replace[$arrayKey], $result[$i]['text']);
					$result[$i]['text'] = preg_replace('/'.$alay[$arrayKey].'/', $alay_replace[$arrayKey], $result[$i]['text']);
					
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
		}
		
		// !!! === !!! === delete stopword
		for ($i=0; $i<sizeof($result); $i++){
			// split tweet word by word and put it into an array, to compare easily
			$target = $result[$i]['text'];
			$target = $this->splitSentence($target);
			$target = $target[0];
			
			// if there's any word that intersects (exist in 2 arrays), do:
			if (array_intersect($stopword, $target)) {
				$intersectStr = null;
				$intersectsWith = array_intersect($stopword, $target);
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					//$result[$i]['text'] = str_ireplace($stopword[$arrayKey], '', $result[$i]['text']);
					$result[$i]['text'] = preg_replace('/'.$stopword[$arrayKey].'/', '', $result[$i]['text']);
					
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
	
	private function splitSentence($words){
		preg_match_all('/\w+/', $words, $matches);
		return $matches;
	}
}

?>
