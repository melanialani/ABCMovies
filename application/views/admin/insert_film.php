
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Insert Film</h1><hr/>
		<div class="span12">
			<div class="thumbnail" style="padding-left: 30px; padding-top: 30px;">
				<?php echo form_open('admin/insertFilm', "role='form'"); ?>
					<table width="100%">
						<tr>
							<td width="12%" align="right"><b style="margin-right: 20px;">Title</b></td>
							<td><input type="text" id="title" name="title" placeholder="Beauty and the Beast" required class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Summary</b></td>
							<td><textarea id="summary" name="summary" placeholder="Summary of the film" required class="form-control" rows="10" style="width:95%"></textarea></td>
						</tr>
					</table>
					
					<fieldset style="margin-top: 20px;"><legend>Informasi Film</legend>
					<table width="100%">
						<tr>
							<td width="12%" align="right"><b style="margin-right: 20px;">Genre</b></td>
							<td><input type="text" id="genre" name="genre" placeholder="Drama, Comedy" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Year</b></td>
							<td><input type="number" id="year" name="year" size="4" min="1899" placeholder="2017" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Playing date</b></td>
							<td><input type="date" id="playing_date" name="playing_date" placeholder="03-30-2017" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Length</b></td>
							<td><input type="number" id="length" name="length" placeholder="120 menit" class="form-control" style="width:91%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Director</b></td>
							<td><input type="text" id="director" name="director" placeholder="Jackie Chan" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Writer</b></td>
							<td><input type="text" id="writer" name="writer" placeholder="Niel Beige" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Actors</b></td>
							<td><input type="text" id="actors" name="actors" placeholder="Keanu Reeves, Gal Gadot" class="form-control" style="width:95%"/></td>
						</tr>
					</table>
					</fieldset>
					
					<fieldset style="margin-top: 20px;"><legend>Media Film</legend>
					<table width="100%">
						<tr>
							<td width="12%" align="right"><b style="margin-right: 20px;">Poster</b></td>
							<td><input type="text" id="poster" name="poster" placeholder="OMDB Poster URL" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Trailer</b></td>
							<td><input type="text" id="trailer" name="trailer" placeholder="YouTube Embed Code" class="form-control" style="width:95%"/></td>
						</tr>
					</table>
					</fieldset>
					
					<fieldset style="margin-top: 20px;"><legend>Rating Film</legend>
					<table width="100%">
						<tr>
							<td width="12%" align="right"><b style="margin-right: 20px;">ID IMDB</b></td>
							<td><input type="text" id="imdb_id" name="imdb_id" placeholder="tt4116284" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Rating IMDB</b></td>
							<td><input type="text" id="imdb_rating" name="imdb_rating" placeholder="6.8" class="form-control" style="width:95%"/></td>
						</tr>
						<tr>
							<td align="right"><b style="margin-right: 20px;">Metacritic score</b></td>
							<td><input type="number" id="metascore" name="metascore" placeholder="73" class="form-control" style="width:95%"/></td>
						</tr>
					</table>
					</fieldset>
					
					<!--Status: <input type="number" id="status" name="status" placeholder="1" max="3" min="0" required class="form-control"/-->
					<fieldset><legend>Status Film</legend>
						<input type="radio" name="status" value="0" class="form-control"> Coming Soon<br>
					    <input type="radio" name="status" value="1" class="form-control"> Now Playing<br>
					    <input type="radio" name="status" value="2" class="form-control"> Not playing anymore<br>
					    <input type="radio" name="status" value="3" class="form-control" checked> Unchecked Coming Soon<br>
					    <input type="radio" name="status" value="4" class="form-control"> Unchecked Now Playing
					</fieldset>
					
					<hr/>
	                
	                <?php echo form_submit(['id'=>'insert','name'=>'insert','value'=>'Insert','class'=>'btn btn-primary']); ?>
				<?php echo form_close(); ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
</div><!-- /.container -->
</div><!-- /.mainBody -->
	