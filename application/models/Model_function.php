<?php

include_once( dirname(dirname(__FILE__)) . '/libraries/SentimentAnalyzer.php' );

Class Model_function extends CI_Model {

	public function __construct(){
        parent::__construct();
    }
	
	public function calculateTweetWithoutMovieId($input){
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
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_neg.txt', 'negative', 200); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data_training_twitter_pos.txt', 'positive', 200); //training with positive data	
		
		// put into associative array
		$result[0]['input'] 		= $input;
		$result[0]['text'] 			= $input;
		$result[0]['regex'] 		= NULL;
		$result[0]['replaced']	 	= NULL;
		$result[0]['lexicon'] 		= NULL;
		$result[0]['is_review'] 	= 0;
		$result[0]['is_positive'] 	= 0; 
		$result[0]['intersect'] 	= NULL; 
		$result[0]['positivity'] 	= 0; 
		$result[0]['negativity'] 	= 0; 
		$result[0]['score'] 		= 0;
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			$result[$i]['text'] = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $result[$i]['text']);
			$result[$i]['text'] = preg_replace('/(.)\\1+/', '$1', $result[$i]['text']); // delete double characters in a word
			$result[$i]['text'] = preg_replace('/[^A-Za-z0-9]/', ' ', $result[$i]['text']);  // delete everything except a-z & 0-9
			
			$result[$i]['regex'] = $result[$i]['text'];
			
			// replace bahasa bukan baku
			for ($j=0; $j<sizeof($singkatan); $j++){
				if (strpos($result[$i]['text'], $singkatan[$j]['short']) == TRUE){
					if (preg_match('/\b'.$singkatan[$j]['short'].'\b/', $result[$i]['text'])) {
						$result[$i]['text'] = preg_replace('/\b'.$singkatan[$j]['short'].'\b/', $singkatan[$j]['long'], $result[$i]['text']);
						if ($result[$i]['replaced'] == NULL) $result[$i]['replaced'] = $singkatan[$j]['short'];
						else $result[$i]['replaced'] .= ',' . $singkatan[$j]['short'];
					}
				}
			}
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			// compare with lexicon data
			for ($j=0; $j<sizeof($lexicon); $j++){
				if (strpos($result[$i]['text'], $lexicon[$j]) == TRUE){
					if (preg_match('/\b'.$lexicon[$j].'\b/', $result[$i]['text'])) { 
						$result[$i]['score']++; // for every matched words, give 1
						if ($result[$i]['lexicon'] == NULL) $result[$i]['lexicon'] = $lexicon[$j];
						else $result[$i]['lexicon'] .= ',' . $lexicon[$j];
					}
				}
			}
			
			// compare with non-review common words data
			for ($j=0; $j<sizeof($nonReview); $j++){
				if (strpos($result[$i]['text'], $nonReview[$j]['text']) == TRUE){
					if (preg_match('/\b'.$nonReview[$j]['text'].'\b/', $result[$i]['text'])) {
						$result[$i]['score'] += $nonReview[$j]['score'];
						if ($result[$i]['lexicon'] == NULL) $result[$i]['lexicon'] = '-'.$nonReview[$j]['text'];
						else $result[$i]['lexicon'] .= ',-' . $nonReview[$j]['text'];
					}
				}
			}
			
			// compare with review common words data
			for ($j=0; $j<sizeof($commonWords); $j++){
				if (strpos($result[$i]['text'], $commonWords[$j]['text']) == TRUE){
					if (preg_match('/\b'.$commonWords[$j]['text'].'\b/', $result[$i]['text'])) { 
						$result[$i]['score'] += $commonWords[$j]['score'];
						if ($result[$i]['lexicon'] == NULL) $result[$i]['lexicon'] = $commonWords[$j]['text'];
						else $result[$i]['lexicon'] .= ',' . $commonWords[$j]['text'];
					}
				}
			}
			
			if (!$this->model_tweets_new->getTweetByOri('tweets_lexicon', $result[$i]['twitter_id']) && $value >= 10){
				$result[$i]['is_review'] = 1;
				$result[$i]['lexicon'] = $intersectStr; 
				$result[$i]['score'] = $value; 
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
				else 
					$result[$i]['is_positive'] = 0;
			}
		}
		
		return $result;
	}
	
}
?>
