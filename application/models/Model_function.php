<?php

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
		
		$result[0]['input'] 		= $input;
		$result[0]['text'] 			= $input;
		$result[0]['replaced']	 	= NULL;
		$result[0]['common'] 		= NULL;
		$result[0]['regex'] 		= NULL;
		$result[0]['lexicon'] 		= NULL;
		$result[0]['is_review'] 	= 0;
		$result[0]['is_positive'] 	= 0; 
		$result[0]['persen_pos'] 	= 0; 
		$result[0]['persen_neg'] 	= 0; 
		$result[0]['score'] 		= 0; 
		
		// !!! === !!! === begin feature-reduction & mapping data
		for ($i=0; $i<sizeof($result); $i++){
			// decode html characters
			$result[$i]['text'] = html_entity_decode($result[$i]['text'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			
			// feature reduction --> delete username, url, hashtag, punctuations
			$editedResult = $result[$i]['text'];
			$editedResult = preg_replace('%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s', 'URL', $editedResult);
			$editedResult = preg_replace('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'HASHTAG', $editedResult);
			$editedResult = preg_replace('/@([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', 'USERNAME', $editedResult);
			//$editedResult = str_ireplace($title, 'JUDULFILM', $editedResult);
			$editedResult = preg_replace('/(.)\\1+/', '$1', $editedResult); // delete double characters in a word
			$editedResult = preg_replace('/[^A-Za-z0-9]/', ' ', $editedResult);  // delete everything except a-z & 0-9
			
			$result[$i]['regex'] = $editedResult;
			$result[$i]['text']  = $editedResult;
			
			// replace bahasa bukan baku
			for ($j=0; $j<sizeof($singkatan); $j++){ // using strpos is faster than using preg_match so
				if (preg_match('/\b'.$singkatan[$j]['short'].'\b/', $result[$i]['text'])) { // if matched
					$result[$i]['text']  = preg_replace('/\b'.$singkatan[$j]['short'].'\b/', $singkatan[$j]['long'], $result[$i]['text']);
					if ($result[0]['replaced'] == NULL) $result[0]['replaced'] = $singkatan[$j]['short'];
					else $result[0]['replaced'] .= ',' . $singkatan[$j]['short'];
				}
			}
		}
		
		// !!! === !!! === begin rule-based
		for ($i=0; $i<sizeof($result); $i++){
			// compare with lexicon data
			for ($j=0; $j<sizeof($lexicon); $j++){
				if (preg_match('/\b'.$lexicon[$j].'\b/', $result[$i]['text'])) { // if matched
					$result[0]['score']++; // for every matched words, give 1
					if ($result[0]['lexicon'] == NULL) $result[0]['lexicon'] = $lexicon[$j];
					else $result[0]['lexicon'] .= ',' . $lexicon[$j];
				}
			}
			
			// compare with non-review common words data
			for ($j=0; $j<sizeof($nonReview); $j++){
				if (preg_match('/\b'.$nonReview[$j]['text'].'\b/', $result[$i]['text'])) { // if matched
					$result[0]['score'] += $nonReview[$j]['score'];
					if ($result[0]['common'] == NULL) $result[0]['common'] = $nonReview[$j]['text'];
					else $result[0]['common'] .= ',' . $nonReview[$j]['text'];
				}
			}
			
			// compare with common words data
			for ($j=0; $j<sizeof($commonWords); $j++){
				if (preg_match('/\b'.$commonWords[$j]['text'].'\b/', $result[$i]['text'])) { // if matched
					$result[0]['score'] += $commonWords[$j]['score'];
					if ($result[0]['common'] == NULL) $result[0]['common'] = $commonWords[$j]['text'];
					else $result[0]['common'] .= ',' . $commonWords[$j]['text'];
				}
			}
			
			if ($result[0]['score'] >= 8)
				$result[$i]['is_review'] = 1;
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($result); $i++){
			if ($result[$i]['is_review'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($result[$i]['text']);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$result[$i]['persen_pos'] = $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$result[$i]['persen_neg'] = $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
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
				if ($resultofAnalyzingSentence == "positive" || $result[$i]['persen_pos'] > $result[$i]['persen_neg'])
					$result[$i]['is_positive'] = 1;
				else 
					$result[$i]['is_positive'] = 0;
			}
		}
		
		return $result;
	}
	
}
?>
