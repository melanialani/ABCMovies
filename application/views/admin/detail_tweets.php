<div id="mainBody">
 <div class="container">
  <div class="row">
   <div class="span12">
   				<div class="row">
					<div id="gallery" class="span3">
						<img src="<?= $movie[0]['poster']; ?>" style="width:100%"/>
					</div>
					<div class="span9">
						<h3><?= $movie[0]['title']; ?></h3>
						<hr class="soft clr"/>
						<h4>Informasi Film</h4>
						<table class="table table-bordered">
							<tbody>
								<tr class="techSpecRow">
									<td class="techSpecTD1" style="width: 100px;">Sutradara</td>
									<td class="techSpecTD2"><?= $movie[0]['director']; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Penulis</td>
									<td class="techSpecTD2"><?= $movie[0]['writer']; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Aktor</td>
									<td class="techSpecTD2"><?= $movie[0]['actors']; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Tahun</td>
									<td class="techSpecTD2"><?= $movie[0]['year']; ?></td>
								</tr>
								<tr class="techSpecRow">
									<td class="techSpecTD1">Tanggal rilis</td>
									<td class="techSpecTD2"><?= date('l jS F Y', strtotime($movie[0]['playing_date'])); ?></td>
								</tr>
							</tbody>
						</table>
						<h4 style="margin-top: 20px;">Sinopsis</h4>
						<p><?= $movie[0]['summary']; ?></p>
					</div>
				</div><!-- row -->
   		<hr>
   		<!--?php 
	   		echo form_open('webSystem/calculateTweets');
		   		echo form_hidden('film_id', $film_id);
		   		echo form_submit('calculate','Calculate Tweets','class="btn btn-primary btn-lg btn-block"');
	   		echo form_close(); 
   		?-->
   		<h4 style="text-align:center;" ><a href="<?= site_url('webSystem/getTweets/'.$film_id); ?>" class="btn btn-primary btn-lg btn-block">Get more Tweets</a></h4>
		
		<br /><br />
   
   		<table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Confirmed</th>
		            <th>Tweet</th>
		            <th>Sentiment</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php 
		   		if (sizeof($tweets) > 1){
					for($i=0; $i<sizeof($tweets); $i++) {
				    	echo "<tr>";
				    		echo "<td>";
					        	echo '<span style="font-size: 0px;">'.$tweets[$i]['confirmed'].'</span>';
					        	if ($tweets[$i]['confirmed'] == 1) echo '<span title="Already checked" class="fa fa-check" style="margin-left:45%;"></span>';
					        echo "</td>";
				    		echo "<td>" . $tweets[$i]['text'] . "</td>";
					        echo "<td>";
					        	echo '<span style="font-size: 0px;">'.$tweets[$i]['yes_positive'].'</span>';
					        	if ($tweets[$i]['yes_positive'] == 1) echo '<div title="positive" class="btn btn-xs btn-success" style="margin-left:10px;"><span class="fa fa-plus"></span></div>';
					        	else if ($tweets[$i]['yes_positive'] == 0) echo '<div title="negative" class="btn btn-xs btn-warning" style="margin-left:10px;"><span class="fa fa-minus"></span></div>';
					        echo "</td>";
					        echo "<td>";
				   				echo form_open('admin/detailTweets');
				   					echo form_hidden('id', $tweets[$i]['id']);
				   					echo form_hidden('ori_id', $tweets[$i]['ori_id']);
				   					echo form_hidden('yes_positive', $tweets[$i]['yes_positive']);
				   					?>
				   					<button type="submit" name="pos" value="Positive" title="Mark as positive review" class="btn btn-xs btn-success"><span class="fa fa-plus"></span></button>
				   					<button type="submit" name="neg" value="Negative" title="Mark as positive review" class="btn btn-xs btn-warning"><span class="fa fa-minus"></span></button>
				   					<button type="submit" name="delete" value="Delete" title="Delete" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></button>
				   					<?php
				   				echo form_close();
			   				echo "</td>";
				        echo "</tr>";
				    }
				}
		   		?>
		   	</tbody>
		</table>
	</div>	<!--/.main-->
	
  </div>
 </div>
</div>

	<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		$('#datatable').DataTable( { 
			"columnDefs": [{ "width": "12%", "targets": 3 }]
		} );
	} );
	</script>

	<!-- Footer ================================================================== -->
	<div  id="footerSection">
	<div class="container" style="margin-bottom: -2%;">
		<p class="pull-right" style="color: white;">&copy; ABCMovies</p>
	</div><!-- Container End -->
	</div>

</body>
</html>