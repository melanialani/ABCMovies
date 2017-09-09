<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

defined('BASEPATH') OR exit('No direct script access allowed');

include_once( dirname(dirname(__FILE__)) . '/libraries/TwitterAPIExchange.php' );
include_once( dirname(dirname(__FILE__)) . '/libraries/SentimentAnalyzer.php' );
require_once( dirname(dirname(__FILE__)) . '/libraries/imdb.class.php' );

class Test extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function testOld(){
		// for rule-based system
		$commonWords = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$commonWords[$idx]['text'] = $temp[0];
			$commonWords[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$nonReview = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words-nonreview.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$nonReview[$idx]['text'] = $temp[0];
			$nonReview[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon-minus-common_words_review.txt', "r"); 
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
		}
		
		$singkatan = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$singkatan[$idx]['short'] = $temp[0];
			$singkatan[$idx]['long'] = $temp[1];
			$idx++;
		}
		
		$listPos = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/sentiment_pos.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$listPos[$idx]['text'] = $temp[0];
			$listPos[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$listNeg = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/sentiment_neg.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$listNeg[$idx]['text'] = $temp[0];
			$listNeg[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		
		$this->model_dataset->writeDataset('neg'); 
		$this->model_dataset->writeDataset('pos');
		
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		// put into associative array
		$result = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/tweet_lama.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$result[$idx]['film_id'] = $temp[0];
			$result[$idx]['text'] = $temp[1];
			$result[$idx]['status'] = 2;
			$result[$idx]['truth_rule'] = $temp[2];
			$result[$idx]['truth_naive'] = $temp[3];
			
			//$result[$idx]['film_id'] = null;
			//$result[$idx]['text'] = trim(strtolower($activeLine));
			
			$result[$idx]['twitter_id'] = null;
			$result[$idx]['is_review'] = 0;
			$result[$idx]['is_positive'] = 0; 
			$result[$idx]['replaced'] = null; 
			$result[$idx]['lexicon'] = null; 
			$result[$idx]['score'] = 0; 
			$result[$idx]['intersect'] = null;
			$result[$idx]['positivity'] = 0;
			$result[$idx]['negativity'] = 0;
			$idx++;
		}
		
		/*// put into associative array
		$result = [];
		$tweets = $this->model_tweets_new->getAllTweetsFrom('tweets_ori');
		for ($i=0; $i<sizeof($tweets); $i++){
			$result[$i]['id'] = $tweets[$i]['id'];
			$result[$i]['film_id'] = $tweets[$i]['film_id'];
			$result[$i]['twitter_id'] = $tweets[$i]['twitter_id'];
			$result[$i]['text'] = strtolower($tweets[$i]['text']);
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0; 
			
			$result[$i]['replaced'] = null; 
			$result[$i]['lexicon'] = null; 
			$result[$i]['score'] = 0; 
			$result[$i]['intersect'] = null;
			$result[$i]['positivity'] = 0;
			$result[$i]['negativity'] = 0;
		}
		
		$nilai = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/tweet_baru.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$idx = $temp[0];
			$nilai[$idx]['yes_review'] = $temp[1];
			$nilai[$idx]['yes_positive'] = $temp[2];
		}*/
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$movie = $this->model_film->getFilm($result[$i]['film_id']);
			$title = $movie[0]['title'];
			
			$result[$i]['text'] = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $result[$i]['text']);
			$result[$i]['text'] = str_ireplace($title, 'JUDULFILM', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/(.)\\1+/', '$1', $result[$i]['text']); // delete double characters in a word
			$result[$i]['text'] = preg_replace('/[^A-Za-z0-9]/', ' ', $result[$i]['text']);  // delete everything except a-z & 0-9
			
			/*if (!$this->model_tweets_new->getTweetByOri('tweets_regex', $result[$i]['twitter_id'])){
				$this->model_tweets_new->insertTweetRegex($result[$i]['twitter_id'], $result[$i]['text']);
			}*/
			
			// replace bahasa bukan baku
			$originalStr = NULL;
			for ($j=0; $j<sizeof($singkatan); $j++){
				if (strpos($result[$i]['text'], $singkatan[$j]['short']) == TRUE){
					if (preg_match('/\b'.$singkatan[$j]['short'].'\b/', $result[$i]['text'])) {
						$result[$i]['text'] = preg_replace('/\b'.$singkatan[$j]['short'].'\b/', $singkatan[$j]['long'], $result[$i]['text']);
						if ($originalStr == NULL) $originalStr = $singkatan[$j]['short'];
						else $originalStr .= ',' . $singkatan[$j]['short'];
					}
				}
			}
			
			$result[$i]['replaced'] = $originalStr; 
			/*if (!$this->model_tweets_new->getTweetByOri('tweets_replaced', $result[$i]['twitter_id']) && $originalStr != NULL){
				$this->model_tweets_new->insertTweetReplaced($result[$i]['twitter_id'], $result[$i]['text'], $originalStr);
			}*/
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			$value = 0;
			$intersectStr = NULL;
			
			// compare with lexicon data
			for ($j=0; $j<sizeof($lexicon); $j++){
				if (strpos($result[$i]['text'], $lexicon[$j]) == TRUE){
					if (preg_match('/\b'.$lexicon[$j].'\b/', $result[$i]['text'])) { 
						$value++; // for every matched words, give 1
						if ($intersectStr == NULL) $intersectStr = $lexicon[$j];
						else $intersectStr .= ',' . $lexicon[$j];
					}
				}
			}
			
			// compare with review common words data
			for ($j=0; $j<sizeof($commonWords); $j++){
				if (strpos($result[$i]['text'], $commonWords[$j]['text']) == TRUE){
					if (preg_match('/\b'.$commonWords[$j]['text'].'\b/', $result[$i]['text'])) { 
						$value += $commonWords[$j]['score'];
						if ($intersectStr == NULL) $intersectStr = $commonWords[$j]['text'];
						else $intersectStr .= ',' . $commonWords[$j]['text'];
					}
				}
			}
			
			// compare with non-review common words data
			for ($j=0; $j<sizeof($nonReview); $j++){
				if (strpos($result[$i]['text'], $nonReview[$j]['text']) == TRUE){
					if (preg_match('/\b'.$nonReview[$j]['text'].'\b/', $result[$i]['text'])) {
						$value += $nonReview[$j]['score'];
						if ($intersectStr == NULL) $intersectStr = '-'.$nonReview[$j]['text'];
						else $intersectStr .= ',-' . $nonReview[$j]['text'];
					}
				}
			}
			
			//if (!$this->model_tweets_new->getTweetByOri('tweets_lexicon', $result[$i]['twitter_id']) && $value >= 10){
			if ($value >= 10){
				$result[$i]['is_review'] = 1;
				$result[$i]['lexicon'] = $intersectStr; 
				$result[$i]['score'] = $value; 
				
				//$this->model_tweets_new->insertTweetLexicon($result[$i]['twitter_id'], $intersectStr, $value);
			}
			
			// set default value for positivity & negativity
			for ($j=0; $j<sizeof($listPos); $j++){
				if (strpos($result[$i]['text'], $listPos[$j]['text']) == TRUE){
					if (preg_match('/\b'.$listPos[$j]['text'].'\b/', $result[$i]['text'])){
						$result[$i]['positivity'] += $listPos[$j]['score']/10;
						$result[$i]['negativity'] -= $listPos[$j]['score']/10;
						$result[$i]['intersect'] .= $listPos[$j]['text'].',';
					}
				}
			}
			for ($j=0; $j<sizeof($listNeg); $j++){
				if (strpos($result[$i]['text'], $listNeg[$j]['text']) == TRUE){
					if (preg_match('/\b'.$listNeg[$j]['text'].'\b/', $result[$i]['text'])){
						$result[$i]['negativity'] += $listNeg[$j]['score']/10;
						$result[$i]['positivity'] -= $listNeg[$j]['score']/10;
						$result[$i]['intersect'] .= '-'.$listNeg[$j]['text'].',';
					}
				}
			}
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['is_review'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($result[$i]['text']);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$result[$i]['positivity'] += $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$result[$i]['negativity'] += $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				// set is_positive value
				if ($result[$i]['positivity'] > $result[$i]['negativity'])
					//$result[$i]['is_positive'] = 1;
					$result[$i]['status'] = 1;
				else 
					//$result[$i]['is_positive'] = 0;
					$result[$i]['status'] = 0;
			}
			
			if (!$this->model_tweets_old->getTweetByText($result[$i]['text'])){
				$this->model_tweets_old->insertTweet($result[$i]['film_id'], $result[$i]['text'], $result[$i]['status'], $result[$i]['truth_rule'], $result[$i]['truth_naive']);
			}
			
			/*if (!$this->model_tweets_new->getTweetByOri('tweets_final', $result[$i]['twitter_id'])){
				if (!$this->model_tweets_new->getTweetByText('tweets_final', $result[$i]['text']))
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], $nilai[$result[$i]['id']]['yes_review'], $nilai[$result[$i]['id']]['yes_positive'], 1, 0, $result[$i]['positivity'], $result[$i]['negativity']);
				else 
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], $nilai[$result[$i]['id']]['yes_review'], $nilai[$result[$i]['id']]['yes_positive'], 1, 1, $result[$i]['positivity'], $result[$i]['negativity']);
			}*/
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets_old->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets_old->getMovieCountPosTweet($allMovies[$i]['id']));	
		}
	}
	
	public function testNew(){
		// for rule-based system
		$commonWords = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$commonWords[$idx]['text'] = $temp[0];
			$commonWords[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$nonReview = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words-nonreview.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$nonReview[$idx]['text'] = $temp[0];
			$nonReview[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon-minus-common_words_review.txt', "r"); 
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
		}
		
		$singkatan = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$singkatan[$idx]['short'] = $temp[0];
			$singkatan[$idx]['long'] = $temp[1];
			$idx++;
		}
		
		$listPos = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/sentiment_pos.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$listPos[$idx]['text'] = $temp[0];
			$listPos[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$listNeg = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/sentiment_neg.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$listNeg[$idx]['text'] = $temp[0];
			$listNeg[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //training with positive data	
		
		$this->model_dataset->writeDataset('neg'); 
		$this->model_dataset->writeDataset('pos');
		
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		/*// put into associative array
		$result = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/tweet_lama.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$result[$idx]['film_id'] = $temp[0];
			$result[$idx]['text'] = $temp[1];
			$result[$idx]['status'] = 2;
			$result[$idx]['truth_rule'] = $temp[2];
			$result[$idx]['truth_naive'] = $temp[3];
			
			//$result[$idx]['film_id'] = null;
			//$result[$idx]['text'] = trim(strtolower($activeLine));
			
			$result[$idx]['twitter_id'] = null;
			$result[$idx]['is_review'] = 0;
			$result[$idx]['is_positive'] = 0; 
			$result[$idx]['replaced'] = null; 
			$result[$idx]['lexicon'] = null; 
			$result[$idx]['score'] = 0; 
			$result[$idx]['intersect'] = null;
			$result[$idx]['positivity'] = 0;
			$result[$idx]['negativity'] = 0;
			$idx++;
		}*/
		
		// put into associative array
		$result = [];
		$tweets = $this->model_tweets_new->getAllTweetsFrom('tweets_ori');
		for ($i=0; $i<sizeof($tweets); $i++){
			$result[$i]['id'] = $tweets[$i]['id'];
			$result[$i]['film_id'] = $tweets[$i]['film_id'];
			$result[$i]['twitter_id'] = $tweets[$i]['twitter_id'];
			$result[$i]['text'] = strtolower($tweets[$i]['text']);
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0; 
			
			$result[$i]['replaced'] = null; 
			$result[$i]['lexicon'] = null; 
			$result[$i]['score'] = 0; 
			$result[$i]['intersect'] = null;
			$result[$i]['positivity'] = 0;
			$result[$i]['negativity'] = 0;
		}
		
		$nilai = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/tweet_baru.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$idx = $temp[0];
			$nilai[$idx]['yes_review'] = $temp[1];
			$nilai[$idx]['yes_positive'] = $temp[2];
		}
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$movie = $this->model_film->getFilm($result[$i]['film_id']);
			$title = $movie[0]['title'];
			
			$result[$i]['text'] = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $result[$i]['text']);
			$result[$i]['text'] = str_ireplace($title, 'JUDULFILM', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/(.)\\1+/', '$1', $result[$i]['text']); // delete double characters in a word
			$result[$i]['text'] = preg_replace('/[^A-Za-z0-9]/', ' ', $result[$i]['text']);  // delete everything except a-z & 0-9
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_regex', $result[$i]['twitter_id'])){
				$this->model_tweets_new->insertTweetRegex($result[$i]['twitter_id'], $result[$i]['text']);
			}
			
			// replace bahasa bukan baku
			$originalStr = NULL;
			for ($j=0; $j<sizeof($singkatan); $j++){
				if (strpos($result[$i]['text'], $singkatan[$j]['short']) == TRUE){
					if (preg_match('/\b'.$singkatan[$j]['short'].'\b/', $result[$i]['text'])) {
						$result[$i]['text'] = preg_replace('/\b'.$singkatan[$j]['short'].'\b/', $singkatan[$j]['long'], $result[$i]['text']);
						if ($originalStr == NULL) $originalStr = $singkatan[$j]['short'];
						else $originalStr .= ',' . $singkatan[$j]['short'];
					}
				}
			}
			
			$result[$i]['replaced'] = $originalStr; 
			if (!$this->model_tweets_new->getTweetByOri('tweets_replaced', $result[$i]['twitter_id']) && $originalStr != NULL){
				$this->model_tweets_new->insertTweetReplaced($result[$i]['twitter_id'], $result[$i]['text'], $originalStr);
			}
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			$value = 0;
			$intersectStr = NULL;
			
			// compare with lexicon data
			for ($j=0; $j<sizeof($lexicon); $j++){
				if (strpos($result[$i]['text'], $lexicon[$j]) == TRUE){
					if (preg_match('/\b'.$lexicon[$j].'\b/', $result[$i]['text'])) { 
						$value++; // for every matched words, give 1
						if ($intersectStr == NULL) $intersectStr = $lexicon[$j];
						else $intersectStr .= ',' . $lexicon[$j];
					}
				}
			}
			
			// compare with review common words data
			for ($j=0; $j<sizeof($commonWords); $j++){
				if (strpos($result[$i]['text'], $commonWords[$j]['text']) == TRUE){
					if (preg_match('/\b'.$commonWords[$j]['text'].'\b/', $result[$i]['text'])) { 
						$value += $commonWords[$j]['score'];
						if ($intersectStr == NULL) $intersectStr = $commonWords[$j]['text'];
						else $intersectStr .= ',' . $commonWords[$j]['text'];
					}
				}
			}
			
			// compare with non-review common words data
			for ($j=0; $j<sizeof($nonReview); $j++){
				if (strpos($result[$i]['text'], $nonReview[$j]['text']) == TRUE){
					if (preg_match('/\b'.$nonReview[$j]['text'].'\b/', $result[$i]['text'])) {
						$value += $nonReview[$j]['score'];
						if ($intersectStr == NULL) $intersectStr = '-'.$nonReview[$j]['text'];
						else $intersectStr .= ',-' . $nonReview[$j]['text'];
					}
				}
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_lexicon', $result[$i]['twitter_id']) && $value >= 10){
			//if ($value >= 10){
				$result[$i]['is_review'] = 1;
				$result[$i]['lexicon'] = $intersectStr; 
				$result[$i]['score'] = $value; 
				
				$this->model_tweets_new->insertTweetLexicon($result[$i]['twitter_id'], $intersectStr, $value);
			}
			
			// set default value for positivity & negativity
			for ($j=0; $j<sizeof($listPos); $j++){
				if (strpos($result[$i]['text'], $listPos[$j]['text']) == TRUE){
					if (preg_match('/\b'.$listPos[$j]['text'].'\b/', $result[$i]['text'])){
						$result[$i]['positivity'] += $listPos[$j]['score']/10;
						$result[$i]['negativity'] -= $listPos[$j]['score']/10;
						$result[$i]['intersect'] .= $listPos[$j]['text'].',';
					}
				}
			}
			for ($j=0; $j<sizeof($listNeg); $j++){
				if (strpos($result[$i]['text'], $listNeg[$j]['text']) == TRUE){
					if (preg_match('/\b'.$listNeg[$j]['text'].'\b/', $result[$i]['text'])){
						$result[$i]['negativity'] += $listNeg[$j]['score']/10;
						$result[$i]['positivity'] -= $listNeg[$j]['score']/10;
						$result[$i]['intersect'] .= '-'.$listNeg[$j]['text'].',';
					}
				}
			}
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['is_review'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($result[$i]['text']);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$result[$i]['positivity'] += $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$result[$i]['negativity'] += $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				// set is_positive value
				if ($result[$i]['positivity'] > $result[$i]['negativity'])
					$result[$i]['is_positive'] = 1;
					//$result[$i]['status'] = 1;
				else 
					$result[$i]['is_positive'] = 0;
					//$result[$i]['status'] = 0;
			}
			
			/*if (!$this->model_tweets_old->getTweetByText($result[$i]['text'])){
				$this->model_tweets_old->insertTweet($result[$i]['film_id'], $result[$i]['text'], $result[$i]['status'], $result[$i]['truth_rule'], $result[$i]['truth_naive']);
			}*/
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_final', $result[$i]['twitter_id'])){
				if (!$this->model_tweets_new->getTweetByText('tweets_final', $result[$i]['text']))
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], $nilai[$result[$i]['id']]['yes_review'], $nilai[$result[$i]['id']]['yes_positive'], 1, 0, $result[$i]['positivity'], $result[$i]['negativity']);
				else 
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], $nilai[$result[$i]['id']]['yes_review'], $nilai[$result[$i]['id']]['yes_positive'], 1, 1, $result[$i]['positivity'], $result[$i]['negativity']);
			}
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets_old->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets_old->getMovieCountPosTweet($allMovies[$i]['id']));	
		}
	}
	
	public function calculation(){
		$data['tp'] = sizeof($this->model_tweets_new->getBoth('tp'));
		$data['tn'] = sizeof($this->model_tweets_new->getBoth('tn'));
		$data['fp'] = sizeof($this->model_tweets_new->getBoth('fp'));
		$data['fn'] = sizeof($this->model_tweets_new->getBoth('fn'));
		$data['accuracy'] = (($data['tn']+$data['tp'])*100) / ($data['tn']+$data['tp']+$data['fn']+$data['fp']);
		$data['precision'] = $data['tp']*100/($data['tp']+$data['fp']);
		$data['recall'] = $data['tn']*100/($data['tn']+$data['fn']);
		$data['fmeasure'] = (2*$data['precision']*$data['recall'])/($data['precision']+$data['recall']);
		
		$data['review_tp'] = sizeof($this->model_tweets_new->getBoth('tr'));
		$data['review_tn'] = sizeof($this->model_tweets_new->getBoth('tnr'));
		$data['review_fp'] = sizeof($this->model_tweets_new->getBoth('fr'));
		$data['review_fn'] = sizeof($this->model_tweets_new->getBoth('fnr'));
		$data['review_accuracy'] = (($data['review_tn']+$data['review_tp'])*100) / ($data['review_tn']+$data['review_tp']+$data['review_fn']+$data['review_fp']);
		$data['review_precision'] = $data['review_tp']*100/($data['review_tp']+$data['review_fp']);
		$data['review_recall'] = $data['review_tn']*100/($data['review_tn']+$data['review_fn']);
		$data['review_fmeasure'] = (2*$data['review_precision']*$data['review_recall'])/($data['review_precision']+$data['review_recall']);
		
		echo '<h3>Positive/Negative</h3>';
		echo 'TP: '.$data['tp'].'<br>';
		echo 'TN: '.$data['tn'].'<br>';
		echo 'FP: '.$data['fp'].'<br>';
		echo 'FN: '.$data['fn'].'<br>';
		echo 'Accuracy: '.$data['accuracy'].'<br>';
		echo 'Precision: '.$data['precision'].'<br>';
		echo 'Recall: '.$data['recall'].'<br>';
		echo 'F-Measure: '.$data['fmeasure'].'<br>';
		echo '<hr>';
		echo '<h3>Review/Non-review</h3>';
		echo 'TP: '.$data['review_tp'].'<br>';
		echo 'TN: '.$data['review_tn'].'<br>';
		echo 'FP: '.$data['review_fp'].'<br>';
		echo 'FN: '.$data['review_fn'].'<br>';
		echo 'Accuracy: '.$data['review_accuracy'].'<br>';
		echo 'Precision: '.$data['review_precision'].'<br>';
		echo 'Recall: '.$data['review_recall'].'<br>';
		echo 'F-Measure: '.$data['review_fmeasure'].'<br>';
		
		/*echo '<hr><h1>FN</h1>';
		$data['tweets'] = $this->model_tweets_new->getBoth('fn');
		for($i=0; $i<sizeof($data['tweets']); $i++) {
			echo $data['tweets'][$i]['text'].'<br/>';
		}*/
	}
	
	public function testEmail($newMovie = NULL){
		$allAdmin = $this->model_user->getAdminEmail();
		
		$newMovie[0]['title'] = 'Title 1';
		$newMovie[0]['status'] = 'Now Playing';
	    $newMovie[1]['title'] = 'Title 2';
		$newMovie[1]['status'] = 'Coming Soon';
	    
	    for ($j=0; $j<sizeof($allAdmin); $j++){
			$email = $allAdmin[$j]['email'];
		    if (valid_email($email)){  // check is email addrress valid or no
		    	$config['useragent'] = "CodeIgniter";
				$config['protocol'] = "smtp";
				//$config['smtp_host'] = "ssl://smtp.gmail.com";
				$config['smtp_host'] = "ssl://srv33.niagahoster.com";
				$config['smtp_port'] = "465";
				$config['smtp_timeout'] = "30";
				//$config['smtp_user'] = "adm.abcmovies@gmail.com"; 
				$config['smtp_user'] = "admin@show.web.id"; 
				$config['smtp_pass'] = "adminadminadmin";
				$config['charset'] = "utf-8";
				$config['mailtype'] = "html";
				$config['newline'] = "\r\n";
				
				$this->email->initialize($config);
				
				$this->email->from($config['smtp_user'], 'Admin of ABC Movies');
				$this->email->to($email);
				$this->email->subject('Film baru ditemukan');
				
				$message  = 'Hai admin, ada film baru lho di website Cinema 21. Tolong cek ya di master film. <br/><br/>';
		      	$message .= 'Daftar film baru yang ditemukan: <br/>';
		      	for ($i=0; $i<sizeof($newMovie); $i++){
					$message .= ($i+1).'. '.$newMovie[$i]['title'].' - '.$newMovie[$i]['status'].'<br/>';
				}
		      	
		      	$this->email->message($message);
				
		      	// try send mail ant if not able print debug
		      	if ( ! $this->email->send()){
		        	echo "<hr>Email not sent <br/>".$this->email->print_debugger().'<hr>';
		      	} else echo "Email successfully sent to ($email) <br/>";
		    } else echo "Email address ($email) not correct <br/>";
		}   
	}
	
	public function checkNewMovies(){
		$newMovie = NULL; $idx = 0;
		
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
	   	
	   	$nowPlaying = [];
	   	
	   	$title = explode('title', $playing[0]);
	   	$img = explode('src', $playing[0]);
		for ($i=1; $i<sizeof($title); $i++){
			$trueTitle = explode('"', $title[$i]);
			$trueImg = explode('"', $img[$i]);
			$nowPlaying[$i]['title'] = $trueTitle[1];
			$nowPlaying[$i]['img'] = $trueImg[1];
		}
	    
	    if ($nowPlaying != NULL){
			// if there's a title in db with status NOW PLAYING, but doesnt exist in the data we got from 21, then change the movie's status into OLD
			$moviesinDB = $this->model_film->getOnGoingMovies();	
			for ($j=0; $j<sizeof($moviesinDB); $j++){
				$isStillPlaying = FALSE;
				for ($i=1; $i<sizeof($nowPlaying); $i++){
					//if (mb_strtolower(preg_replace('/[^A-Za-z0-9]/', ' ', $nowPlaying[$i]['title'])) == mb_strtolower(preg_replace('/[^A-Za-z0-9]/', ' ', $moviesinDB[$j]['title']))){
					if (mb_strtolower(html_entity_decode($nowPlaying[$i]['title'], ENT_QUOTES | ENT_XML1, 'UTF-8')) == mb_strtolower($moviesinDB[$j]['title'])){
						$isStillPlaying = TRUE;
						break;
					}
			    }
				if (!$isStillPlaying) // if not exist, change status into old
					$this->model_film->updateStatusFilm($moviesinDB[$j]['id'], 2);
			}
			
			// explode each movie to get informations
			for ($i=1; $i<sizeof($nowPlaying); $i++){
				$getdata['title'] = html_entity_decode($nowPlaying[$i]['title'], ENT_QUOTES | ENT_XML1, 'UTF-8');
				$getdata['poster'] = htmlspecialchars_decode(str_replace('100x147','300x430',$nowPlaying[$i]['img']));
				
				// so if the movie title has any 3D or IMAX in it, we dismiss it (because it's definitely a double)
				if (strpos($getdata['title'], '3D') == FALSE && strpos($getdata['title'], 'IMAX') == FALSE){
					$inDB = $this->model_film->getFilmByTitleChecked($getdata['title']);
					if ($inDB){ // already in db, just change status
						$this->model_film->updateStatusFilm($inDB[0]['id'], 1);
					} else { // not in db, add it
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
						$getdata['param'] = '#'.str_replace(' ', '', $getdata['title']).','.$getdata['title'];
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
						        } else if ($aItem['name'] == 'Url' && strpos($aItem['name'], 'imdb') == TRUE){
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
								if ($getdata['summary'] != NULL && $getdata['summary'] != ''){
									$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($getdata['summary']).'&lang=en-id';
									$json = file_get_contents($url);
									$yandex = json_decode($json);
									if ($yandex->code == 200) $getdata['summary'] = $yandex->text[0];
								}
						    }
						} 
						
						// get metascore
						$title = strtolower(trim($getdata['title']));
						$title = preg_replace('/[^A-Za-z0-9]/', ' ', $title);
						$title = str_ireplace('  ', '-', $title);
						$title = str_ireplace(' ', '-', $title);
						
						$site = "http://www.metacritic.com/movie/".$title;
						$yql = "select * from htmlstring where url='" . $site . "'";
						$resturl = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
						
						// make call with cURL
						$session = curl_init($resturl);
						curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
						$json = curl_exec($session);
						
						// convert JSON to PHP object
						$phpObj =  json_decode($json); 
						
						$arrku = (array) $phpObj->query->diagnostics->url[1];
						if (!array_key_exists('http-status-code', $arrku)){ 
							$result = $phpObj->query->results->result;
							if (strpos($result, 'ratingValue') == TRUE){
								$result = explode('ratingValue" : "', $result);
								$result = explode('"', $result[1]);
								$getdata['metascore'] = $result[0];
							} // else : not rated
						} // else : page not found
						
						if (!$this->model_film->getFilmByTitle($getdata['title']))
							$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
							$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],$getdata['param'],$getdata['status']);
					
						$newMovie[$idx]['title'] = $getdata['title'];
						$newMovie[$idx]['status'] = 'Now Playing';
						$idx++;
					}
				}
		    }
		}
		
		/***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****
	     ***** ***** ***** ***** ***** ***** ***** ***** CHECK COMING SOON ***** ***** ***** ***** ***** ***** ***** ***** *****
	     ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****/
	    
	    // new code YQL
		$site = "http://www.21cineplex.com/comingsoon/";
		$yql = "select * from htmlstring where url='" . $site . "'";
		$resturl = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
	    
	    // make call with cURL
	    $session = curl_init($resturl);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	    $json = curl_exec($session);
	    
	    // convert JSON to PHP object
	    $phpObj =  json_decode($json); 
	    
	    $coming = $phpObj->query->results->result;
		$coming = explode('pdata=[', $coming);
		$coming = explode('movieTitle":"', $coming[1]); // explode to get each movie
		//echo '<pre>'; print_r($coming); echo '</pre>';
		
		$comingSoon = [];
		
		// explode each movie to get informations
		for ($i=1; $i<sizeof($coming); $i++){
			$info = explode('","movieImage":"', $coming[$i]);				
			$comingSoon[$i-1]['title'] = $info[0];									
			
			$info = explode('","movieTrailerFile":', $info[1]);				
			$info = explode('.', $info[0]);
			$comingSoon[$i-1]['img'] = 'http://www.21cineplex.com/data/gallery/pictures/'.$info[0].'_300x430.jpg';
		}
	    
	    /*$coming = $phpObj->query->results->result;
		$coming = explode('<div id="coming">', $coming);
		$coming = explode('</div>', $coming[1]);
	   	
	   	$comingSoon = [];
	   	
	   	$title = explode('title', $coming[0]);
	   	$img = explode('src', $coming[0]);
		for ($i=1; $i<sizeof($title); $i++){
			$trueTitle = explode('"', $title[$i]);
			$trueImg = explode('"', $img[$i]);
			$comingSoon[$i]['title'] = $trueTitle[1];
			$comingSoon[$i]['img'] = $trueImg[1];
		}*/
	    
	    if ($comingSoon != NULL){
	    	// if there's a title in db with status COMING SOON, but doesnt exist in the data we got from 21, then change the movie's status into OLD
			$moviesinDB = $this->model_film->getComingSoonMovies();	
			for ($j=0; $j<sizeof($moviesinDB); $j++){
				$isComingSoon = FALSE;
				for ($i=1; $i<sizeof($nowPlaying); $i++){
					//if (mb_strtolower(preg_replace('/[^A-Za-z0-9]/', ' ', $nowPlaying[$i]['title'])) == mb_strtolower(preg_replace('/[^A-Za-z0-9]/', ' ', $moviesinDB[$j]['title']))){
					if (mb_strtolower(html_entity_decode($comingSoon[$i]['title'], ENT_QUOTES | ENT_XML1, 'UTF-8')) == mb_strtolower($moviesinDB[$j]['title'])){
						$isComingSoon = TRUE;
						break;
					}
			    }
				if (!$isComingSoon) // if not exist, change status into old
					$this->model_film->updateStatusFilm($moviesinDB[$j]['id'], 2);
			}
			
			// explode each movie to get informations
			for ($i=1; $i<sizeof($comingSoon); $i++){
				$getdata['title'] = html_entity_decode($comingSoon[$i]['title'], ENT_QUOTES | ENT_XML1, 'UTF-8');
				$getdata['poster'] = htmlspecialchars_decode($comingSoon[$i]['img']);
				
				// so if the movie title has any 3D or IMAX in it, we dismiss it (because it's definitely a double)
				if (strpos($getdata['title'], '3D') == FALSE && strpos($getdata['title'], 'IMAX') == FALSE){
					// check if title already exist in database
					$inDB = $this->model_film->getFilmByTitleChecked($getdata['title']);
					if ($inDB){ // already in db, just change status
						$this->model_film->updateStatusFilm($inDB[0]['id'], 0);
					} else { // not in db, add it
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
						$getdata['param'] = '#'.str_replace(' ', '', $getdata['title']).','.$getdata['title'];
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
						        } else if ($aItem['name'] == 'Url' && strpos($aItem['name'], 'imdb') == TRUE){
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
								if ($getdata['summary'] != NULL && $getdata['summary'] != ''){
									$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($getdata['summary']).'&lang=en-id';
									$json = file_get_contents($url);
									$yandex = json_decode($json);
									if ($yandex->code == 200) $getdata['summary'] = $yandex->text[0];
								}
						    }
						}
						
						// get metascore
						$title = strtolower(trim($getdata['title']));
						$title = preg_replace('/[^A-Za-z0-9]/', ' ', $title);
						$title = str_ireplace('  ', '-', $title);
						$title = str_ireplace(' ', '-', $title);
						
						$site = "http://www.metacritic.com/movie/".$title;
						$yql = "select * from htmlstring where url='" . $site . "'";
						$resturl = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
						
						// make call with cURL
						$session = curl_init($resturl);
						curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
						$json = curl_exec($session);
						
						// convert JSON to PHP object
						$phpObj =  json_decode($json); 
						
						$arrku = (array) $phpObj->query->diagnostics->url[1];
						if (!array_key_exists('http-status-code', $arrku)){ 
							$result = $phpObj->query->results->result;
							if (strpos($result, 'ratingValue') == TRUE){
								$result = explode('ratingValue" : "', $result);
								$result = explode('"', $result[1]);
								$getdata['metascore'] = $result[0];
							} // else : not rated
						} // else : page not found
						
						if (!$this->model_film->getFilmByTitle($getdata['title']))
							$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
							$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],$getdata['param'],$getdata['status']);
					
						$newMovie[$idx]['title'] = $getdata['title'];
						$newMovie[$idx]['status'] = 'Coming Soon';
						$idx++;
					} 
				}
		    }
		}
		
		return $newMovie;
	}
	
	public function checkComingSoon(){
		// new code YQL
		$site = "http://www.21cineplex.com/comingsoon/";
		$yql = "select * from htmlstring where url='" . $site . "'";
		$resturl = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
	    
	    // make call with cURL
	    $session = curl_init($resturl);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	    $json = curl_exec($session);
	    
	    // convert JSON to PHP object
	    $phpObj =  json_decode($json); 
	    
	    $coming = $phpObj->query->results->result;
		$coming = explode('pdata=[', $coming);
		$coming = explode('movieTitle":"', $coming[1]); // explode to get each movie
		//echo '<pre>'; print_r($coming); echo '</pre>';
		
		// explode each movie to get informations
		for ($i=1; $i<sizeof($coming); $i++){
			$info = explode('","movieImage":"', $coming[$i]);				//echo '<pre>'; print_r($info); echo '</pre>';
			$getdata['title'] = $info[0];									echo $i.' - '.$getdata['title'].'<br/>';
			
			$info = explode('","movieTrailerFile":', $info[1]);				//echo '<pre>'; print_r($info); echo '</pre>';
			$info = explode('.', $info[0]);
			$getdata['poster'] = htmlspecialchars_decode('http://www.21cineplex.com/data/gallery/pictures/'.$info[0].'_300x430.jpg'); echo $i.' - '.$getdata['poster'].'<br/><br/>';
		}
	}
	
	public function checkNowPlaying(){
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
		
		$playing = $phpObj->query->results->result;
		$playing = explode('<div id="playing">', $playing);
		$playing = explode('</div>', $playing[1]);
	   	
	   	$title = explode('title', $playing[0]);
	   	$img = explode('src', $playing[0]);
		for ($i=1; $i<sizeof($title); $i++){
			$trueTitle = explode('"', $title[$i]);
			$trueImg = explode('"', $img[$i]);
			$nowPlaying[$i]['title'] = $trueTitle[1];		echo $nowPlaying[$i]['title'].'<br/>';
			$nowPlaying[$i]['img'] = $trueImg[1];
		}
	}
	
	public function updateMetacriticImdb(){
		//$site = "http://www.metacritic.com/movie/dunkirk";
		//$site = "http://www.metacritic.com/movie/guardians-of-the-galaxy-vol-2";
		
		$movies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($movies); $i++){
			$title = strtolower(trim($movies[$i]['title']));
			$title = preg_replace('/[^A-Za-z0-9]/', ' ', $title);
			$title = str_ireplace('  ', '-', $title);
			$title = str_ireplace(' ', '-', $title);
			
			$site = "http://www.metacritic.com/movie/".$title;
			$yql = "select * from htmlstring where url='" . $site . "'";
			$resturl = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
		    
		    // make call with cURL
		    $session = curl_init($resturl);
		    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
		    $json = curl_exec($session);
		    
		    // convert JSON to PHP object
		    $phpObj =  json_decode($json); 
		    
		    //echo '<pre>'; print_r($phpObj); echo '<pre>';
		    
			$arrku = (array) $phpObj->query->diagnostics->url[1];
			if (!array_key_exists('http-status-code', $arrku)){ // page not found
				$result = $phpObj->query->results->result;
				if (strpos($result, 'ratingValue') == TRUE){
					$result = explode('ratingValue" : "', $result);
					$result = explode('"', $result[1]);
					echo '<hr>'.$result[0].' - '.$site.'<hr>';
					$this->model_film->updateMetascoreFilm($movies[$i]['id'], $result[0]);
				}
			} else echo $site.'<br/>';
			
			$oIMDB = new IMDB($movies[$i]['title']);
			if ($oIMDB->isReady) {
				$id = NULL; $rating = NULL;
			    foreach ($oIMDB->getAll() as $aItem) {
			        if ($aItem['name'] == 'Rating') $rating = $aItem['value'];
			        else if ($aItem['name'] == 'Url' && strpos($aItem['name'], 'imdb') == TRUE){
						$temp = explode('/', $aItem['value']);
						$id = $temp[4];
					} 
			    }
			    
			    $this->model_film->updateIMDBFilm($movies[$i]['title'],$id,$rating);
			} else 
			 	echo '<b>Movie not found</b>: ' . $movies[$i]['title'].'<br>';
		}
		
	}
	
	public function testAllAbove70(){
		// for rule-based system
		$commonWords = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$commonWords[$idx]['text'] = $temp[0];
			$commonWords[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$nonReview = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words-nonreview.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$nonReview[$idx]['text'] = $temp[0];
			$nonReview[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon-minus-common_words_review.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
		}
		
		$singkatan = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$singkatan[$idx]['short'] = $temp[0];
			$singkatan[$idx]['long'] = $temp[1];
			$idx++;
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
		$tweets = $this->model_tweets_new->getAllTweetsFrom('tweets_ori');
		
		// put into associative array
		for ($i=0; $i<sizeof($tweets); $i++){
			$result[$i]['film_id'] = $tweets[$i]['film_id'];
			$result[$i]['twitter_id'] = $tweets[$i]['twitter_id'];
			$result[$i]['text'] = strtolower($tweets[$i]['text']);
			$result[$i]['is_review'] = 0;
			$result[$i]['is_positive'] = 0; 
		}
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$movie = $this->model_film->getFilm($result[$i]['film_id']);
			$title = $movie[0]['title'];
			
			$result[$i]['text'] = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $result[$i]['text']);
			$result[$i]['text'] = str_ireplace($title, 'JUDULFILM', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/(.)\\1+/', '$1', $result[$i]['text']); // delete double characters in a word
			$result[$i]['text'] = preg_replace('/[^A-Za-z0-9]/', ' ', $result[$i]['text']);  // delete everything except a-z & 0-9
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_regex', $result[$i]['twitter_id'])){
				$this->model_tweets_new->insertTweetRegex($result[$i]['twitter_id'], $result[$i]['text']);
			}
			
			// replace bahasa bukan baku
			$originalStr = NULL;
			for ($j=0; $j<sizeof($singkatan); $j++){
				//if (preg_match('/\b'.$singkatan[$j]['short'].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$singkatan[$j]['short'].' ') == TRUE || strpos($result[$i]['text'], ' '.$singkatan[$j]['short']) == TRUE || strpos($result[$i]['text'], $singkatan[$j]['short'].' ') == TRUE){
					$result[$i]['text'] = preg_replace('/\b'.$singkatan[$j]['short'].'\b/', $singkatan[$j]['long'], $result[$i]['text']);
					if ($originalStr == NULL) $originalStr = $singkatan[$j]['short'];
					else $originalStr .= ',' . $singkatan[$j]['short'];
				}
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_replaced', $result[$i]['twitter_id']) && $originalStr != NULL){
				$this->model_tweets_new->insertTweetReplaced($result[$i]['twitter_id'], $result[$i]['text'], $originalStr);
			}
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			$value = 0;
			$intersectStr = NULL;
			
			// compare with lexicon data
			for ($j=0; $j<sizeof($lexicon); $j++){
				//if (preg_match('/\b'.$lexicon[$j].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$lexicon[$j].' ') == TRUE || strpos($result[$i]['text'], ' '.$lexicon[$j]) == TRUE || strpos($result[$i]['text'], $lexicon[$j].' ') == TRUE){
					$value++; // for every matched words, give 1
					if ($intersectStr == NULL) $intersectStr = $lexicon[$j];
					else $intersectStr .= ',' . $lexicon[$j];
				}
			}
			
			// compare with non-review common words data
			for ($j=0; $j<sizeof($nonReview); $j++){
				//if (preg_match('/\b'.$nonReview[$j]['text'].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$nonReview[$j]['text'].' ') == TRUE || strpos($result[$i]['text'], ' '.$nonReview[$j]['text']) == TRUE || strpos($result[$i]['text'], $nonReview[$j]['text'].' ') == TRUE){
					$value += $nonReview[$j]['score'];
					if ($intersectStr == NULL) $intersectStr = $nonReview[$j]['text'];
					else $intersectStr .= ',' . $nonReview[$j]['text'];
				}
			}
			
			// compare with common words data
			for ($j=0; $j<sizeof($commonWords); $j++){
				//if (preg_match('/\b'.$commonWords[$j]['text'].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$commonWords[$j]['text'].' ') == TRUE || strpos($result[$i]['text'], ' '.$commonWords[$j]['text']) == TRUE || strpos($result[$i]['text'], $commonWords[$j]['text'].' ') == TRUE){
					$value += $commonWords[$j]['score'];
					if ($intersectStr == NULL) $intersectStr = $commonWords[$j]['text'];
					else $intersectStr .= ',' . $commonWords[$j]['text'];
				}
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_lexicon', $result[$i]['twitter_id']) && $value >= 10){
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
				for ($j=0; $j<sizeof($listPos); $j++){
					if (strpos($result[$i]['text'], $listPos[$j]) == TRUE) // if matched
						$result[$i]['persen_pos'] += 0.25;
					
				}
				for ($j=0; $j<sizeof($listNeg); $j++){
					if (strpos($result[$i]['text'], $listNeg[$j]) == TRUE) // if matched
						$result[$i]['persen_neg'] += 0.25;
					
				}
				
				// set is_positive value
				if ($resultofAnalyzingSentence == "positive" || $probabilityofSentenceBeingPositive > $probabilityofSentenceBeingNegative)
					$result[$i]['is_positive'] = 1;
				else 
					$result[$i]['is_positive'] = 0;
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_final', $result[$i]['twitter_id'])){
				if (!$this->model_tweets_new->getTweetByText('tweets_final', $result[$i]['text']))
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 0);
				else 
					$this->model_tweets_new->insertTweetFinal($result[$i]['twitter_id'], $result[$i]['film_id'], $result[$i]['text'], $result[$i]['is_review'], $result[$i]['is_positive'], 1);
			}
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets_old->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets_old->getMovieCountPosTweet($allMovies[$i]['id']));	
		}		
		
		echo '<h1>DONE</h1><br><h1>DONE</h1><br><h1>DONE</h1><br><h1>DONE</h1><br><h1>DONE</h1><br>';
	}
	
	public function testLama(){ // testDataTweetLama
		// for rule-based system
		$commonWords = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$commonWords[$idx]['text'] = $temp[0];
			$commonWords[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$nonReview = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/common-words-nonreview.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$nonReview[$idx]['text'] = $temp[0];
			$nonReview[$idx]['score'] = $temp[1];
			$idx++;
		}
		
		$lexicon = [];
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/lexicon-minus-common_words_review.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			array_push($lexicon, trim($activeLine));	
		}
		
		$singkatan = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/singkatan.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$singkatan[$idx]['short'] = $temp[0];
			$singkatan[$idx]['long'] = $temp[1];
			$idx++;
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
		
		// input data
		$result = []; $idx = 0;
		$fileLocation = fopen(dirname(dirname(__FILE__)).'/third_party/tweet_lama_nonreview.txt', "r");
		while ($activeLine = fgets($fileLocation)){
			$temp = explode(',', trim(strtolower($activeLine)));
			$result[$idx]['film_id'] = $temp[0];
			$result[$idx]['text'] = $temp[1];
			$result[$idx]['status'] = 2;
			$idx++;
		}
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$movie = $this->model_film->getFilm($result[$i]['film_id']);
			$title = $movie[0]['title'];
			
			$result[$i]['text'] = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $result[$i]['text']);
			$result[$i]['text'] = str_ireplace($title, 'JUDULFILM', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/(.)\\1+/', '$1', $result[$i]['text']); // delete double characters in a word
			$result[$i]['text'] = preg_replace('/[^A-Za-z0-9]/', ' ', $result[$i]['text']);  // delete everything except a-z & 0-9
			
			// replace bahasa bukan baku
			for ($a=1; $a<=3; $a++){ // cek bahasa bukan baku 3x
				$originalStr = NULL;
				for ($j=0; $j<sizeof($singkatan); $j++){ 
					//if (preg_match('/\b'.$singkatan[$j]['short'].'\b/', $result[$i]['text'])) { // if matched
					if (strpos($result[$i]['text'], ' '.$singkatan[$j]['short'].' ') == TRUE || strpos($result[$i]['text'], ' '.$singkatan[$j]['short']) == TRUE || strpos($result[$i]['text'], $singkatan[$j]['short'].' ') == TRUE){
						$result[$i]['text'] = preg_replace('/\b'.$singkatan[$j]['short'].'\b/', $singkatan[$j]['long'], $result[$i]['text']);
						if ($originalStr == NULL) $originalStr = $singkatan[$j]['short'];
						else $originalStr .= ',' . $singkatan[$j]['short'];
					}
				}
			}
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			$value = 0;
			$intersectStr = NULL;
			
			// compare with lexicon data
			for ($j=0; $j<sizeof($lexicon); $j++){
				//if (preg_match('/\b'.$lexicon[$j].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$lexicon[$j].' ') == TRUE || strpos($result[$i]['text'], ' '.$lexicon[$j]) == TRUE || strpos($result[$i]['text'], $lexicon[$j].' ') == TRUE){
					$value++; // for every matched words, give 1
					if ($intersectStr == NULL) $intersectStr = $lexicon[$j];
					else $intersectStr .= ',' . $lexicon[$j];
				}
			}
			
			// compare with non-review common words data
			for ($j=0; $j<sizeof($nonReview); $j++){
				//if (preg_match('/\b'.$nonReview[$j]['text'].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$nonReview[$j]['text'].' ') == TRUE || strpos($result[$i]['text'], ' '.$nonReview[$j]['text']) == TRUE || strpos($result[$i]['text'], $nonReview[$j]['text'].' ') == TRUE){
					$value += $nonReview[$j]['score'];
					if ($intersectStr == NULL) $intersectStr = $nonReview[$j]['text'];
					else $intersectStr .= ',' . $nonReview[$j]['text'];
				}
			}
			
			// compare with common words data
			for ($j=0; $j<sizeof($commonWords); $j++){
				//if (preg_match('/\b'.$commonWords[$j]['text'].'\b/', $result[$i]['text'])) { // if matched
				if (strpos($result[$i]['text'], ' '.$commonWords[$j]['text'].' ') == TRUE || strpos($result[$i]['text'], ' '.$commonWords[$j]['text']) == TRUE || strpos($result[$i]['text'], $commonWords[$j]['text'].' ') == TRUE){
					$value += $commonWords[$j]['score'];
					if ($intersectStr == NULL) $intersectStr = $commonWords[$j]['text'];
					else $intersectStr .= ',' . $commonWords[$j]['text'];
				}
			}
			
			if ($value >= 10) $result[$i]['status'] = 1;
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['status'] == 1){
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
					$result[$i]['status'] = 1;
				else 
					$result[$i]['status'] = 0;
			}
			
			if (!$this->model_tweets_old->getTweetByText($result[$i]['text'])){
				$this->model_tweets_old->insertTweet($result[$i]['film_id'], $result[$i]['text'], $result[$i]['status'], 0,0);
			}
		}
		
		// update film
		$allMovies = $this->model_film->getAllFilm();
		for ($i=0; $i<sizeof($allMovies); $i++){
			$this->model_film->updateTwitterFilm($allMovies[$i]['id'], $this->model_tweets_old->getMovieCountNegTweet($allMovies[$i]['id']), $this->model_tweets_old->getMovieCountPosTweet($allMovies[$i]['id']));	
		}		
		
		echo '<h1>DONE</h1><br><h1>DONE</h1><br><h1>DONE</h1><br><h1>DONE</h1><br><h1>DONE</h1><br>';
	}
	
	public function arrayIntersect(){
		$color 		= ['red','green','blue','cyan','magenta','yellow'];
		$warna 		= ['merah','red','hijau','green','biru','blue','biru muda','ungu','kuning','yellow','pink','merah muda','hitam','putih','abu-abu'];
		$warnaValue	= ['merah-0','red-1','hijau-2','green-3','biru-4','blue-5','biru muda-6','ungu-7','kuning-8','yellow-9','pink-10','merah muda-11','hitam-12','putih-13','abu-abu-14'];
		
		echo '<pre>';
		echo 'color: '; 		print_r($color); 		echo '<br>';
		echo 'warna: '; 		print_r($warna); 		echo '<br>';
		echo 'warnaValue: '; 	print_r($warnaValue); 	echo '<hr>';
		echo '</pre>';
		
		$intersectsWith = array_intersect($warna, $color);
		if ($intersectsWith) {
			for ($j=0; $j<sizeof($intersectsWith); $j++){
				$arrayKey = key($intersectsWith);
				echo $arrayKey.' '.$warna[$arrayKey].' '.$warnaValue[$arrayKey].'<br>';
				next($intersectsWith);
			}
		}
	}
	
	public function yandexTranslate(){
		$temp = 'http://www.imdb.com/title/tt4981636/';
		$temp = explode('/',$temp);
		print_r($temp);
		echo '<br>'.$temp[4];
		
		echo '<hr>';
		
		$getdata['summary'] = 'Devoted lifeguard Mitch Buchannon butts heads with a brash new recruit, as they uncover a criminal plot that threatens the future of the bay.';
		$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($getdata['summary']).'&lang=en-id';
		$session = curl_init($url);
		curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
		$json = curl_exec($session);
		$yandex = json_decode($json);
		
		echo '<br>'.$url.'<br><br>';
		var_dump($json);
		echo '<br><br>';
		var_dump($yandex);
		echo '<hr>';					    
		
		$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170416T090412Z.f7b776234bccb994.6d705805d1d4deee728d68550f617a3f8be6c15c&text='.urlencode($getdata['summary']).'&lang=en-id';
		$json = file_get_contents($url);
		$yandex = json_decode($json);
		$getdata['summary'] = $yandex->text[0];
		
		var_dump($yandex);
		echo '<br><br>';
		print_r($yandex);
		echo '<br><br>'.$yandex->text[0];
		echo '<br><br>'.$yandex->code;
		if ($yandex->code == 200) echo '  haha';
	}

	public function newImdbLibrary(){
		$oIMDB = new IMDB('MIDDLE SCHOOL: THE WORST YEARS OF MY LIFE');
		if ($oIMDB->isReady) {
		    foreach ($oIMDB->getAll() as $aItem) {
		        echo '<b>' . $aItem['name'] . '</b>: ' . $aItem['value'] . '<br><br>';
		    }
		}
		else 
		 	echo '<b>Movie not found</b>: ' . 'MIDDLE SCHOOL: THE WORST YEARS OF MY LIFE';
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
	
	private function splitSentence($words){
		preg_match_all('/\w+/', $words, $matches);
		return $matches[0];
	}
	
}