<div id="mainBody">
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="row">
					<div id="gallery" class="span3">
						<img src="<?= $poster; ?>" style="width:100%"/>
						<br/><br/>
						<?= form_open('film/detail'); ?>
				   			<?= form_hidden('id', $id); ?>
							<button type="submit" name="tweets" value="Tweets" title="See Twitter Sentiment" class="btn btn-xs btn-info" style="width:100%"><span class="fa fa-twitter"></span> Lihat Sentiment Twitter</button>
				   		<?=  form_close(); ?>
					</div>
					<div class="span9">
						<h3><?= $title; ?></h3>
						<hr class="soft clr"/>
						<h4>Rating Film</h4>
						<table class="table table-bordered">
							<tbody>
								<tr class="techSpecRow">
									<td class="techSpecTD1">
										<img src="<?= base_url('assets/pictures/imdb.jpg'); ?>" style="width:25%" title="Rating IMDB"/>
										<span style="margin-left: 10px;"><?= $imdb_rating; ?></span>
									</td>
									<td class="techSpecTD1">
										<img src="<?= base_url('assets/pictures/metacritic.png'); ?>" style="width:25%" title="Metacritic score"/>
										<span style="margin-left: 10px;"><?= $metascore; ?></span>
									</td>
									<td class="techSpecTD1">
										<img src="<?= base_url('assets/pictures/logo_small.png'); ?>" style="width:25%" title="Rating ABCMovies"/>
										<span style="margin-left: 10px;"><?= $rating; ?></span>
									</td>
									<td class="techSpecTD1">
										<img src="<?= base_url('assets/pictures/twitter_neg.png'); ?>" style="width:25%" title="Banyak review negatif pada Twitter"/>
										<span style="margin-left: 10px;"><?= $twitter_negatif; ?></span>
									</td>
									<td class="techSpecTD1">
										<img src="<?= base_url('assets/pictures/twitter_pos.png'); ?>" style="width:25%" title="Banyak review positif pada Twitter"/>
										<span style="margin-left: 10px;"><?= $twitter_positif; ?></span>
									</td>
								</tr>
							</tbody>
						</table>
						<h4>Informasi Film</h4>
						<table class="table table-bordered">
							<tbody>
								<tr class="techSpecRow">
									<td class="techSpecTD1" style="width: 100px;">Sutradara</td>
									<td class="techSpecTD2"><?= $director; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Penulis</td>
									<td class="techSpecTD2"><?= $writer; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Aktor</td>
									<td class="techSpecTD2"><?= $actors; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Tahun</td>
									<td class="techSpecTD2"><?= $year; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Tanggal rilis</td>
									<td class="techSpecTD2"><?= date('l jS F Y', strtotime($playing_date)); ?></td>
								</tr>
							</tbody>
						</table>
						<h4 style="margin-top: 20px;">Sinopsis</h4>
						<p><?= $summary; ?></p>
						<h4 style="margin-top: 20px;">Trailer</h4>
						<?= $trailer; ?>
					</div>
				</div><!-- row -->
				<div class="row">
					<div class="span12"> <br/><hr/>
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade active in" id="home">
								
								<?php // if movie's status is now playing or old
								if ($status == 1 || $status == 2){
									// check if user logged in has left a review in the movie
									$reviewAda = FALSE;
									for($i=0; $i<sizeof($reviews); $i++) {
										if ($this->input->cookie('abcmovies') == $reviews[$i]['email']){
											$reviewAda = TRUE;
											// if user has left a review, show update button
											echo form_open('film/detail');
							   					echo form_hidden('id', $reviews[$i]['id']);
							   					echo form_hidden('film_id', $id);
							   					echo form_submit('update','Update Review','class="btn btn-primary pull-right"');
							   				echo form_close();
											break;
										}
									}
									// if user hasn't left a review but has logged in, show insert review button
									if (!$reviewAda && $this->input->cookie('abcmovies')){
										echo form_open('film/detail');
							   				echo form_hidden('id', $id);
							   				echo form_submit('insert','Insert Review','class="btn btn-primary pull-right"');
							   			echo form_close();
									}
								}
								?>
								
								<h4>Review Film</h4><br/>
								
								<!-- Review film =========================================================== -->
								<?php 
								//print_r($reviews);
								for($i=0; $i<sizeof($reviews); $i++) {
							    	echo '
							    	<div class="row">
										<div class="span2">
											<img src="'.base_url($reviews[$i]['picture']).'" width="100px;" height="100px;"/>
										</div>
										<div class="span10" style="margin-left: -50px;">
											<strong>'.$reviews[$i]['name'].'</strong>
											<small class="pull-right">Rating: <strong>'.$reviews[$i]['rating'].'/10</strong></small> 
											<p>'.$reviews[$i]['review'].'</p>
										</div>
									</div>
									<hr/>';
								} 
								if ($reviews == NULL) echo '<p>Belum ada review untuk film ini</p>';
								?>
							</div>
							
						</div>
						<!-- End of tab content =========================================================== -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- MainBody End ============================= -->