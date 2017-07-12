<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

defined('BASEPATH') OR exit('No direct script access allowed');

require_once (dirname(__FILE__) . "/WebSystem.php");

class Test extends WebSystem {
	
	public function __construct(){
		parent::__construct();
	}
	
	private function splitSentence($words){
		preg_match_all('/\w+/', $words, $matches);
		return $matches[0];
	}

	public function test(){
		$this->load->model('model_tweets_old');
		
		// for rule-based system
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
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
		
		$singkatan = []; $alay = []; $alay_replace = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($alay, $temp[0]);
			array_push($alay_replace, $temp[1]);
			$singkatan[$temp[0]] = $temp[1];
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		// input data
		$input = []; 
		$film_id = []; 
		$result = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/tweet_lama_nonreview.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($film_id, $temp[0]);
			array_push($input, $temp[1]);
		}
		
		for ($i=0; $i<sizeof($input); $i++){
			$result[$i]['status'] = 2;
			
			$input[$i] = html_entity_decode($input[$i], ENT_QUOTES | ENT_XML1, 'UTF-8');
			$editedResult = $input[$i];
			$editedResult = preg_replace('/(.)\\1+/', '$1', $editedResult); // delete double characters in a word
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult);  // delete everything except a-z & 0-9
			
			$input[$i] = $editedResult;
			
			// replace bahasa alay
			$words = $this->splitSentence($input[$i]);
			for ($a=1; $a<=3; $a++){
				if (array_intersect($alay, $words)) {
					$intersectStr = null;
					$intersectsWith = array_intersect($alay, $words);
					for ($j=0; $j<sizeof($intersectsWith); $j++){
						$arrayKey = key($intersectsWith);
						$input[$i] = preg_replace('/\b'.$alay[$arrayKey].'\b/u', $alay_replace[$arrayKey], $input[$i]);
						
						if ($intersectStr == null)
							$intersectStr = $alay[$arrayKey];
						else 
							$intersectStr .= ',' . $alay[$arrayKey];
						
						// get next key array
						next($intersectsWith);
					}
				}
			}
			
			// compare with lexicon data
			$words = $this->splitSentence($input[$i]);
			if (array_intersect($lexicon, $words)) {
				$result[$i]['status'] = 1;
				
				$intersectStr = null;
				$intersectsWith = array_intersect($lexicon, $words);
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
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($input); $i++){
			if ($result[$i]['status'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($input[$i]);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$probabilityofSentenceBeingPositive = $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$probabilityofSentenceBeingNegative = $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				if ($resultofAnalyzingSentence == "positive")
					$result[$i]['status'] = 1;
					
				$words = $this->splitSentence($input[$i]);
				if (array_intersect($listPos, $words))
					$result[$i]['status'] = 1;
				else if (array_intersect($listNeg, $words))
					$result[$i]['status'] = 0;
			}
			
			if (!$this->model_tweets_old->getTweetByText($input[$i])){
				$this->model_tweets_old->insertTweet($film_id[$i], $input[$i], $result[$i]['status'], 0,0);
			}
			
			//echo
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets_old->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets_old->getMovieCountPosTweet($allMovies[$i]['id']));	
		}		
	}

	public function test_resulting_in_71_percent(){
		$this->load->model('model_tweets_new');
		
		// for rule-based system
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
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
		
		$singkatan = []; $alay = []; $alay_replace = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($alay, $temp[0]);
			array_push($alay_replace, $temp[1]);
			$singkatan[$temp[0]] = $temp[1];
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		$result = [];
		$tweets = $this->model_tweets_new->getAllTweetsFrom('tweets_ori');
		
		// put into associative array
		for ($i=0; $i<sizeof($tweets); $i++){
			$result[$i]['film_id'] = $tweets[$i]['film_id'];
			$result[$i]['twitter_id'] = $tweets[$i]['twitter_id'];
			$result[$i]['text'] = strtolower($tweets[$i]['text']);
			$result[$i]['created_at'] = date("Y-m-d H:i:s", strtotime($tweets[$i]['created_at']));
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0; 
		}
		
		/* TEST DATA
			$result[0]['film_id'] = '46';
			$result[0]['twitter_id'] = '859301109927600128';
			$result[0]['text'] = '@santiwelehweleh cah wingi sing isuk² gembel nyang rs.kartini????????';
			$result[0]['created_at'] = date("Y-m-d H:i:s", strtotime('2017-05-02 06:58:44'));
			$result[0]['is_review'] = 0;
			$result[0]['is_positive'] = 0; 
		*/
		
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			//echo '<br/><h3>'.$i.'</h3> after html_entity_decode - '.$result[$i]['text'].'<br/>';
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$movie = $this->model_film->getFilm($result[$i]['film_id']);
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
			
			// replace bahasa alay
			$words = $this->splitSentence($result[$i]['text']);
			for ($a=1; $a<=3; $a++){
				if (array_intersect($alay, $words)) {
					$intersectStr = null;
					$intersectsWith = array_intersect($alay, $words);
					for ($j=0; $j<sizeof($intersectsWith); $j++){
						$arrayKey = key($intersectsWith);
						//$result[$i]['text'] = str_ireplace($alay[$arrayKey], $alay_replace[$arrayKey], $result[$i]['text']);
						$result[$i]['text'] = preg_replace('/\b'.$alay[$arrayKey].'\b/u', $alay_replace[$arrayKey], $result[$i]['text']);
						
						if ($intersectStr == null)
							$intersectStr = $alay[$arrayKey];
						else 
							$intersectStr .= ',' . $alay[$arrayKey];
						
						// get next key array
						next($intersectsWith);
					}
					
					if (!$this->model_tweets_new->getTweetByOri('tweets_replaced', $result[$i]['twitter_id'])){
						$this->model_tweets_new->insertTweetReplaced($result[$i]['twitter_id'], $result[$i]['text'], $intersectStr);
					}
				}
			}
			
			// compare with lexicon data
			$words = $this->splitSentence($result[$i]['text']);
			if (array_intersect($lexicon, $words)) {
				$result[$i]['is_review'] = 1;
				
				$intersectStr = null;
				$intersectsWith = array_intersect($lexicon, $words);
				for ($j=0; $j<sizeof($intersectsWith); $j++){
					$arrayKey = key($intersectsWith);
					if ($intersectStr == null)
						$intersectStr = $lexicon[$arrayKey];
					else 
						$intersectStr .= ',' . $lexicon[$arrayKey];
					
					// get next key array
					next($intersectsWith);
				}
				
				if (!$this->model_tweets_new->getTweetByOri('tweets_lexicon', $result[$i]['twitter_id'])){
					$this->model_tweets_new->insertTweetLexicon($result[$i]['twitter_id'], $intersectStr);
				}
			}
			
			//echo 'after compare with lexicon - '.$result[$i]['text'].'<br/>';
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
					
				$words = $this->splitSentence($result[$i]['text']);
				if (array_intersect($listPos, $words))
					$result[$i]['is_positive'] = 1;
				else if (array_intersect($listNeg, $words))
					$result[$i]['is_positive'] = 0;
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_final', $result[$i]['twitter_id'])){
				if (!$this->model_tweets_new->getTweetByText('tweets_final', $result[$i]['text']))
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 0);
				else 
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 1);
			}
			
			//echo $i.' - '.$result[$i]['text'].'<br/>';
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets_old->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets_old->getMovieCountPosTweet($allMovies[$i]['id']));	
		}		
	}
	
	public function replaceSingkatan(){
		// read singkatan from text file
		$alay = [];
		$alay_replace = [];
		$singkatan = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($alay, $temp[0]);
			array_push($alay_replace, $temp[1]);
			$singkatan[$temp[0]] = $temp[1];
		}
		
		// input data
		$singkatan = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/data_training_twitter_pos.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($singkatan, trim($activeLine));	
		}
		
		for ($i=0; $i<sizeof($singkatan); $i++){
			// decode html characters
			$editedResult = html_entity_decode($singkatan[$i], ENT_QUOTES | ENT_XML1, 'UTF-8');
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			$editedResult = preg_replace('/(.)\\1+/', '$1', $editedResult); // delete double characters in a word
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult);  // delete everything except a-z & 0-9
			
			$singkatan[$i] = $editedResult;
			
			// replace bahasa alay & singkatan
			$words = $this->splitSentence($singkatan[$i]);
			for ($a=1; $a<=3; $a++){
				if (array_intersect($alay, $words)) {
					$intersectStr = null;
					$intersectsWith = array_intersect($alay, $words);
					for ($j=0; $j<sizeof($intersectsWith); $j++){
						$arrayKey = key($intersectsWith);
						$singkatan[$i] = preg_replace('/\b'.$alay[$arrayKey].'\b/u', $alay_replace[$arrayKey], $singkatan[$i]);
						
						if ($intersectStr == null)
							$intersectStr = $alay[$arrayKey];
						else 
							$intersectStr .= ',' . $alay[$arrayKey];
						
						// get next key array
						next($intersectsWith);
					}
				}
			}
			
			echo $singkatan[$i].'<br/>';
		}
	}

	public function testTrainingDataTwitter(){ // result: 50 FP, 20 FN
		// for rule-based system
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
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
		
		$alay = [];
		$alay_replace = [];
		$singkatan = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			array_push($alay, $temp[0]);
			array_push($alay_replace, $temp[1]);
			$singkatan[$temp[0]] = $temp[1];
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		// input data
		$singkatan = []; $result = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/data_training_twitter_pos.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($singkatan, trim($activeLine));	
		}
		
		for ($i=0; $i<sizeof($singkatan); $i++){
			// set initial value
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0;
			
			// decode html characters
			$editedResult = html_entity_decode($singkatan[$i], ENT_QUOTES | ENT_XML1, 'UTF-8');
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			$editedResult = preg_replace('/(.)\\1+/', '$1', $editedResult); // delete double characters in a word
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult);  // delete everything except a-z & 0-9
			
			$singkatan[$i] = $editedResult;
			
			// replace bahasa alay & singkatan
			$words = $this->splitSentence($singkatan[$i]);
			for ($a=1; $a<=3; $a++){
				if (array_intersect($alay, $words)) {
					$intersectStr = null;
					$intersectsWith = array_intersect($alay, $words);
					for ($j=0; $j<sizeof($intersectsWith); $j++){
						$arrayKey = key($intersectsWith);
						$singkatan[$i] = preg_replace('/\b'.$alay[$arrayKey].'\b/u', $alay_replace[$arrayKey], $singkatan[$i]);
						
						if ($intersectStr == null)
							$intersectStr = $alay[$arrayKey];
						else 
							$intersectStr .= ',' . $alay[$arrayKey];
						
						// get next key array
						next($intersectsWith);
					}
				}
			}
			
			// compare with lexicon data
			if (array_intersect($lexicon, $words)) {
				$result[$i]['is_review'] = 1;
				
				$intersectStr = null;
				$intersectsWith = array_intersect($lexicon, $words);
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
		}
		
		// !!! === !!! === begin naive bayes
		echo '<table>';
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['is_review'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($singkatan[$i]);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$probabilityofSentenceBeingPositive = $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$probabilityofSentenceBeingNegative = $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				if ($resultofAnalyzingSentence == "positive")
					$result[$i]['is_positive'] = 1;
					
				$words = $this->splitSentence($singkatan[$i]);
				if (array_intersect($listPos, $words))
					$result[$i]['is_positive'] = 1;
				else if (array_intersect($listNeg, $words))
					$result[$i]['is_positive'] = 0;
			}
			
			echo '<tr><td>'.$singkatan[$i].'</td><td>'.$result[$i]['is_review'].'</td><td>'.$result[$i]['is_positive'].'</td></tr><br/>';
		}
		echo '</table>';
	}
	
	public function testDataTweetOri1($start = 0){
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
		//$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/lexicon-negative-ind.txt', 'negative', 1000); //training with negative data
		//$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/lexicon-positive-ind.txt', 'positive', 1000); //training with positive data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		
		$result = [];
		/*$tweets = $this->model_tweets->getAllTweetsFrom('tweets_ori');
		
		// put into associative array
		for ($i=0; $i<sizeof($tweets); $i++){
			$result[$i]['film_id'] = $tweets[$i]['film_id'];
			$result[$i]['twitter_id'] = $tweets[$i]['twitter_id'];
			$result[$i]['text'] = strtolower($tweets[$i]['text']);
			$result[$i]['created_at'] = date("Y-m-d H:i:s", strtotime($tweets[$i]['created_at']));
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0; 
		}*/
		
			$result[0]['film_id'] = '9';
			$result[0]['twitter_id'] = '859219794603773952';
			$result[0]['text'] = 'syahdu � � � � � � � � � � #flower #flowerlovers #redroses #beauty #macro_captures… https://t.co/qoqyhtiwnf';
			$result[0]['created_at'] = date("Y-m-d H:i:s", strtotime('2017-05-02 01:35:37'));
			$result[0]['is_review'] = 0;
			$result[0]['is_positive'] = 0; 
		
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			echo 'after html_entity_decode - '.$result[$i]['text'].'<br/>';
			
			// split camel case words
			//$result[$i]['text'] = preg_split('/(?=[A-Z])/', $result[$i]['text']);
			
			// replace bahasa alay
			$words = $this->splitSentence($result[$i]['text']);
			if (array_intersect($alay, $words)) {
				$intersectStr = null;
				$intersectsWith = array_intersect($alay, $words);
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
			
			echo 'after replace bahasa alay - '.$result[$i]['text'].'<br/>';
			
			// compare with lexicon data
			$words = $this->splitSentence($result[$i]['text']);
			if (array_intersect($lexicon, $words)) {
				$result[$i]['is_review'] = 1;
				
				$intersectStr = null;
				$intersectsWith = array_intersect($lexicon, $words);
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
			
			echo 'after compare with lexicon - '.$result[$i]['text'].'<br/>';
			
			// feature reduction --> delete username, url, hashtag, punctuations ------------------------ >>>>>> TEXT ADA YANG NULL !!!! DISINI
			$movie = $this->model_film->getFilm($result[$i]['film_id']);
			$title = $movie[0]['title'];
			
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
			
			echo 'after feature reduction - '.$result[$i]['text'].'<br/>';
			
			/*// delete stopword
			$words = $this->splitSentence($result[$i]['text']);
			if (array_intersect($stopword, $words)) {
				$intersectStr = null;
				$intersectsWith = array_intersect($stopword, $words);
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
			}*/
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
				if (!$this->model_tweets->textExistInTweetFinal($result[$i]['text']))
					$this->model_tweets->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 0);
				else 
					$this->model_tweets->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 1);
			}
			
			echo $i.' - '.$result[$i]['text'].'<br/>';
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets->getMovieCountPosTweet($allMovies[$i]['id']));	
		}		
	}
	
	public function pagination($start = 0){
		// get data from db
		$config['base_url'] = site_url('test/testDataTweetOri');
		$config['total_rows'] = $this->model_tweets->getCountTweet('tweets_ori');
		$config['per_page'] = 125;
		
		//config for bootstrap pagination class integration
        $config['num_links'] = 9;
		$config['page_query_string'] = TRUE;

		$config['query_string_segment'] = 'page';

		$config['full_tag_open'] = '<div class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></div><!--pagination-->';

		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&larr; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';

		$config['anchor_class'] = 'follow_link';
		
		$this->pagination->initialize($config);
		
		$tweets = $this->model_tweets->get_data('tweets_ori', $config['per_page'], $start);
		$paging = $this->pagination->create_links();
		
		echo '<br/> <div class="row">
			        <div class="col-md-12 text-center">
			            '.$paging.'
			        </div>
			    	</div>';
	}

	public function oldCheckNewComingSoonMovies(){ // doesnt work anymore, YQL is abandoned
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
								$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],NULL,$getdata['status']);
					}
				}
		    }
		}
	}

	public function oldCheckNewNowPlayingMovies(){ // doesnt work anymore, YQL is abandoned
		$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
	    $yql_query = "select * from html where url='http://www.21cineplex.com/nowplaying/'";
	    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
	    
	    // make call with cURL
	    $session = curl_init($yql_query_url);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	    $json = curl_exec($session);
	    
	    // convert JSON to PHP object
	    $phpObj =  json_decode($json); 
	    
	    if ($phpObj->query->results){
			$pdata = $phpObj->query->results->body->div[0]->div[1]->div[3]->div[0]->div[0]->div[1]->ul->li;
			
			// if there's a title in db with status NOW PLAYING, but doesnt exist in the data we got from 21, then change the movie's status into OLD
			$moviesinDB = $this->model_film->getOnGoingMovies();	
			for ($j=0; $j<sizeof($moviesinDB); $j++){
				$isStillPlaying = FALSE;
				for ($i=1; $i<sizeof($pdata)-2; $i++){
					if (mb_strtolower($moviesinDB[$j]['title']) == mb_strtolower($pdata[$i]->a->img->title)){
						$isStillPlaying = TRUE;
						break;
					}
			    }
				if (!$isStillPlaying) // if not exist, change status into old
					$this->model_film->updateStatusFilm($moviesinDB[$j]['id'], 2);
			}
			
			// explode each movie to get informations
			for ($i=1; $i<sizeof($pdata)-2; $i++){
				$getdata['title'] = $pdata[$i]->a->img->title;
				$getdata['poster'] = str_replace('100x147','300x430',$pdata[$i]->a->img->src);
				
				// so if the movie title has any (..) in it, we dismiss it (because it's definitely a double)
				if (strpos($getdata['title'], '(') == FALSE){
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
				}
		    }
		}
	}

}
