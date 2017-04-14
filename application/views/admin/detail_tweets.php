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
   		<?php 
	   		echo form_open('webSystem/calculateTweets');
		   		echo form_hidden('film_id', $film_id);
		   		echo form_submit('calculate','Calculate Tweets','class="btn btn-primary btn-lg btn-block"');
	   		echo form_close(); 
   		?>
		
		<br /><br />
   
   		<table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Judul Film</th>
		            <th>Tweet</th>
		            <th>Status</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php 
		   			for($i=0; $i<sizeof($tweets); $i++) {
				    	echo "<tr>";
					        echo "<td>" . $tweets[$i]['title'] . "</td>";
					        echo "<td>" . $tweets[$i]['tweet'] . "</td>";
					        echo "<td>";
					        	if ($tweets[$i]['status'] == 1) echo "positif";
					        	else echo "negatif";
					        echo "</td>";
					        echo "<td>";
				   				echo form_open('admin/detailTweets');
				   					echo form_hidden('film_id', $film_id);
				   					echo form_hidden('id', $tweets[$i]['id']);
				   					echo form_hidden('status', $tweets[$i]['status']);
				   					echo form_submit('negate','Negasi Status','class="btn btn-primary"');
				   					echo form_submit('delete','Delete','class="btn btn-primary"');
				   				echo form_close();
			   				echo "</td>";
				        echo "</tr>";
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
			"order": [[ 2, "asc" ]],
			"columnDefs": [{ "width": "23%", "targets": 3 },{ "width": "7%", "targets": 2 }]
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
  	