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
					$moviesinDB = $this->model_film->getComingSoonMovies();
					$alreadyinDB = FALSE;
					for ($j=0; $j<sizeof($moviesinDB); $j++){
						if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
							$alreadyinDB = TRUE;
							break;
						}
					}
					if (!$alreadyinDB){ // check with unchecked coming soon movies
						$moviesinDB = $this->model_film->getUncheckedComingSoonMovies();
						for ($j=0; $j<sizeof($moviesinDB); $j++){
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
								$alreadyinDB = TRUE;
								break;
							}
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
					
					//echo '<hr>'.$getdata['title'].'<br>'.$getdata['summary'].'<br/>'.$getdata['poster'].'<br/>Found in DB : '.$alreadyinDB;
				}
		    }
		}
	}

	public function checkNewNowPlayingMovies(){
		
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
			/* get js content from cinema 21
		    $pdata = $phpObj->query->results->body->div[0]->div[1]->div[1]->div[2]->script->content;
		    
		    // explode string to get data -> stored inside var pdata=[..]
		    $pdata = explode(']', $pdata);
		    $pdata = $pdata[0];
		    $pdata = explode('pdata=[', $pdata);
			$pdata = "[".$pdata[1]."]"; // valid json
			$pdata = explode('{', $pdata); // explode to get each movie
			//echo '<pre>'; print_r($pdata); echo '</pre>';*/
			
			$pdata = $phpObj->query->results->body->div[0]->div[1]->div[3]->div[0]->div[0]->div[1]->ul->li;
			
			// if there's a title in db with status NOW PLAYING, but doesnt exist in the data we got from 21, then change the movie's status into OLD
			$moviesinDB = $this->model_film->getOnGoingMovies();	
			for ($j=0; $j<sizeof($moviesinDB); $j++){
				$isStillPlaying = FALSE;
				for ($i=1; $i<sizeof($pdata)-2; $i++){
					//echo $pdata[$i]->a->img->title.'<br/><img src="'.str_replace('100x147','300x430',$pdata[$i]->a->img->src).'"/><br/>';
					$getdata['title'] = $pdata[$i]->a->img->title;
					
					if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
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
					$moviesinDB = $this->model_film->getOnGoingMovies();
					$alreadyinDB = FALSE;
					for ($j=0; $j<sizeof($moviesinDB); $j++){
						if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
							$alreadyinDB = TRUE;
							break;
						}
					}
					if (!$alreadyinDB){ // check with unchecked now playing movies
						$moviesinDB = $this->model_film->getUncheckedNowPlayingMovies();						
						for ($j=0; $j<sizeof($moviesinDB); $j++){
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
								$alreadyinDB = TRUE;
								break;
							}
						}
					}
					$isComingSoon = FALSE;
					$isComingSoon_id = 0;
					if (!$alreadyinDB){ // check with coming soon movies										
						$moviesinDB = $this->model_film->getUncheckedNowPlayingMovies();					
						for ($j=0; $j<sizeof($moviesinDB); $j++){
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){
								$alreadyinDB = TRUE;
								$isComingSoon = TRUE;
								$isComingSoon_id = $moviesinDB[$j]['id'];
								break;
							}
						}
					}
					if (!$alreadyinDB && !$isComingSoon){ // check with unchecked coming soon movies			
						$moviesinDB = $this->model_film->getUncheckedNowPlayingMovies();						
						for ($j=0; $j<sizeof($moviesinDB); $j++){		
							if (htmlspecialchars_decode($moviesinDB[$j]['title']) == htmlspecialchars_decode($getdata['title'])){			
								$alreadyinDB = TRUE;
								$isComingSoon = TRUE;
								$isComingSoon_id = $moviesinDB[$j]['id'];
								break;
							}
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
						
						if (!$isComingSoon){ // not in database as coming soon, insert it as new
							$this->model_film->insertFilm($getdata['title'],$getdata['summary'],$getdata['genre'],$getdata['year'],$getdata['playing_date'],$getdata['length'],$getdata['director'],
								$getdata['writer'],$getdata['actors'],$getdata['poster'],$getdata['trailer'],$getdata['imdb_id'],$getdata['imdb_rating'],$getdata['metascore'],$getdata['status']);
						} else {
							// already in database as coming soon, just change status
							$this->model_film->updateStatusFilm($isComingSoon_id, 1);
						}
					}
				}
		    }
		}
	}
	
	public function calculateTweets(){
		// for rule-based system
		$lexicon = array("2 berwajah","2-wajah","a +","ababil","abadi","abnormal","abominably","absentee","absurdness","acak","acak-acakan","acerbic","acerbically","achey","achievible","acridly","acridness","acrimoniously","acuh tak acuh","adaptif","adiktif","adil","adjustable","admonisher","admonishingly","adulterier","affably","afflictive","afinitas","afirmasi","afordable","aggrivation","agilely","agonizingly","agresi","agresif","agresor","agung","ahli","ahlinya","aib","air terjun","ajaib","ajur","aklamasi","akomodatif","aktif","aktual","akurat","alam mimpi","alami","alarm","alasan","alat pengatur api kompor","alat permainan","alay","alergi","alergik","alluringly","altruistically","altruistis","alur","aman","amat","amat ketakutan","amat panas","ambisius","ambivalen","ambivalensi","amiabily","amobil","amuk","anak nakal","anak yatim","anarki","anarkis","anarkisme","ancaman","ancur","andal","aneh","aneh lagi","anehnya","anget","anggun","angin sepoi-sepoi","angkuh","angriness","anjlok","anomali","antagonis","antagonisme","antek","Anteng","anti-","anti-Amerika","anti-Israel","anti-kami","antipati","anti-pendudukan","anti-proliferasi","anti-putih","anti-Semit","antisosial","antitesis","antusias","antusiasme","apak","apathetically","apati","apatis","apek","apik","apokaliptik","apologis","apresiasi","arduously","argumentatif","arik","artinya jika","asam","asap","asem","asin","asing","asininely","asinininity","asli","aspersions","aspirasi","assult","asusila","asyik","atrophia","audaciously","audiciously","autentik","avariciously","awan","awas","aweful","awesomely","awet","awfulness","awsome","ayun","babatan","babi","backaching","back-kayu","back-log","Backwood","bacul","badai","baek","bagong","bagus","bahagia","bahan tertawaan","bahaya","baik","baik diposisikan","baik sekali","baik-backlit","baji","bajingan","bakat","balas dendam","banalize","banci","bandel","bandot","bangga","bangkit kembali","bangkrut","banteng","bantuan","banyak","banyak akal","banyak sekali","Barang baru","barang ganjil","barbar","barbarously","baru","bashing","basi","batas","batin","batu permata","bau","bawahan","bdh","beautifullly","beban","bebas","bebas masalah","bebas pulsa","bebas rasa sakit","bebas resiko","beckons","begitu-cal","bejat","bekas","bekas luka","bekas roda","bekerja","bekerja keras","beku","belas kasihan","belerang","believeable","belligerently","belum dewasa","belum dicoba","belum dikonfirmasi","belum pasti","belum selesai","belur","benar","benar-benar","bencana","bencana alam","benci","bener","bengah","bengal","bengis","bengkak","bengkok","benjolan","Bentar","bentrokan","beracun","beradab","beradaptasi","berakhir","beralasan","beraneka ragam","berang","berani","berapi","berarti","berat","berat sebelah","berawan","berbahaya","berbakat","berbatu-batu","berbau","berbesar hati","berbisa","berbohong","Berbuat curang","berbuat jahat","berbudi luhur","berbuih","bercacat","bercahaya","bercanda","bercita-cita","berdalih","berdarah","berdasar","berdaya","berdaya cipta","berdebar","berdendam","berdengung","berdenyut","berderak","berderit","berdetak","berdikari","berdokumen","berdosa","berduri","berdusta","berebut","berempati","bergairah","bergaya","bergegas","bergelombang","bergema","bergembira","bergembira sekali","bergengsi","bergerak","bergerak lambat","bergerigi","bergetar","bergizi","bergolak","bergulat","berguna","berhak","berhaluan kiri","berharga","berhasil","berhutang","berikut","berimbang","beringas","berisi","berisik","berisiko","beristirahat","berita palsu","berjangkit","berjanji","berjaya","berjenis","berjuang","berkapasitas besar","berkarat","berkat","berkata tanpa berpikir","berkedip","berkeinginan","berkejut","berkelanjutan","berkembang","berkenan","berkeping-keping","berkeras pendirian","berkeringat","berkerut","berkeyakinan","berkhayal","berkhotbah","berkilat","berkilau","berkilauan","berkolusi","berkonflik","berkotek","berkualitas","berkubang","berlangganan","berlantai","berlawanan","berlebihan","berlemak","berlepotan","berliku-liku","berlimpah","berlimpah-limpah","berlumpur","bermacam-macam","bermain berlebih-lebihan","bermanfaat","bermartabat","bermasalah","berminyak","bermuka dua","bermuram","bermusuhan","bermutu rendah","bernasib buruk","bernilai","bernoda kotor","berongga","berpendidikan","berpendidikan baik","berpengalaman","berpengaruh","berpengetahuan luas","berperang","berperasaan","berpetak-petak","berpijar","berpikiran lemah","berpura-pura","berputus asa","bersabun","bersaing","bersakit","bersanding","bersatu","bersedia melakukan","bersedih","bersekongkol","berselang","bersemangat","bersemangat meluap-luap","bersenandung","bersenang-senang","bersenda gurau","berserakan","berseri","bersetubuh","bersifat dermawan","bersifat kanker","bersifat membatasi","bersifat memfitnah","bersifat mendamaikan","bersifat mengejek","bersifat menipu","bersifat pembalasan","bersih","bersinar","bersisik","bersorak","bersubsidi","bersuka cita","bersuka ria","bertahan hidup","bertakhyul","bertanggung jawab","bertanya-tanya","bertekun","bertele-tele","bertemu","bertengkar","bertengkar tanpa alasan yg penting","bertentangan","berteriak","berterimakasih","bertindak tdk pantas","bertingkah","bertubuh kecil","berukuran terlalu kecil","berumur pendek","beruntung","berwarna kuning","berwawasan","besar","besar dan kuat","besar sekali","beutifully","bewilderingly","bgs","biadab","bias","biasa","biasa-biasa saja","biaya rendah","bidaah","bigotries","bijak","bijaksana","bikinan","bimbang","bimbingan","binasa","bingung","bisa digunakan","bisa diraih","bisa diterapkan","bisa ular","bla","Blindside","blockbuster","blunder","bluring","bnr","bntr","bobot mati","bocor","bodoh","bohong","bom","boneka","bonus","boros","bosan","bosen","botak","bowdlerize","Bragger","brainiest","brashly","brazenness","break-up","breakups","brilliances","brutal","bs","bsn","bsr","bual","buar","buas","budak","bug","bukan kepalang","bulkier","bulkiness","bulkyness","bullish","bullshyt","bullyingly","bulu","bumpping","bunga","buntu","bunuh diri","buram","burdensomely","buritan","buronan","buruk","buruk sekali","busuk","buta","buta huruf","byk","cabul","cacat","cahaya","cahaya redup","cakap","cakep","calamitously","calo","calumniously","cambuk","canggung","cantik","capek","cara","casanova","cashbacks","cash-diikat","cataclysmal","cataclysmically","catastrophies","cebol","cedera","cekatan","cekcok","cekung","celaan","celah","celaka","cemas","cemberut","cemburu","cemen","cemerlang","cemooh","cemoohan","cenderung","cengeng","cengking","cepat","cepat tanggap","cepet","cerah","cercaan","cerdas","cerdik","cerewet","ceria","cerita","cermat","ceroboh","Cetek","chintzy","chubby","chunky","cinta","cita","ckp","Clears","cocok","commendably","commotions","compang-camping","compliant","concen","concens","Congkak","conscons","contoh","contortions","convience","convienient","convient","corengan","corrosions","corrupts","corruptted","coupists","courageousness","cpt","craftly","craps","crash","cringes","crisper","cukup","cukup besar","Culas","culun","cunts","cuplrit","cupu","curam","curang","curiga","dalam","damai","damn","dangkal","danke","Danken","dapat","dapat dipercaya","dapet","Datar","dauntingly","daya tarik","debaser","debaucher","dedicated","defensif","Defiler","degenerasi","degenerately","degil","deginified","degradasi","degradingly","dehumanisasi","dekaden","dekadensi","Dekat","deket","delightfulness","delusi","demam","demoralisasi","demoralizingly","dendam","dengan keras kepala","dengan ketat","dengan marah","dengan mewah","dengan naifnya","dengan panas","dengan senang hati","dengan sopan","dengan tampan","dengki","denunciate","denyutan","dependably","deplorably","deploringly","depravedly","depresi","derisiveness","derit","derita","dermawan","desa","desersi","desis","desititute","desolately","despicably","despoiler","despotisme","destabilisasi","destains","destruktif","detestably","dewa-mengerikan","dewasa","dgn aneh","dgn bagus","dgn baik sekali","dgn berhati-hati","dgn berkilau","dgn berlebih-lebihan dlm pujiannya","dgn bernafsu","dgn boros","dgn cakap","dgn cara yg menyolok","dgn cara yg tdk dpt dimaafkan","dgn disesalkan","dgn enggan","dgn fasih","dgn hati-hati","dgn jenaka","dgn kagum","dgn kasar","dgn keberanian","dgn kejam","dgn kurang sopan","dgn licin","dgn malu-malu","dgn mencemoohkan","dgn menghina","dgn menuruntukan gerak hati","dgn menyedihkan","dgn menyesal","dgn mudah","dgn mulia","dgn penuh ketakutan","dgn perasaan bersalah","dgn rajin","dgn rasa curiga","dgn rasa hina","dgn remeh-temeh","dgn ria gembira","dgn riang","dgn sayang","dgn sedih","dgn segan","dgn sengit","dgn sia-sia","dgn suara keras","dgn sukar","dgn tak dpt disangsikan","dgn tangkas","dgn tdk sabar","dgn tegas","dgn tenang","dgn terbahak-bahak","dgn tinggi hati","diakses","diakui","diam","diam-diam","dianggap baik","diasuransikan","diatasi","dibaca","dibakar","dibanjiri","dibatalkan","dibebaskan","dibedakan","dibenarkan","dibenci","diberikan","dibersihkan","dibesar-besarkan","dibuat dengan baik","dibuat-buat","dicapai","dicela","dicerca","dicuci","dicuri","didanai","didominasi","didukung","diem","diganggu","digugat","digunakan","dihaluskan","dihancurkan","dihargai","dihukum","diinginkan","diizinkan","dijauhi","dijelaskan","dikaburkan","dikalahkan","dikelola","dikelola dengan baik","dikembalikan","dikenakan","Dikit","dikorbankan","dilapisi gula","dilayari","dilecehkan","dilema","dilengkapi","dilenyapkan","dilepas","dilihat","dimarahi","dimenangkan","dimengerti","dinamis","dingin","diperbaharui","diperbaiki","dipercaya","diperkosa","diperlakukan dengan buruk","diperoleh","dipertanyakan","dipikirkan","diplomatik","dipoles","dirampas","direformasi","direkomendasikan","diremajakan","diremehkan","direstrukturisasi","diri","dirugikan","dirusak","disahkan","disalahgunakan","disalahpahami","disayangi","disayangkan","disebut-sebut","disederhanakan","disembelih","disiram","disubsidi","ditarik","ditegakkan","ditempa","ditenangkan","diterima dengan baik","ditinggalkan","ditingkatkan","ditipu","ditolak","diverifikasi","dkt","dlm","dogmatis","dongkol","dorongan","dosa","dotingly","dpt","dtr","dua wajah","duanya","duga","dugaan","duka","dukun","dukung","dukungan","dumbfounding","dummy-bukti","dungu","duniawi","dupa","durhaka","duri","durian runtuh","dusta","dusun","dwimakna","ebulliently","ecenomical","edan","edukatif","efektif","efektivitas","efisien","efusi","egois","egoisme","ejekan","ekonomi","ekonomis","eksis","ekspektasi","eksploitasi","eksplosif","ekspor","ekstase","ekstremis","ekstrimis","elastis","elatedly","elegan","elite","emas","embun","embun beku","empati","empedu","enak","enchantingly","endel","energi","enggan","enggan membantu","enrapt","enteng","enviousness","epik","erat","ergonomis","err bebas","etis","euforia","euphorically","evaluatif","exaltedly","exaltingly","examplar","examplary","excallent","exceled","Excellent","excited","excitedness","exellent","exhilaratingly","exhorbitant","exorbitantance","exorbitantly","expulse","exuberantly","exultingly","eyecatch","eye-catch","eyecatching","f ** k","Fajar","fakta","fallaciously","fanatik","fanatisme","fancinating","fantastis","farcically","farfetched","fasih","fasis","fasisme","fastuous","fatal","fatalistis","fatefully","fatique","fav","fave","favorit","fawningly","feasibly","fecilitous","feeblely","feisty","feminim","fenomenal","feodal","fervidly","fiksi","film","firasat","firdaus","fitnah","fitnahan","flairs","flakey","flakieness","flare","flat","flatteringly","fleed","fleksibel","fleksibilitas","flicering","fobi","fobia","forebodingly","forgetfully","fractiously","franticly","freakishly","frenetically","frets","friksi","frustasi","frustrasi","ftw","full-blown","fundamentalisme","futurestic","futuristik","gadungan","gagah","gagal","gagap","gaib","gainfully","gainsayer","gairah","galak","galau","gallingly","galls","gamblang","ganas","gangguan","ganjal","ganjil","ganteng","ganti rugi","garang","garing","garis keras","garu","gasang","gatal","gede","geekier","gegabah","gejala","gelandangan","gelap","gelisah","gelora","gemar","gembar-gembor","Gembel","gembira","gembira luar biasa","gembira sekali","Gembul","gemuk","Gemulai","gendut","gengsi","genit","genosida","genting","gentle","gerah","geram","gerombolan","gesekan","gesit","gesper","getah","getaran","ghosting","giat","gigih","gigil","gila","gila hormat","gila ketakutan","gila-gilaan","gimmicked","gimmicking","gimmicks","Girang","glamor","glitches","gloatingly","glowingly","goading","goblok","godaan","goood","gooood","goreng","goresan","gosip","goyah","goyangan","gratifyingly","gratis","groundbreaking","gruesomely","grumpier","grumpiest","grumpish","gua","gugup","gurih","gurun","gusar","gutless","habis","hacks","hadiah","hak istimewa","hak milik","hal patuh","hal tdk dimengerti","halangan","halus","hama","hambatan","hampir","hampir mati","hancur","handier","hang","hangat","hangat-hangat kuku","haram","haram jadah","harapan","hardball","hardier","harga di atas","harga diri","Harga rendah","harmoni","harmonis","harried","harta","harum","hasil karya","hasil terbaik","hasseling","hasutan","hati","hati-hati","haus","haus darah","hawkish","hbs","heartbreakingly","heavyhearted","hebat","heckles","hedonistik","hegemoni","hegemonisme","hegemonistic","hemat","hemat biaya","hemat energi","heran","hero","heroik","heroize","heros","hestitant","hibur","hideousness","hiliarious","hina","hingar-bingar","hiperbola","hiruk-pikuk","histeri","histeria","histeris","hitam","hiu","ho-hum","hoodium","hore","hormat","horor","horrendously","horrifys","horror","hotcake","hubungan","hujan es","hukuman","hukuman penjara","hukuman setimpal","humor","hung","hutang","huyung","hypocricy","iba","iblis","ideal","idealnya","idilis","idiocies","idiot","idola","igauan","ih","ikhwan","ikut campur","ilahi","ilegal","ilu","Iluminati","ilusi","imaculate","imajinatif","imajiner","iman","immoderately","impedansi","imperialis","implausibly","implikasi","impolitely","impor","imposers","impossiblity","impoten","imprecisely","impresif","impressiveness","improbably","impudently","impulsif","impunitas","imut","inadverent","inadverently","inadvisably","inanely","incapably","incompetently","incompliant","inconsequentially","inconsequently","inconsiderately","inconveniently","incorrigibly","indah","indecently","indecisively","indeterminably","indiscreetly","indiscriminating","individual","indoktrinasi","inefficacy","inefficien","ineloquent","ineloquently","ineptly","inescapably","inestimably","inexpertly","inexplainable","infalibilitas","infamously","infeksi","inferioritas","infiltran","inflamasi","inflammed","inflasi","informasi yang salah","infuriatingly","ingin sekali","ingusan","inhibisi","inhospitality","inimically","inkonsistensi","inkonstitusionil","inordinately","inovasi","inovatif","inpressed","insan","insensitively","insociable","inspirasi","inspirasional","inspiratif","instan","instant","instrumental","insubstantially","insultingly","insupportably","insurmountably","intefere","inteferes","integral","intelijen","intens","interupsi","intim","intimidasi","intimidatingly","intolerablely","intrik","intrusi","intuitif","invaluablely","invasif","invidiously","invidiousness","irama","irasional","irasionalitas","irately","iri","iritasi","irking","irks","irksomely","irksomeness","irksomenesses","ironi","ironis","ironisnya","irragularity","irrationals","irrecoverable","irrecoverableness","irrecoverablenesses","irrecoverably","irredeemably","irreformable","irreplacible","irretating","isolasi","Istimewa","istirahat","isu","isyarat","jadah","jagoan","jahanam","jahat","jalan buntu","jalan keluar","jaminan","janggal","janji","Jantan","Jarang","jatuh","jatuh sakit","jauh","jawa","jealousness","jeeringly","jelaga","jelas","jelatang","jelek","jelimet","jelu","jempol ke bawah","jempolan","jempol-down","Jenaka","jengkel","jerat","jerawat","jeritan","jernih","jijik","Jinak","jiwa","Jomplang","jompo","jorok","jrg","juara","jubiliant","judder","juddering","judders","judes","jujur","jumlah sedikit","jumplang","jutter","jutters","kabur","kabut","kacau","kacung","kadaluarsa","kadung","kafir","kagum","kain kafan","kaku","kaku,","kalah","kalahan","kambing hitam","kambuh","kampung Yahudi di kota","kampungan","kanan","kandang","kandas","kanibal","kanker","kapak","kapalan","kapital","kaput","karakter","karat","karikatur","karisma","karismatik","karper","kartun","karya","kasar","kasar menggatalkan","kasih sayang","kasihan","kata klise","kata-kata kasar","kata-kata kotor","kaya","kaya fitur","ke belakang","keadaan acuh tak acuh","keadaan buruk","keadaan pingsan","keadaan sulit","keadaan yg menyedihkan","keadaan yg sebenarnya","keadilan","keagungan","keairan","keajaiban","keanggunan","keangkeran","keangkuhan","keaslian","kebahagiaan","kebaikan","kebajikan","kebal","kebanggaan","kebangkitan","kebaruan","kebebasan","kebenaran","kebencian","keberanian","keberatan","keberbahayaan","keberhasilan","keberlanjutan","kebersamaan","kebersihan","keberuntungan","kebesaran","kebetulan","kebiadaban","kebiasaan","kebijaksanaan","kebingungan","kebiri","kebisingan","kebobolan","kebocoran","kebodohan","kebohongan","kebosanan","kebrutalan","kebuntuan","keburukan","kecabulan","kecakapan","kecanduan","kecantikan","kecapaian","kecelakaan","kecelakaan kapal","kecemasan","kecemburuan","kecemerlangan","kecenderungan","kecepatan","kecerdasan","kecerdikan","kecerobohan","kecewa","kecil","kecil hati","kecongkakan","kecurangan","kecurigaan","kedangkalan","kedengkian","kedua","keemasan","keenakan","keengganan","kefanatikan","kegagalan","kegairahan","keganasan","kegarangan","kegelapan","kegelisahan","kegembiraan","kegembiraan yg meluap-luap","kegemparan","kegemukan","kegilaan","kegoyangan","kegugupan","kehabisan","kehancuran","kehangatan","kehebohan","Keheranan","kehilangan","kehilangan keseimbangan","kehinaan","keindahan","keinginan","keingkaran","keintiman","keirasionalan","kejahatan","kejam","kejang","kejanggalan","kejangkitan","kejatuhan","kejayaan","kejelasan","kejelekan","kejengkelan","keji","kejujuran","kejut","kekacauan","kekaguman","kekakuan","kekal","kekalahan","kekanak-kanakan","kekasih","kekecilan","kekejaman","kekejamannya","kekeliruan","kekencangan","kekenyangan","kekerasan","kekerasan pendirian","kekesalan","kekhawatiran","kekotoran","kekuatiran","kekurangan","kekurangpekaan","kelakuan buruk","kelalaian","kelambanan","kelancangan","kelancaran berbicara","kelangkaan","kelaparan","kelas atas","kelas kedua","kelas satu","kelas utama","kelayakan","kelelahan","kelem","kelemahan","kelemahan karena usia tua","kelembutan","kelesuan","keletihan","kelezatan","kelimpahan","kelincahan","kelip redup","keliru","kelucuan","keluhan","kelupaan","keluwesan","kemacetan","kemajuan","kemakmuran","kemalangan","kemalasan","kemampuan","kemandekan","kemantapan","kemarahan","kemasukan setan","kemasyhuran","kematangan","kematian","kemauan baik","Kemayu","kembali","kemegahan","kemelaratan","kemenangan","kemenduaan","kemerosotan","kemewahan","kemiskinan","kemudahan","kemunafikan","kemunduran","kemurahan hati","kemurkaan","kemustahilan","kenal","kenangan","kendor","kendur","kenekatan","kenikmatan","kental","kenyamanan","kenyataan","kepahitan","kepala","kepala batu","kepala-sakit","kepanjangan akal daya","kependekan","kepentingan","kepentingan diri sendiri","kepercayaan","kepicikan","kepincut","kepuasan","kepuasan diri","keputusasaan","keputusasan","keracunan","keraguan","keramahan","keramat","kerangka","keranjingan","keras","keras hati","keras kepala","keras-liner","kerasnya","kerdil","kerelaan","keren","kerendahan hati","kerepotan","kereta","keretakan","keriangan","keributan","kerinduan","kering","keriput","keriuhan di pawai","kerja","kerja keras","kerlip","keroncongan","kerubin","kerugian","kerusakan","kerusuhan","kerut","keruwetan","kesabaran","kesal","kesalahan","kesalahan hitung","kesalahpahaman","kesalehan","kesamaan","kesatuan","kesayangan","kesedihan","kesegeraan","kesehatan","kesejahteraan","kesel","kesembronoan","kesenangan","kesendirian","kesengitan","kesengsaraan","kesepian","keserakahan","keseriusan","kesesakan","kesetiaan","kesilauan","kesombongan","kesopanan","kesucian","kesukaan","kesulitan","kesungguhan","kesuraman","kesyahidan","ketabahan","ketajaman","ketakutan","ketamakan","ketat","ketegangan","ketegasan","ketekunan","ketenangan","ketenaran","keterbatasan","keterbelakangan","keterbukaan","keterlaluan","ketiadaan","ketiadaan rasa hormat","ketidakabsahan","ketidakadilan","ketidakakuratan","ketidakamanan","ketidakbahagiaan","ketidakberdayaan","ketidakbijaksanaan","ketidakcakapan","ketidakcocokan","ketidakcukupan","ketidakefektifan","ketidakefisienan","ketidakjelasan","ketidaklogisan","ketidakmampuan","ketidakmampuan menyesuaikan diri","ketidakmungkinan","ketidakmurnian","ketidakpantasan","ketidakpedulian","ketidakpercayaan","ketidakrelevanan","ketidaksabaran","ketidaksamaan","ketidakseimbangan","ketidaksempurnaan","ketidaksenonohan","ketidaksetaraan","ketidaksopanan","ketidakstabilan","ketidaksusilaan","Ketidaktelitian","ketidakteraturan","ketidaktoleranan","ketidaktulusan","ketinggalan","ketrampilan","ketukan","ketulusan","keuletan","keunggulan","keuntungan","kewajiban","kewalahan","kewaspadaan","kezaliman","khawatir","khayalan","khayalan belaka","khayali","khianat","khusus","kikir","kikuk","kilau","kini","kios","kisi","Kisut","klasik","klik","klise","knalpot","kocak","koheren","koherensi","kokoh","kolot","komitmen","kompak","kompetitif","kompleks","komplemen","komplementer","komplikasi","komplotan","kompong","komunis","kondusif","konflik","konfrontasi","kongkalikong","konservatif","konsesi","konsisten","konspirasi","konspiratif","konspirator","konstruktif","kontaminasi","kontensius","kontinuitas","kontra","kontradiksi","kontradiktif","kontraproduktif","kontra-produktif","kontras","kontribusi","kontroversi","kontroversial","konyol","kook","kooky","kooperatif","koperasi","koplak","korban","korban kecelakaan","korek api","korosi","korosif","korup","korupsi","kosong","Kotor","kotoran","kram","kreatif","krg","krisis","kritik","kritikus","kritis","kronis","krusial","ksl","kualitas","Kualitas terbaik","kualitas tinggi","kuasa","kuat","kuatir","kucing gemuk","kucing-kucing gemuk","kudung","kue panas","kukuh","kumuh","kumus","kuno","kurang","kurang ajar","kurang baik","kurang berkembang","kurang dikenal","kurang lengkap","kurang menarik","kurang pengalaman","kurang sehat","kusut","kutukan","labil","labu","lagging","laggy","lags","lagu","lahan subur","lalai","lalim","lalu-parit","lama","lamban","lambast","lambat","lame-duck","lamentably","lancang","lancar","landasan","langka","languorously","lantang","lapang","larangan","larut","latency","laudably","laughably","lawan","lawas","layak","lead","lebam","lebat","lebih baik","lebih baik dari perkiraan","lebih buruk","lebih cemerlang dr","lebih cepat","lebih dikenal","lebih disukai","lebih kencang","lebih keras","lebih kuat","lebih lambat","Lebih memilih","lebih mudah","lebih murah","lebih suka","lebih tebal","lebih tenang","lebihan","lebih-bertindak","lebur","lecet","LED","ledakan","lega","legal","legendaris","leha","lekat","lekir","lekuk","lelah","lelucon","lemah","lemah lembut","lemak","lemas","lembab","lembek","lembut","lemon","lemot","lendir","lengkap","lengket","lesu","letal","letch","letih","lewdly","lezat","liar","liberal","licentiously","licik","licin","lier","limbah","limbung","lincah","linglung","lintah","Lionhearted","lirik","liris","lividly","lmyn","loathly","loathsomely","logis","lola","longgar","los","lovably","love","loyalitas","luah","luar","luar biasa","luas","lubang angin","luckiness","lucu","lucu-belum-provokatif","lugu","luka","luka bakar","luka lecet","lumayan","lumpuh","lumpur","lunak","lunaticism","lupa","lurus","lusuh","luwes","Maaf","mabuk","mabuk kepayang","mafia","mahal","mahir","main mata","main perempuan","mainan kerincingan","main-main","maju","makanan","makian","makin","makmur","maks","maksimal","malaikat","malang","malapetaka","malas","malcontented","maledict","males","malu","mampat","mampu","mancung","mandek","manfaat","Mangles","mangling","manik","manipulasi","manipulatif","manipulator","manis","manja","manjur","manusiawi","mapan","marah","marginal","marjinal","martabat","martir-seeking","marvelousness","masalah","masalah-bebas","masam","master","masuk akal","matang","mati","mati kelaparan","mati lemas","mati rasa","mati-matian","mati-murah","mati-on","matte","mau bertobat","mau kalah","mau tidak mau","mawas","mawkishly","mawkishness","max","maximal","medeni","megah","megap-megap","mekar","melalaikan","melambat","melampaui","melampiaskan","melancarkan","melanggar","melanggar hukum","melanggar susila","melankolis","melarang","melarikan diri","melawan hukum","melayani diri sendiri","melayu","melebihi","melebih-lebihkan","melecehkan","meledak","melelahkan","melemahkan","melengkapi","melengking","melengkingkan","melengkung","melenyapkan","melepuh","meleset","meletakkan-off","melibatkan","melimpahi","melindungi","melodramatis","melongo","melukai","melumpuhkan","melunakkan","meluruskan","memabukkan","memadai","memadamkan","memakan waktu","memaksa","memaksakan","memalsukan","memalukan","memamerkan","memanaskan","memancing","memandang dgn marah","memandang rendah","memanggil","memanjakan","memantapkan","memar","memarahi","memarahkan","memarut","memastikan","mematahkan semangat","mematikan","membagi","membahas","membahayakan","membakar","membalas","membalikkan","membandingi keindahan puisi","membanjiri","membantah","membantu","membara","membatalkan","membatasi","membatu","membayar","membayar lebih","membebaskan","membeku","membekukan","membela","membelit","membenci","membengisi","memberanikan","memberatkan","memberdayakan","memberi penerangan","memberkati","memberontak","membesarkan hati","membesut","membiasakan","membinasakan","membingungkan","membohong","memboikot","memboikot dr masyarakat","membolos","membombardir","membongkar","membosankan","membuang","membuang waktu","membuat","membuat bersedih hati","membuat bingung","membuat kasar","membuat lebih baik dr","membuat malu","membubuhi gula","membudayakan","membujuk","membuktikan","membunuh","memburuk","memburukkan","membusuk","membutakan","memecat","memelihara","memeluk","memenjarakan","memenuhi","memenuhi syarat","memeras","memerciki","memeriahkan","memfavoritkan","memfitnah","memihak","memikat","memikat hati","memimpin","memisahkan","memiskinkan","memohon","memperbaiki","memperbaiki akhlak","memperberat","mempercantik","mempercayakan","memperdaya","memperdayakan","memperingatkan","memperkaya","memperkosa","memperkuat","memperlambat","mempermanis","memperoleh kembali","mempersiapkan","mempesona","mempolemikkan","mempropagandakan","memprotes","memprovokasi","memuakkan","memuaskan","memuaskan diri","memudahkan","memudarnya","memuja","memujanya","memuji","memuji-muji terlebih-lebihan","memukau","memukul","memukul dgn tongkat","memukul mundur","memuliakan","memulihkan","memuntahkan","memurahkan","memurnikan","memusnahkan","memutar","memutarbalikkan","memutuskan","menabrak","menahan","menakjubkan","menakuti","menakutkan","menambah","menampar","menanamkan","menang","menangis","menantang","menarik","menarik kembali","menarik perhatian","menariknya","menaruh simpati","menasihati","menawan","mencabut","mencabut perlindungan hukum","mencaci","mencampuradukkan","mencampuri","mencap","mencekik","mencela","mencemari","mencemaskan","mencemoohkan","mencengangkan","mencibir","mencicit","mencintai","mencium","mencolok","mencopoti","mencubit","mencuci otak","mencukupi","mencuri","mencurigakan","mendadak","mendakwa","mendalam","mendamaikan","mendapatkan","mendatang","mendatangkan","mendebarkan","mendeklamasikan","menderita","menderita khayalan","menderita sekali","mendesak","mendesis","mendewakan","mendidih","mendominasi","mendorong","menduduki","mendukung","menebus","menegakkan","menegaskan","menegaskan lagi","menegur","menekan","menekankan","menenangkan","menentang","menentukan","menerangi","menerima","menertawakan","mengabaikan","mengaburkan","mengacak","mengacaukan","mengagetkan","mengagumi","mengagumkan","mengagungkan","mengairi","mengakali","mengaku","mengakui","mengalah","mengalahkan","mengalihkan","mengambil alih","mengamputasi","mengamuk","mengancam","mengancam jiwa","menganggap","mengangkat","mengangkat bahu","menganiaya","menganjurkan","mengasyikkan","mengatakan","mengatasi","mengecam","mengecewakan","mengecilkan","mengecilkan hati","mengecoh","mengedan","mengejek","mengejuntukan","mengejutkan","mengeksploitasi","mengeluarkan","mengeluh","mengelupas","mengembara","mengemis","mengendapkan","mengepung","mengerang","mengeras","mengerikan","mengering","mengerjakan dgn kurang baik","mengerti","mengesahkan","mengesalkan","mengesankan","menggagalkan","menggairahkan","menggampangkan","mengganggu","mengganggu ketenangan","menggantung","menggaruk","menggarut","menggasak","menggelapkan","menggelegar","menggelenyar","menggelepar","menggeliat-geliut","menggelikan","menggelisahkan","menggelitik","menggembirakan","menggemparkan","menggentari","menggerakkan","menggeram","menggerenyet","menggertak","menggerutu","menggigil","menggigit","menggila","menggiling","menggoda","menggoyang","menggugah","menggugat","menggugurkan","menggulingkan","menggusarkan","menghadapi","menghadiahkan","menghalangi","menghalau","menghaluskan","menghambat","menghancurkan","menghantui","menghapus","menghapuskan","menghargai","menghasut","menghebohkan","mengherankan","menghibur","Menghidupkan","menghidupkan kembali","menghilangkan","menghina","menghinakan","menghindari","menghormat","menghormati","menghujat","menghukum","menghukum sebelum memeriksa","mengidealkan","mengidolakan","mengigau","mengilhami","mengindoktrinasikan","menginginkan","mengingkari","menginjak-injak","mengintai","mengintimidasi","mengintip","mengisap","mengisyaratkan","mengkhawatirkan","mengkhianati","mengkritik","mengoceh","mengolesi","mengolok-olok","mengomel","mengorbankan","mengotori","mengotorkan","mengoyakkan","menguap","menguasai","mengucapkan selamat","mengumpat","mengumumkan kekurangan","mengundurkan diri","mengungguli","menguntungkan","mengurangi","menguras","Mengurungkan","mengusir","mengutuk","meniadakan","menidurkan","menikmati","menimbulkan","menimbulkan kebencian","menimbulkan perasaan cinta","menimbulkan rasa antusias","menimpa","menindas","meninggal dunia","meninggalkan","meninggikan","meningkat","meningkatkan","meninju","menipu","menjadi kaya","menjadi lembut","menjadi makmur","menjadi terlalu panas","menjamin","menjanjikan","menjarah","menjatuhkan","menjauhkan","menjelekkan","menjemukan","menjengkelkan","menjerat","menjerit","menjijikan","menjijikkan","menjiplak tulisan","menodai","menolak","menonjol","mentah","mentereng","menuduh","menumbangkan","menumpahkan","menunda","menundukkan","menuntut","menurun","menurunkan","menurunkan nilai","menurut","menusuk","menutup jalan","menyakiti","menyakitkan","menyala","menyalahgunakan","menyalahkan","menyalip","menyangkal","menyanjung","menyapa","menyaring","menyayangkan","menyayat hati","menyebalkan","menyederhanakan","menyedihkan","menyegarkan","menyelaraskan","menyelesaikan","menyelinap","menyembuhkan","menyenangkan","menyengat","menyentakkan","menyerah","menyeramkan","menyerang","menyerbu","menyeringai","menyesali","menyesalkan","menyesatkan","menyesatkan pikiran","menyetujui","menyia nyiakan","menyiangi","menyihir","menyiksa","menyilaukan","menyindir","menyinggung","menyita pikiran","menyogok","menyolok","menyombongkan","menyubsidi","menyukai","menyumbat","menyumpahi","menyusahkan","menyusul","menyusup","menyusut","meracuni","meragukan","merah","merajalela","merajuk","merampas","merana","merancang","merangsang","merasa jijik","meratap","meratapi","meraup","merayakan","merayap","merayu","merebut","mereda","meredakan","merekomendasikan","meremajakan","meremas","meremasnya","meremehkan","merencanakan","merendahkan","merendahkan martabat","merengek","merenung","merepet","meresahkan","meretas","merevolusi","merevolusionerkan","meriah","meriangkan","merinding","meringis","merobek","merokok","meromantiskan","merosot","merriness","merugikan","meruntuhkan","merusak","merusak akhlak","merusak bentuk","merusakkan","mesmerizingly","messes","mesum","mewah","meyakinkan","mimpi buruk","min","minim","minimal","miraculousness","miring","misalign","misaligns","misbecoming","miserableness","miskin","mislike","misses","Misteri","misterius","mistified","mitos","mls","mlz","modern","momok","monstrositas","monstrously","montok","monumental","moral","moralitas","mordantly","mubazir","muda","mudah","mudah bergaul","mudah digunakan","mudah terharu","mudah tersinggung","mudah tertipu","mujarab","muka","mukjizat","mulia","multi-polarisasi","mulus","mulut","mundur","mungil","muntah","muntahan","murah","murah banget","murah hati","murahan","muram","murderously","murka","murni","murtad","murung","muslihat","mustahil","musuh","mutakhir","mutlak","nafsu berahi","nafsu berperang","naif","naik opelet","najis","nakal","nakut","nasib","nasional","nastiness","natural","nauseates","nauseatingly","ndut","nefariously","negatif","negeri","nekat","nenek","nepotisme","neraka","nettlesome","neurotik","ngambek","ngantuk","ngawur","ngeri","nggilani","ngobrol","ngomel","niggle","niggles","nightmarishly","nikmat","Nikmati","nitpick","nitpicking","noda","noda-noda","non-aktif","non-kekerasan","non-kepercayaan","nonresponsive","ntar","nyaman","nyaring","nyeri","nyonya","obat untuk segala penyakit","obrolan","obsesi","obsesif","obsessiveness","obyek","occluding","ocehan","offensiveness","ogah-ogahan","ok","omelan","omong kosong","ompong","opini","oportunistik","oposisi","optimal","optimis","optimisme","orang asing","orang bodoh","orang buangan","orang canggung","orang celaka","orang dungu","orang fanatik","orang gila","orang jahat","orang kikir","orang luar","orang membenci","orang miskin","orang yg berhenti berusaha","orang yg suka campur tangan dlm urusan orang lain","orang yg tak berterimakasih","orang yg tersesat","otokrat","otokratis","otoriter","Otot-meregangkan","outrageousness","outshone","overacted","overbearingly","overemphasize","over-hyped","overpayed","over-seimbang","overstatement","overstatements","overtaxed","over-valuasi","overzealously","overzelous","padat","paduka","pagar","pahit","pahlawan","pahlawan wanita","painfull","paksa","paksaan","paling","paling aneh","paling beruntung","paling dikenal","paling kejam","paling lambat","paling menakutkan","palsu","pamperedly","pamperedness","pampers","panas","panci","pandai berbicara","pandangan marah","pandangan yg menghinakan","Pandering","panggilan","panggilan dari pengadilan","panik","panjang","panorama","pantas","pantes","paradoks","paradoksal","parah","paralize","paranoia","parasit","paria","parodi","partisan","pasif","pasrah","pasti","patah","patah hati","patriot","patriotik","patuh","patung","payback","payudara","pecah","pecahnya","pecandu","pecundang","pedas","pedih","peka","pekerja buruk","pekerjaan rumah","Pekerjaan-pembunuhan","pelabuhan","pelacur","pelakunya","pelalaian","pelamun","pelanggar","pelanggar hukum","pelanggaran","pelanggaran hukum","pelan-pelan","pelarian","pelawak","pelemahan","pelepasan nafsu berahi","pelit","pelonggaran","pelupa","pemalas","pemalsuan","pemarah","pembakar","pembakaran","pembantaian","pembaruan","pembatalan","pembaur","pembebasan","pembekuan","pembela","pembenci","pembengkakan","pemberang","Pemberdayaan","pemberian Tuhan","pemberontakan","pembersih","pembetulan","Pembobolan","pembohong","pembongkar","pemborosan","pembual","pembuangan","pembuatan","pembunuh","pembunuhan","pembunuhan besar-besaran","pemecah masalah","pemecahan","pemenang","pemenuhan","pemerasan","pemerkosaan","pemfitnah","pemfitnahan","pemisahan","pemotongan","pemukulan","pemulihan","pemusnahan","pemutarbalikan","penaklukan","penakut","penalti","penangkapan","pencegah","pencemar","pencemas","pencerahan","pencinta","pencurian","pendapat","pendek","pendendam","penderita","penderitaan","penderitaan mendalam","pendewaan","pendukung","penebusan","penegasan kembali","penekanan","penentuan nasib sendiri","penerima","penerobosan","pengabaian","pengacau","pengaduan","pengagum","pengakuan","pengampunan","penganggur","penganiaya","penganiayaan","pengap","pengaruh","pengasingan","pengasuhan","pengeboman","pengecut","pengecut yg kejam","pengembalian dana","pengemis","pengenaan","pengepungan","pengganggu","pengganti","penggemar","penggerutu","penghancuran","Penghargaan","penghasut","penghemat","penghematan","penghematan biaya","penghiburan","penghinaan","penghormatan","penghukuman","pengingatan","pengiring jenazah","pengkhianat","pengkhianatan","pengkritik","pengobrol","pengotor","penguasaan","pengumpatan","pengunduran diri","pengurangan","penindas","penindasan","peninggian derajat","peningkatan","penipu","penipuan","penjaga perdamaian","penjahat","penjara","penjarah","penjualan terbaik","penolakan","penting","penuh","penuh benci","penuh celaan","penuh curiga","penuh dosa","penuh gaya","penuh harapan","penuh kasih","penuh kebahagiaan","penuh kebajikan","penuh kepercayaan","penuh pengharapan","penuh perasaan","penuh peristiwa","penuh semangat","penuh sesal","penuh sukacita","penumpasan","penundaan","penurunan","penurut","penyadapan","penyakit","penyakit-","penyakit jiwa","penyalahgunaan","penyangkalan","penyayang","penyederhanaan yg berlebih-lebihan","penyelamat","penyelewengan","penyembunyian","penyerbu","penyergapan","penyesalan","penyesat","penyiksaan","penyimpang","penyimpangan","penyok","penyuapan","penyuburan","penyusup","peot","PEP","pepped","pepping","peradangan","perampas","perampasan","perangkap","perasaan geli","perasaan kagum","perasaan suka cita","perasaan waswas","perawan tua","perayaan","perbaikan","perbedaan","perbudakan","percaya","percaya diri","perdamaian","perdana menteri","perebut","perempuan jahanam","perfidity","pergolakan","perhatian","periang","perih sekali","peringatan","perjanjian","perjuangan","perkelahian","perlakuan baik","perlakuan kejam","perlawanan","perlindungan","perlu","permai","permata","permohonan","permusuhan","perpecahan","persahabatan","persaingan","perselisihan","persetujuan","Persik","personalisasi","pertama di kelas","pertempuran","pertengkaran","pertengkaran sengit","pertikaian","pertinaciously","pertumbuhan tercepat","pertumpahan darah","pertunjukkan","perubahan","perusak","pesek","pesimis","pesimisme","pesimistis","pesona","pesta","pesta besar","pesuruh","petualang","PHK","PHK-senang","piala","picik","picketed","pidana","pidato panjang dan tajam","piket","pikir","pikiran-bertiup","pikun","pilih-pilih","pimpinan salah","pincang","pingsan","pintar","pinter","pisau","plasticky","playboy","pleasingly","pleasurably","plin-plan","plot","plus","plusses","pndpt","pntr","poisonously","polarisasi","polos","polusi","pontang-panting","populer","portable","positif","praktek","praktik","praktis","prasangka","pratfall","prefered","preferes","preposterously","prestasi","presumptuously","prh","pricier","prickles","prihatin","prik","primer","primitif","pro","proaktif","procrastinates","prodigiously","produktif","progresif","prohibitively","promotor","propaganda","propitiously","prospros","Protektif","protes","provokasi","provokatif","puas","pucat","pugnaciously","puitis","pujian","pukulan","pukulan keras","pukulan yg tdk keras","pulih","punah","puncak","punk","pura-pura","pusing","putih","putus","putus asa","quarrellous","quarrellously","quibbles","racun","radikal","radikalisasi","ragu-ragu","rahang-droping","rahang-menjatuhkan","rahmat","rajin","raksasa","rakus","ramah","ramah tamah","ramai","rambut rontok","rampasan","rangsang","rantingly","rants","rapi","raptureous","raptureously","rapturously","rapuh","rasa cemas","rasa gelisah","rasa hormat","rasa manis","rasa sakit","rasa suram","rasa tidak berterimakasih","rasa tidak enak","rasional","rasis","rasisme","rata","reaksioner","realis","realisasi","realistis","rebusan","record-pengaturan","redundansi","reformasi","regresi","regreted","rekomendasi","rel","rela","religius","remeh","remehkan","remorselessness","Renaisans","rendah","rendah dinilai","rendahan","rendah-harga","rentan","reprehensibly","reprehensive","represi","represif","repugnantly","repulsing","repulsively","repulsiveness","reputasi","reruntuhan","resah","reseptif","resesi","Resiko rendah","responsif","restrukturisasi","retak","retardedness","retorik","retoris","revengefully","revitalisasi","revoltingly","revolusioner","rewardingly","rewel","reyot","rhapsodize","ria","riang","ribut","ricer","ridiculously","righten","rigidness","rileks","rilex","ringan","ringkas","rintik","risiko","riuh","roboh","rock-bintang","rockstar","rock-star","rockstars","rohani","rollercoaster","romantis","rongseng","rongsokan","rontok","roomier","rremediable","ruam","rujuk","rumah kaca","Rumawi Timur","rumit","rumor","runtuh","rupa","rusak","rusuh","rutin","sabar","sabotase","sags","sah","saingan","sakit","sakit hati","sakit kepala","sakit punggung","sakit saraf","sakitan","sakit-dibentuk","sakit-digunakan","sakit-dikandung","sakit-dirancang","sakit-penggunaan","salah","salah arah","salah baca","salah hitung","salah informasi","salah membaca","salah menafsirkan","salah mengerti","salah mengucapkan","salah mengurus","salah menilai","salah menyebutkan","salah paham","salah pengertian","salah pikiran","salah saji","saleh","salju longsor","salut","sama sekali","samar","samaran","sambil tersenyum","sambilan","sambutan dgn tepuk tangan","sampah","sandal bakiak","sandera","sangat","sangat baik","sangat indah","sangat jahat","sangat lapar","sangat membahayakan","sangat memuaskan","sangat menakjubkan","sangat menarik","sangat menyayangi","sangat mudah","sangat penting","sangat sedikit","sangat sopan","sangat tepat","sanggahan","sanjungan","santai","santo","saran","sarankan","sarkas","sarkasme","sarkastik","saru","satiris","satisified","sayang","sayangnya","scandalously","scandel","scandels","scarily","scoldingly","scorchingly","sdkt","sebelum waktunya","secara bengkeng","secara bodoh","secara damai","secara dangkal","secara jahat","secara khianat","secara khusus","secara kuat","secara memalukan","secara mengejek","secara menggemparkan","secara menindas","secara menjijikkan","secara menyenangkan","secara pantas","secara produktif","secara serampangan","secara tdk sehat","secara terbuka","secara tidak akurat","secara tragis","secepatnya","second-tier","sedang saja","sederhana","sedih","sedikit","segan","segar","segera","sehat","seimbang","sejajar","sekam","sekejap","sekop","sekrup-up","seksi","sekunder","selai","selamat","SELAMAT DATANG","selang","selayaknya","selektif","selfinterested","self-kritik","self-kudeta","selokan","semangat","semangat yg meluap-luap","sembarangan","sembrono","semburan","sementara","semi-terbelakang","sempit","sempurna","semrawut","semuanya","senang","sendirian","seneng","sengaja","sengit","sensasi","sensasional","sensasionil","sentakan","seorang penyendiri","seorang yg penuh bertenaga","seorang yg suka merusak kesenangan orang lain","sepakan","sepanjang masa","sepatutnya","sepele","sepenuh hati","seperti","seperti malaikat","seperti pasir","seperti patung","seperti perang","seperti raja","Sepi","serak","serakah","seram","serampangan","serangan","serangan balik","serangan gencar","serasi","serba cepat","serba guna","serba-nebula","serempak","serius","serong","serpihan","sesak","sesak napas","sesat","sesuai","sesuai dgn mode terakhir","sesuatu yg buruk","sesuatu yg sangat dibenci","sesungguhnya","setajam silet","setan","setengah","setengah hati","setia","setiti","setuju","sewajarnya","sewenang-wenang","sexy","sh * t","shamefulness","shamelessness","shimmeringly","sia sia","sial","sialan","siap","sia-sia","sifat agresif","sifat hati-hati","sifat takut-takut","sifat tdk bersemarak","sifat tdk terkalahkan","sigap","sihir","sikap","sikap keras kepala","sikap melindungi","sikap memihak","sikap tenang","sikap tunduk","siksaan","silakan","silau","sillily","simpatisan baik","sindiran","sindroma","sinfully","singkat","sinis","sinisme","sinisterly","sinting","siput","sisa","siuman","skandal","skeptis","skittishly","slammin","slogging","slogs","sloooooooooooooow","sloooow","slooow","sloow","sloww","slowww","slowwww","slumpping","smuttier","smuttiest","snags","snarky","sng","snobish","sobek","sok","sok aksi","solidaritas","sombong","sopan","sorak kegirangan","sosial","spektakuler","spellbindingly","spendy","spews","spitefulness","spoilages","spoilled","spontan","spookier","spookiest","spookily","spoonfed","sporadis","sporty","stabil","stabilitas","stagnasi","standar","starkly","state-of-the-art","statis","staunchness","steadiest","steal","stellarly","stereotip","stgh","stirringly","stres","stress","stressfully","stupendously","stupified","Stupify","suam","suar","suara","suasana menekan","suavely","sublim","sub-par","subsidi","substantif","subur","subversi","subversif","suci","sucky","sudah cukup","sueing","suka","suka beperkara","sukacita","sukses","sulit","sumberdaya","sundal","sungguh","sungguh-sungguh","sunyi","super","supremasi","supurb","supurbly","suram","surealis","surga","surgawi","susah","swankier","swankiest","swasembada","swatantra","syok","syukur","taat","taat hukum","tabah","tabir asap","tabu","tabungan","tahan karat","tahan lama","tahi","tajam","taji","tak ada artinya","tak ada salahnya","tak ada taranya","tak baik","tak berarti","tak bercacat","tak berdaya","tak bergairah","tak berguna","tak beriman","tak bermoral","tak bermutu","tak bernyawa","tak berperikemanusiaan","tak berpindah-pindah","tak bersedia","tak bersuara","tak bersyarat","tak berterimakasih","tak biasa","tak disengaja","tak henti-hentinya","tak jelas","tak karuan","tak kenal ampun","tak kenal takut","tak layak","tak menentu","tak pernah puas","tak populer","tak putus-putusnya","tak tahu malu","tak tentu","tak terbantahkan","tak terbatas","tak terbayangkan","tak terbedakan","tak terdamaikan","tak terduga","tak tergantikan","tak terhindarkan","tak terhitung","tak terkalahkan","tak terkatakan","tak terkendalikan","tak terlupakan","tak ternilai","tak terpadai","tak terpecahkan","tak terpenuhi","tak tertahankan","takdir","takhyul","takjub","takut","takzim","tamak","tampan","tamparan","tanda","tandus","tangan ke bawah","tangkas","tangki","tank","tanpa alasan","tanpa ampun","tanpa belas","tanpa belas kasihan","tanpa berpikir","tanpa cela","tanpa cinta","tanpa dasar","tanpa disadari","tanpa dosa","tanpa hasil","tanpa henti","tanpa hukum","tanpa kompromi","tanpa malu","tanpa malu-malu","tanpa pandang bulu","tanpa rasa sakit","tanpa rasa takut","tanpa rebewes","tanpa sadar","tanpa tenaga","tanpa tujuan","tantangan","tarnishes","tawanan","tawar","tawaran","Tawaran rigging","tawar-menawar","tdk ada reaksi","tdk bagus","tdk benar","tdk beralasan","tdk berbahaya","tdk berhati-hati","tdk bijaksana","tdk cakap","tdk efisien","tdk jelas","tdk kuat","tdk layak","tdk lazim","tdk masuk akal","tdk menurut kenyataan","tdk mungkin","tdk pantas","tdk penting","tdk ramah","tdk resmi","tdk sebanding","tdk seimbang","tdk sopan","tdk tegas","tdk tepat","tdk terbaca","tdk terbukti","tdk terdengar","tdk terduga","tdk tertekan","tdk tetap","tdr","tebal","tediously","teduh","tegang","tegar","tegas","teguh","teguran","tekanan","teknik","teknis","tekun","teladan","telanjang","teliti","temanmu","tembakan penangkis udara","tembel","tempa","tempat barang rongsokan","temptingly","tenaga","tenang","tenang dan serius","tengah","tenggelamnya","tenuously","tepat","tepat pd waktunya","tepat waktu","tepuk tangan sorak","teralihkan","teramat","teramati","terampil","terang","terangsang","terang-terangan","terasa tdk enak","terasing","terbaik","terbaik-performing","terbalik","terbandingkan","terbatas","terbayangkan","terbebani","terbelakang","terberat","terbersih","terbesar","terbodoh","terbuang","terbujuk","terbukti","terbunuh","terburu nafsu","terburuk","tercela","tercemar","tercengang","tercepat","tercinta","terdefinisi","terdelusi","terdengar","terduga","terelakkan","terencana","terfragmentasi","tergagap","terganggu","tergantikan","tergenang","tergesa-gesa","terhalang","terhambat","terhina","terhormat","terhukum","teriakan","terik","terima","terima kasih","terinfeksi","terinfestasi","terjal","terjangan","terjangkau","terjawab","terjelek","terkejar","terkejut","terkekang","terkemuka","terkena","terkenal","terkenal di dunia","terkenal jahat","terkesima","terkuat","terkupas","terkutuk","terlalu","terlalu bersemangat","terlalu besar","terlalu mahal","terlalu memanjakan","terlalu tinggi","terlambat","terlantar","terlarang","terlepas","terlibat","termiskin","termotivasi","termudah","termurah","ternama","ternganga keheranan","ternoda","terobosan","terompet","teror","teror-genic","terorisme","terpadu","terpanas","terpelajar","terpencil","terpengaruh","terpenting","terperangkap","terperanjat","terpercaya","terpesona","terpuji","terribleness","terrifically","tersakiti","tersandung","tersangka","tersangkut","tersedak","tersedia","terselesaikan","terselubung","tersembunyi","tersembunyi dan membahayakan","tersendat","tersentak-sentak","tersenyum","tersiksa","tersinggung","tersumbat","tertagihnya","tertahankan","tertanam di hati","tertancap","tertandingi","tertarik","tertib","tertinggal","tertinggi","tertipu","terurai","terus terang","terutama","tetap","tetchily","threesome","thrillingly","thrills","thumb-up","tiada bandingan","tiba-tiba","tidak","tidak ada","tidak adil","tidak aman","tidak bahagia","tidak benar","tidak berharga","tidak bijaksana","tidak bisa diandalkan","tidak cukup","tidak curiga","tidak dapat diandalkan","tidak dapat digunakan","tidak dapat dipertahankan","tidak dapat diterima","tidak dibutuhkan","tidak didukung","tidak diketahui","tidak efektif","tidak efisien","tidak etis","tidak jelas","tidak kompatibel","tidak kompetitif","tidak konsisten","tidak kreatif","tidak lengkap","tidak logis","tidak mampu","tidak masuk akal","tidak memadai","tidak membantu","tidak memenuhi syarat","tidak memiliki","tidak memuaskan","tidak menarik","tidak mendapatkan hasil yang","tidak mendukung","tidak nyaman","tidak ortodoks","tidak pantas","tidak peduli","tidak peka","tidak penting","tidak produktif","tidak ramah","tidak rasional","tidak relevan","tidak sabar","tidak sempurna","tidak sepertinya","tidak tepat","tidak tersedia","tidak tulus","tidak wajar","tidur","tikungan","tikus","timbunan","timidness","timpang","tindak pencegahan","tindakan ekstrimis","tindakan yg bodoh","tinggi","tinggi hati","tinggi-harga","tinjauan ke masa depan","tinju","tipis","tipu","tipu daya","tipu muslihat","tiringly","titillatingly","tng","tokoh","tolol","topangan","tops","torturously","totaliter","tragedi","tragis","traped","trauma","traumatis","travesties","tren","trendi","troublesomely","troublingly","truf","tuduhan","tukang daging","tukang fitnah","tukang jualan","Tukang onar","tulang punggung","tuli","tulus","tumbang","Tumbles","tumbuh dengan cepat","tumpul","tuna karya","tunggu dulu","tunggul","tusukan","tyrannically","uang kembali","uap","ugal-ugalan","ulang","ular berbisa","ultimatum","ultra-garis keras","ultra-tajam","ulung","umpan","umpatan","unacceptablely","unaccessible","unbearablely","uncomfy","undependability","undercut","underpaid","underpowered","un-dilihat","undisputably","undissolved","unggul","unggulan","unik","unilateralisme","unintelligile","unipolar","unkindly","unlamentable","unlamentably","unnervingly","unpleasantries","unprove","unproves","unproving","unreachable","unscrupulously","unsettlingly","unspeakablely","unthinkably","untuk sementara","untung","untungnya","unusably","unuseable","unuseably","unviewable","unwatchable","unyu","upgradable","upgrade","upgradeable","upliftingly","upliftment","uproarous","uproarously","upseting","upsettingly","usang","utama","utuh","vagina","variasi","vengefully","vexingly","vileness","villainously","villianous","villianously","villify","vindictively","virulently","virus","visioner","volatil","vulgar","wabah","Wack","wahah","wahyu","wajah","wajar","wajib","wakil","waktu dekat","waras","waria","warna","warna-warni","waspada","wastafel","wastefulness","was-was","wayang","whooa","Whooooa","wimpy","won","wonderous","wonderously","worthlessly","Wow","wowing","wows","wreaks","wrip","wripped","wripping","yakin","yang funky","yang menghambat","yang tidak diinginkan","yang tidak perlu","yay","yg beralamat buruk","yg berbau busuk","yg berisi ucapan selamat","yg berjasa","yg berkelakuan baik","yg berkembang sama sekali","yg bermaksud baik","yg bersalah","yg bersungut","yg dihormati karena tua","yg disesalkan","yg dpt digerakkan","yg dpt menembus","yg ingin membalas dendam","yg ingin tahu","yg ketinggalan jaman","yg membayangkan kegembiraan hati","yg membelok perhatian","yg memfitnah","yg memperhatikan","yg memperjelas","yg mempesonakan","yg memuji-muji berlebih-lebihan","yg memundurkan","yg menandakan resesi","yg mendendam","yg menenangkan","yg mengasyikkan","yg mengejek","yg mengeluh","yg mengganggu","yg menggerutu","yg menggiurkan","yg mengharukan","yg menghina","yg menghukum","yg menjengkelkan","yg menyakitkan hati","yg menyala kecil","yg menyedihkan","yg menyenangkan","yg menyiksa","yg menyusahkan","yg merajalela","yg merawankan hati","yg merugikan","yg merugikan diri sendiri","yg merusak diri","yg mudah percaya","yg patut disalahkan","yg sedikit diketahui","yg suka berperang","yg suka damai","yg suka mencampuri urusan orang","yg suka perang","yg tak diragukan lagi","yg tak disukai","yg tak ditentukan","yg tak dpt diampuni","yg tak dpt dibedakan","yg tak dpt dilaksanakan","yg tak dpt dipahami","yg tak dpt diperbaiki","yg tak dpt dipercaya","yg tak dpt dipertahankan","yg tak dpt disangkal","yg tak dpt ditebus","yg tak dpt ditentukan","yg tak dpt diubah","yg tak dpt menyesuaikan diri","yg tak mengetahui","yg tak meyakinkan","yg tak perduli","yg tak perlu dipersoalkan","yg tak tahu malu","yg tdk berdasar","yg tdk diganggu gugat","yg tdk diizinkan","yg tdk dijilid","yg tdk dpt dibedah","yg tdk dpt dibenarkan","yg tdk dpt dihiburkan","yg tdk dpt dipersalahkan","yg tdk dpt ditukar","yg tdk memungkinkan untuk melepaskan diri","yg tdk senang","yg tdk tahu sama sekali","zaps","zindik","zombie","best","ngehits","nntn","menonton","meragui","boleh","nyanyi","box office","boring","sebagus","sehidup","moments","ngalahin","sesenggukan","win","visual","muak","kasi","rating","terkejut");
		
		// keys for Twitter OAuth
		$settings = array(
		    'oauth_access_token' => "1430750114-nsW0ODE88uJsy68jd6xJqB2HJIWlrDKAE3DOzQW",
		    'oauth_access_token_secret' => "BzZSA3Z0rcYcGBaTBRjbn3FjOSvIKMqGGAPNZPMv3VI76",
		    'consumer_key' => "Tweak7j9XE7hcMWnrKoPTvFZW",
		    'consumer_secret' => "5d7WLg2jSRZQCRvC3yyS3ZlhGuFDnXGaOCF1Cunearu1d0akLu"
		);
		
		// train naive bayes classifier
		$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.neg', 'negative', 1000); //training with negative data
		$sat->trainAnalyzer(dirname(dirname(__FILE__)) . '/third_party/data.pos', 'positive', 1000); //trainign with positive data	
		
		$film_id = $this->input->post('film_id', TRUE); 
		$movie = $this->model_film->getFilm($film_id);
		$title = $movie[0]['title'];
		
		// begin twitter request -> with tag movieTitle or exact words of "movie's title" minus RT, only in bahasa indonesia, sorted by recent one
		
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$requestMethod = 'GET';
		
		$getfield = '?count=100&q=#'.str_replace(' ', '', $title).'+OR+"'.$title.'"+-RT&lang=id&result_type=recent';
		
		$twitter = new TwitterAPIExchange($settings);
		$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		
		$response = json_decode($response);
		
		// !!! === !!! === begin feature reduction --> delete username, url, hashtag, punctuations
		
		$result = []; // to store the tweets that had been reduced
		
		for ($i=0; $i<sizeof($response->statuses); $i++){
			$editedResult = strtolower($response->statuses[$i]->text); //$response->statuses[$i]->id   $response->statuses[$i]->created_at
			
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
			
			// put the result into array $editedResult
			array_push($result, $editedResult);
		}
		
		// !!! === !!! === begin rule-based system --> delete any tweets that are not a review
		
		$review = [];
		
		for ($i=0; $i<sizeof($result); $i++){
			$review[$i]['text'] = $nonreview[$i];
			
			// split tweet word by word and put it into an array, to compare easily
			$target = $nonreview[$i];
			$target = $this->splitSentence($target);
			$target = $target[0];
			
			// if there's any word that intersects (exist in tweet and in lexicon array), do:
			if (array_intersect($lexicon, $target)) 
				$review[$i]['status'] = 1;
			else // not a review, status = 2
				$review[$i]['status'] = 2;
		}
		
		// !!! === !!! === begin naive bayes
		for ($i=0; $i<sizeof($review); $i++){
			if ($review[$i]['status'] == 1){
				$sentimentAnalysisOfSentence = $sat->analyzeSentence($review[$i]['text']);
				$resultofAnalyzingSentence = $sentimentAnalysisOfSentence['sentiment'];
				$probabilityofSentenceBeingPositive = $sentimentAnalysisOfSentence['accuracy']['positivity'];
				$probabilityofSentenceBeingNegative = $sentimentAnalysisOfSentence['accuracy']['negativity'];
				
				if ($resultofAnalyzingSentence == "negative" || ($probabilityofSentenceBeingNegative>$probabilityofSentenceBeingPositive))
					$review[$i]['status'] = 0;
				else if ($resultofAnalyzingSentence == "positive")
					$review[$i]['status'] = 1;
				//else if ($resultofAnalyzingSentence == "Neutral") $review[$i]['status'] = 2;
				
				if (!$this->model_tweets->isExist($film_id, $review[$i]['text']))
					$this->model_tweets->insertTweet($film_id, $review[$i]['text'], $review[$i]['status']);
			} else
				$this->model_tweets->insertTweetButNotAReview($film_id, $result[$i], 2);
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
