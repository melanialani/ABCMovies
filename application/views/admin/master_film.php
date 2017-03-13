<div id="mainBody">
 <div class="container">
  <div class="row">
   <div class="span12">
  		<a href="<?php echo site_url('admin/insertFilm'); ?>" role="button"><span class="btn btn-info" style="width: 98%;">Insert New Film</span></a>
		
		<br /><br />
		
		<table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <!--th>ID</th-->
		            <th>Title</th>
		            <!--th>Summary</th-->
		            <th>Genre</th>
		            <th>Year</th>
		            <!--th>Playing date</th-->
		            <!--th>Length</th-->
		            <!--th>Director</th-->
		            <!--th>Writer</th-->
		            <!--th>Actors</th-->
		            <!--th>Poster</th-->
		            <!--th>Trailer</th-->
		            <!--th>ID IMDB</th-->
		            <th>IMDB</th>
		            <th>Metascore</th>
		            <!--th>Positif review from Twitter</th-->
		            <!--th>Negatif review from Twitter</th-->
		            <th>Rating</th>
		            <th>Status</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php 
		   			for($i=0; $i<sizeof($movies); $i++) {
				    	echo "<tr>";
					        //echo "<td>" . $movies[$i]['id'] . "</td>";
					        echo "<td>" . $movies[$i]['title'] . "</td>";
					        //echo "<td>" . $movies[$i]['summary'] . "</td >";
					        echo "<td>" . $movies[$i]['genre'] . "</td>";
					        echo "<td>" . $movies[$i]['year'] . "</td>";
					        //echo "<td>" . date("Y-m-d", strtotime($movies[$i]['playing_date'])) . "</td>";
					        //echo "<td>" . $movies[$i]['length'] . " </td>";
					        //echo "<td>" . $movies[$i]['director'] . "</td>";
					        //echo "<td>" . $movies[$i]['writer'] . "</td>";
					        //echo "<td>" . $movies[$i]['actors'] . "</td>";
					        //echo '<td>' . '<img src="http://www.21cineplex.com/data/gallery/pictures/'.$movies[$i]['poster'].'_100x147.jpg" style="width:100%"/><br/>' . '</td>';
					        //echo "<td>" . $movies[$i]['trailer'] . "</td>";
					        //echo "<td>" . $movies[$i]['imdb_id'] . "</td>";
					        echo "<td>" . $movies[$i]['imdb_rating'] . "</td>";
					        echo "<td>" . $movies[$i]['metascore'] . "</td>";
					        //echo "<td>" . $movies[$i]['twitter_positif'] . "</td>";
					        //echo "<td>" . $movies[$i]['twitter_negatif'] . "</td>";
					        echo "<td>" . $movies[$i]['rating'] . "</td>";
					        echo "<td>";  
				   				if ($movies[$i]['status'] == 0) echo "Coming Soon";
				   				else if ($movies[$i]['status'] == 1) echo "Now Playing";
				   				else if ($movies[$i]['status'] == 2) echo "Not playing anymore";
				   				else if ($movies[$i]['status'] == 3) echo "Unchecked Coming Soon";
				   				else if ($movies[$i]['status'] == 4) echo "Unchecked Now Playing";
				   			echo "</td>";
					        echo "<th>";
				   				echo form_open('admin/masterFilm');
				   					echo form_hidden('id', $movies[$i]['id']);
				   					echo form_submit('detail','Detail','class="btn btn-primary"');
				   					echo form_submit('update','Update','class="btn btn-primary"');
				   					echo form_submit('delete','Delete','class="btn btn-primary"');
				   				echo form_close();
			   				echo "</th>";
				        echo "</tr>";
				        
				        //echo "<td><img style='width:100px;height:100px;'src='" . $b_url.$users[$i]['picture'] . "'/></td>";
				    }
		   		?>
		   	</tbody>
		</table>
	</div>	<!--/.main-->
	
  </div>
 </div>
</div>

	<!-- Footer ================================================================== -->
	<div  id="footerSection">
	<div class="container" style="margin-bottom: -2%;">
		<p class="pull-right" style="color: white;">&copy; ABCMovies</p>
	</div><!-- Container End -->
	</div>

</body>
</html>
  	