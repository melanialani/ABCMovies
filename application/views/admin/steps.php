<div id="mainBody">
 <div class="container">
  <div class="row">
   <div class="span12">
   		<h4><?= $title; ?></h4>
        <div class="tooltip-demo">
        	<?= form_open('admin/report'); ?>
	        <?= form_submit('true_pos','True Positive','class="btn btn-default"'); ?>
	        <?= form_submit('true_neg','True Negative','class="btn btn-default"'); ?>
	        <?= form_submit('false_pos','False Positive','class="btn btn-default"'); ?>
	        <?= form_submit('false_neg','False Negative','class="btn btn-default"'); ?>
	        |
	        <?= form_submit('true_review','True Review','class="btn btn-default"'); ?>
	        <?= form_submit('true_non','True Non-review','class="btn btn-default"'); ?>
	        <?= form_submit('false_review','False Review','class="btn btn-default"'); ?>
	        <?= form_submit('false_non','False Non-review','class="btn btn-default"'); ?>
	        |
	        <?= form_submit('unchecked','Unchecked','class="btn btn-default"'); ?>
	        <?= form_submit('dataset','Dataset','class="btn btn-default"'); ?>
	        <?= form_close(); ?>
        </div>
        <br /><br />
        <table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Judul Film</th>
		            <th>ID Twitter</th>
		            <th>Detail</th>
		            <th>Tambahan</th>
		            <th>Aksi</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php
		   		if (sizeof($tweets) > 1){
					for($i=0; $i<sizeof($tweets); $i++) {
				    	echo "<tr>";
					    echo "<td>" . $tweets[$i]['title'] . "</td>";
					    echo "<td>" . $tweets[$i]['ori_id'] . "</td>";
					    echo "<td><b>1. " . $tweets[$i]['text'] . "</b><br/>";
					        echo "2. " . $tweets[$i]['regex'] . "<br/>";
					        echo "3. " . $tweets[$i]['singkatan_text'] . "<br/>";
					        echo "<b>4. " . $tweets[$i]['final_text'] . "</b><br/>";
					        
					        if ($tweets[$i]['is_review'] == 1){
								echo "<b>Review: Yes</b> / <strike>No</strike>" . "<br/>";
								if ($tweets[$i]['is_positive'] == 1) echo "<b>Sentiment: Positive</b> / <strike>Negative</strike>";
					        	else if ($tweets[$i]['is_positive'] == 0) echo "<b>Sentiment:</b> <strike>Positive</strike> / <b>Negative</b>";
							} else if ($tweets[$i]['is_review'] == 0) echo "<b>Review:</b> <strike>Yes</strike> / <b>No</b>";
						echo "</td>";
						echo "<td>1. Original text<br/>2. Feature Reduction<br/>";
					        echo '3. Normalization: '.$tweets[$i]['singkatan_intersect'] . "<br/>";
					        echo '4. Rule-Based: '.$tweets[$i]['lexicon'].' (Score: '.$tweets[$i]['score'].')';
					    echo "</td>";
						echo "<td>";
					        echo form_open('admin/unchecked');
					        echo form_hidden('id', $tweets[$i]['id']);
					        echo form_hidden('film_id', $tweets[$i]['film_id']);
				   			?>
				   				<button type="submit" name="pos" value="pos" title="Set as positive review" class="btn btn-xs btn-success"><span class="fa fa-plus"></span></button><br/>
				   				<button type="submit" name="neg" value="neg" title="Set as negative review" class="btn btn-xs btn-warning" style="margin-top: 5px;"><span class="fa fa-minus"></span></button><br/>
				   				<button type="submit" name="delete" value="Delete" title="Mark as non-review" class="btn btn-xs btn-danger" style="margin-top: 5px;"><span class="fa fa-trash"></span></button>
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
			"order": [[ 0, "desc" ]]
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