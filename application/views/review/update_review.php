<div id="mainBody">
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="row">
					<div id="gallery" class="span3">
						<img src="http://www.21cineplex.com/data/gallery/pictures/<?= $poster; ?>_452x647.jpg" style="width:100%"/>
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
								<h4>Tinggalkan Review Film</h4><br/>
								
								<!-- Review film =========================================================== -->
								<?php echo form_open('film/updateReview', "role='form'"); ?>
								<?php echo form_hidden('film_id',$id); ?>
								<?php echo form_hidden('review_id',$review_id); ?>
								<div class="form-group">
				                	<label><strong>Rating</strong></label>
				                	<input type="number" class="form-control" value="<?= $review_rating; ?>" id="review_rating" name="review_rating" min="1" max="10" required data-validation-required-message="Masukan Rating film"> /10
				                </div>
				                <div class="form-group">
				                	<label><strong>Review</strong></label>
				                	<textarea class="form-control" id="review_review" name="review_review" required data-validation-required-message="Masukan Review film" class="form-control" rows="10" style="width:90%"><?= $review_review; ?></textarea>
				                </div>
				                <?php echo form_submit(['id'=>'save','name'=>'save','value'=>'Simpan','class'=>'btn btn-primary']); ?>
								<?php echo form_close(); ?>
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