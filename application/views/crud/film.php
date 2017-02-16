<div id="content" style="width: 82%; position: absolute; padding: 20px; background-color: #d1fafe;">

<?php
	echo "<h2> Master Film </h2>" . "<br/>";
		
	echo form_open_multipart('welcome/masterFilm');
	?>
		ID: <input type="number" id="id" name="id" placeholder="0"/><br/>
		Title: <input type="text" id="title" name="title" placeholder="Beauty and the Beast"/><br/>
		Summary: <input type="text" id="summary" name="summary" placeholder="Summary of the movie"/><br/>
		Genre: <input type="text" id="genre" name="genre" placeholder="Drama, Comedy"/><br/>
		Year: <input type="number" id="year" name="year" size="4" min="1899" placeholder="2017"/><br/>
		Playing date: <input type="date" id="playing_date" name="playing_date" placeholder="03-30-2017"/><br/>
		Length: <input type="number" id="length" name="length" placeholder="128"/> minutes<br/>
		Director: <input type="text" id="director" name="director" placeholder="Jackie Chan"/><br/>
		Writer: <input type="text" id="writer" name="writer" placeholder="Niel Beige"/><br/>
		Actors: <input type="text" id="actors" name="actors" placeholder="Keanu Reeves, Gal Gadot"/><br/>
		Poster: <input type="text" id="poster" name="poster" placeholder="poster.jpg"/><br/>
		Trailer: <input type="text" id="trailer" name="trailer" placeholder="youtube link"/><br/>
		ID IMDB: <input type="text" id="imdb_id" name="imdb_id" placeholder="256kkkyya"/><br/>
		Rating IMDB: <input type="text" id="imdb_rating" name="imdb_rating" placeholder="6.8"/><br/>
		Metacritic score: <input type="number" id="metascore" name="metascore" placeholder="6.2"/><br/>
		Positif review from Twitter: <input type="number" id="twitter_positif" name="twitter_positif" placeholder="159"/><br/>
		Negatif review from Twitter: <input type="number" id="twitter_negatif" name="twitter_negatif" placeholder="34"/><br/>
		Rating ABCMovies: <input type="text" id="rating" name="rating" placeholder="7.5"/><br/>
		Status: <input type="number" id="status" name="status" placeholder="1" max="3" min="0"/><br/><br/>
	<?php
		echo form_submit('insert', 'Insert') . "&nbsp &nbsp &nbsp";
		echo form_submit('update', 'Update') . "&nbsp &nbsp &nbsp";
		echo form_submit('delete', 'Delete');
	echo form_close();
	
	echo "<br/> <b>Status:</b> " . $conf . "<br/><br/><br/>";
	
	//print_r($users);
	echo "<table cellpadding='10px' border='1' align='center'>
			<tr>
				<td><b>ID</b></td>
				<td><b>Title</b></td>
				<td><b>Summary</b></td>
				<td><b>Genre</b></td>
				<td><b>Year</b></td>
				<td><b>Playing date</b></td>
				<td><b>Length</b></td>
				<td><b>Director</b></td>
				<td><b>Writer</b></td>
				<td><b>Actors</b></td>
				<td><b>Poster</b></td>
				<td><b>Trailer</b></td>
				<td><b>ID IMDB</b></td>
				<td><b>Rating IMDB</b></td>
				<td><b>Metacritic score</b></td>
				<td><b>Positif review from Twitter</b></td>
				<td><b>Negatif review from Twitter</b></td>
				<td><b>Rating ABCMovies</b></td>
				<td><b>Status</b></td>
			</tr>";
    for($i=0; $i<sizeof($movies); $i++) {
    	echo "<tr>";
        echo "<td>" . $movies[$i]['id'] . "</td>";
        echo "<td>" . $movies[$i]['title'] . "</td>";
        echo "<td>" . $movies[$i]['summary'] . "</td>";
        echo "<td>" . $movies[$i]['genre'] . "</td>";
        echo "<td>" . $movies[$i]['year'] . "</td>";
        echo "<td>" . date("Y-m-d", strtotime($movies[$i]['playing_date'])) . "</td>";
        echo "<td>" . $movies[$i]['length'] . "minutes</td>";
        echo "<td>" . $movies[$i]['director'] . "</td>";
        echo "<td>" . $movies[$i]['writer'] . "</td>";
        echo "<td>" . $movies[$i]['actors'] . "</td>";
        echo "<td>" . $movies[$i]['poster'] . "</td>";
        echo "<td>" . $movies[$i]['trailer'] . "</td>";
        echo "<td>" . $movies[$i]['imdb_id'] . "</td>";
        echo "<td>" . $movies[$i]['imdb_rating'] . "</td>";
        echo "<td>" . $movies[$i]['metascore'] . "</td>";
        echo "<td>" . $movies[$i]['twitter_positif'] . "</td>";
        echo "<td>" . $movies[$i]['twitter_negatif'] . "</td>";
        echo "<td>" . $movies[$i]['rating'] . "</td>";
        echo "<td>" . $movies[$i]['status'] . "</td>";
        echo "</tr>";
        
        //echo "<td><img style='width:100px;height:100px;'src='" . $b_url.$users[$i]['picture'] . "'/></td>";
    }
    echo "</table>";
?>

</div>