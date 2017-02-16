<div id="content" style="width: 82%; position: absolute; padding: 20px; background-color: #d1fafe;">

<?php
	echo "<h2> Master Review </h2>" . "<br/>";
	
	// set data array untuk drop down
	$id = NULL; $film_id = NULL; $username = NULL;
	for($i=0; $i<sizeof($reviews); $i++) {
    	$id[$reviews[$i]['id']] = $reviews[$i]['id'].' - '.$reviews[$i]['username'];
    }
	for($i=0; $i<sizeof($movies); $i++) {
    	$film_id[$movies[$i]['id']] = $movies[$i]['id'].' - '.$movies[$i]['title'];
    }
    for($i=0; $i<sizeof($users); $i++) {
    	$username[$users[$i]['username']] = $users[$i]['username'].' - '.$users[$i]['name'];
    }
		
	echo form_open_multipart('welcome/masterReview');
	?>
		ID: <?= form_dropdown('id', $id); ?><br/>
		Film: <?= form_dropdown('film_id', $film_id); ?><br/>
		Username: <?= form_dropdown('username', $username); ?><br/>
		Rating: <input type="number" id="rating" name="rating" placeholder="8" min="1" max="10"/>/10<br/>
		Review: <input type="text" id="review" name="review" placeholder="Bagus juga kok"/><br/><br/>
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
				<td><b>ID Film</b></td>
				<td><b>Username</b></td>
				<td><b>Rating</b></td>
				<td><b>Review</b></td>
				<td><b>Tanggal</b></td>
			</tr>";
    for($i=0; $i<sizeof($reviews); $i++) {
    	echo "<tr>";
        echo "<td>" . $reviews[$i]['id'] . "</td>";
        echo "<td>" . $reviews[$i]['film_id'] . "</td>";
        echo "<td>" . $reviews[$i]['username'] . "</td>";
        echo "<td>" . $reviews[$i]['rating'] . "/10 </td>";
        echo "<td>" . $reviews[$i]['review'] . "</td>";
        echo "<td>" . date("Y-m-d", strtotime($reviews[$i]['tanggal'])) . "</td>";
        //echo "<td><img style='width:100px;height:100px;'src='" . $b_url.$users[$i]['picture'] . "'/></td>";
        echo "</tr>";
    }
    echo "</table>";
?>

</div>