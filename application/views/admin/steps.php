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
	        
	        <?= form_submit('true_review','True Review','class="btn btn-default"'); ?>
	        <?= form_submit('true_non','True Non-review','class="btn btn-default"'); ?>
	        <?= form_submit('false_review','False Review','class="btn btn-default"'); ?>
	        <?= form_submit('false_non','False Non-review','class="btn btn-default"'); ?>
	        
	        <?= form_submit('unchecked','Unchecked Steps','class="btn btn-default"'); ?>
	        <?= form_close(); ?>
        </div>
        <br /><br />
        <table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Title</th>
		            <th>ID Twitter</th>
		            <th>Langkah-langkah</th>
		            <th>Keterangan</th>
		            <!--th>Yes Review</th>
		            <th>Yes Positive</th-->
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php
		   		if (sizeof($tweets) > 1){
					for($i=0; $i<sizeof($tweets); $i++) {
				    	echo "<tr>";
					        echo "<td rowspan='6'>" . $tweets[$i]['title'] . "</td>";
					        echo "<td rowspan='6'>" . $tweets[$i]['ori_id'] . "</td>";
					        echo "<td>1. " . $tweets[$i]['text'] . "</td>";
					        echo "<td></td>";
					        //echo "<td rowspan='6'>" . $tweets[$i]['yes_review'] . "</td>";
					        //echo "<td rowspan='6'>" . $tweets[$i]['yes_positive'] . "</td>";
					    echo "</tr>";
					    echo "<tr>";
					        echo "<td>2. " . $tweets[$i]['regex'] . "</td>";
					        echo "<td></td>";
					    echo "</tr>";
					    /*echo "<tr>";
					        echo "<td>3. " . $tweets[$i]['stop_text'] . "</td>";
					        echo "<td>" . $tweets[$i]['stop_intersect'] . "</td>";
					    echo "</tr>";*/
					    echo "<tr>";
					        echo "<td>3. " . $tweets[$i]['singkatan_text'] . "</td>";
					        echo "<td>" . $tweets[$i]['singkatan_intersect'] . "</td>";
					    echo "</tr>";
					    echo "<tr>";
					    	echo "<td>4. " . $tweets[$i]['final_text'] . "</td>";
					        echo "<td>" . $tweets[$i]['lexicon'] . "</td>";
					    echo "</tr>";
					    echo "<tr>";
					        echo "<td colspan='2'><b>Review: </b>";
					        	if ($tweets[$i]['is_review'] == 1) echo '<span title="Bukan review" class="fa fa-check"></span>';
					        	else if ($tweets[$i]['is_review'] == 0) echo '<span title="Review" class="fa fa-minus"></span>';
					        	echo " <span style='margin-left:50px;'><b>Positive: </b></span> ";
					        	if ($tweets[$i]['is_positive'] == 1) echo '<span title="Positive review" class="fa fa-check"></span>';
					        	else if ($tweets[$i]['is_positive'] == 0) echo '<span title="Negative review" class="fa fa-minus"></span>';
					       	echo "</td>";
					    echo "</tr>"; 
					    echo "<tr>"; 
					    	echo "<td colspan='2'>";
					        echo form_open('admin/unchecked');
					        echo form_hidden('id', $tweets[$i]['id']);
				   			?>
				   				<span>Action:</span>
				   				<button type="submit" name="pos" value="pos" title="Set as positive review" class="btn btn-xs btn-success"><span class="fa fa-plus"></span></button>
				   				<button type="submit" name="neg" value="neg" title="Set as negative review" class="btn btn-xs btn-warning"><span class="fa fa-minus"></span></button>
				   				<button type="submit" name="delete" value="Delete" title="Mark as non-review" class="btn btn-xs btn-danger"><span class="fa fa-trash"></span></button>
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
	<!-- Footer ================================================================== -->
	<div  id="footerSection">
	<div class="container" style="margin-bottom: -2%;">
		<p class="pull-right" style="color: white;">&copy; ABCMovies</p>
	</div><!-- Container End -->
	</div>

</body>
</html>