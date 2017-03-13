
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Update Film</h1><hr/>
		<div class="span12">
			<div class="thumbnail" style="padding-left: 30px; padding-top: 30px;">
				<?php echo form_open('admin/updateFilm', "role='form'"); ?>
					<table width="100%">
						<tr>
							<td width="15%" align="right"><b style="margin-right: 20px;">ID</b></td>
							<td><input type="number" id="id" name="id" value="<?= $id; ?>" required class="form-control" readonly="true" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Title</b></td>
							<td><input type="text" id="title" name="title" value="<?= $title; ?>" required class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Summary</b></td>
							<td><textarea id="summary" name="summary" required class="form-control" rows="10" style="width:95%"><?= $summary; ?></textarea></td>
						</tr>
					</table>
					
					<fieldset style="margin-top: 20px;"><legend>Informasi Film</legend>
					<table width="100%">
						<tr>
							<td width="15%" align="right"><b style="margin-right: 20px;">Genre</b></td>
							<td><input type="text" id="genre" name="genre" value="<?= $genre; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Year</b></td>
							<td><input type="number" id="year" name="year" size="4" min="1899" value="<?= $year; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Playing date</b></td>
							<td><input type="date" id="playing_date" name="playing_date" value="<?php echo date('Y-m-d',strtotime($playing_date)); ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Length</b></td>
							<td><input type="number" id="length" name="length" value="<?= $length; ?>" class="form-control" style="width:91%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Director</b></td>
							<td><input type="text" id="director" name="director" value="<?= $director; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Writer</b></td>
							<td><input type="text" id="writer" name="writer" value="<?= $writer; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Actors</b></td>
							<td><input type="text" id="actors" name="actors" value="<?= $actors; ?>" class="form-control" style="width:95%"/></td>
						</tr>
					</table>
					</fieldset>
					
					<fieldset style="margin-top: 20px;"><legend>Media Film</legend>
					<table width="100%">
						<tr>
							<td width="15%" align="right"><b style="margin-right: 20px;">Poster</b></td>
							<td>
								<img src="<?= $poster; ?>" style="width:10%"/><br/><br/>
							</td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">OMDB Poster URL</b></td>
							<td><input type="text" id="poster" name="poster" value="<?= $poster; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Trailer</b></td>
							<td>
								<?= $trailer; ?>
							</td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">YouTube Embed Code</b></td>
							<td><input type="text" id="trailer" name="trailer" value='<?= $trailer; ?>' class="form-control" style="width:95%"/></td>
						</tr>
					</table>
					</fieldset>
					
					<fieldset style="margin-top: 20px;"><legend>Rating Film</legend>
					<table width="100%">
						<tr>
							<td width="15%" align="right"><b style="margin-right: 20px;">ID IMDB</b></td>
							<td><input type="text" id="imdb_id" name="imdb_id" value="<?= $imdb_id; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Rating IMDB</b></td>
							<td><input type="text" id="imdb_rating" name="imdb_rating" value="<?= $imdb_rating; ?>" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Metacritic score</b></td>
							<td><input type="number" id="metascore" name="metascore" value="<?= $metascore; ?>" class="form-control" style="width:95%"/></td>
						</tr>
					</table>
					</fieldset>
					
					<!--Status: <input type="number" id="status" name="status" placeholder="1" max="3" min="0" required class="form-control"/-->
					<fieldset><legend>Status Film</legend>
						<?php
						if ($status == 0) echo '<input type="radio" name="status" value="0" class="form-control" checked> Coming Soon<br>';
						else echo '<input type="radio" name="status" value="0" class="form-control"> Coming Soon<br>';
						if ($status == 1) echo '<input type="radio" name="status" value="1" class="form-control" checked> Now Playing<br>';
						else echo '<input type="radio" name="status" value="1" class="form-control"> Now Playing<br>';
						if ($status == 2) echo '<input type="radio" name="status" value="2" class="form-control" checked> Not playing anymore<br>';
						else echo '<input type="radio" name="status" value="2" class="form-control"> Not playing anymore<br>';
						if ($status == 3) echo '<input type="radio" name="status" value="3" class="form-control" checked> Unchecked Coming Soon<br>';
						else echo '<input type="radio" name="status" value="3" class="form-control"> Unchecked Coming Soon<br>';
						if ($status == 4) echo '<input type="radio" name="status" value="3" class="form-control" checked> Unchecked Now Playing';
						else echo '<input type="radio" name="status" value="3" class="form-control"> Unchecked Now Playing';
						?>
					</fieldset>
					
					<hr/>
	                
	                <?php echo form_submit(['id'=>'save','name'=>'save','value'=>'Save','class'=>'btn btn-primary']); ?>
				<?php echo form_close(); ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
</div><!-- /.container -->
</div><!-- /.mainBody -->
	