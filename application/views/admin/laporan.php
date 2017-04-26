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
	        
	        <!--h4>Ground Truth</h4>
	        <?= form_submit('all','All Tweet','class="btn btn-default"'); ?>
	        <?= form_submit('unchecked','Unchecked Tweet','class="btn btn-default"'); ?>
	        <?= form_submit('groundTruth','All Ground Truth','class="btn btn-default"'); ?>
	        <?= form_submit('nonreview','Non-review data','class="btn btn-default"'); ?>
	        <?= form_submit('review','Review Ground Truth','class="btn btn-default"'); ?>
	        <?= form_submit('positive','Positive Review Ground Truth','class="btn btn-default"'); ?>
	        <?= form_submit('negative','Negative Review Ground Truth','class="btn btn-default"'); ?> -->
	        
	        <?= form_close(); ?>
        </div>
        <br /><br />
        <table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Title</th>
		            <th>Tweet</th>
		            <th>Positive/ Negative</th>
		            <th>Yes Review</th>
		            <th>Yes Positive</th>
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
					        	echo '<span style="font-size: 0px;">'.$tweets[$i]['status'].'</span>';
					        	if ($tweets[$i]['status'] == 2) echo '<div title="Bukan review" class="btn btn-xs btn-warning" style="margin-left:10px;"><span class="fa fa-minus"></span></div>';
					        	else echo '<div title="Review" class="btn btn-xs btn-success" style="margin-left:10px;"><span class="fa fa-plus"></span></div>';
					        echo "</td>";
					        echo "<td>";
					        	echo '<span style="font-size: 0px;">'.$tweets[$i]['truth_rule'].'</span>';
					        	if ($tweets[$i]['truth_rule'] == 1) echo '<div title="true" class="btn btn-xs btn-success" style="margin-left:10px;"><span class="fa fa-check"></span></div>';
					        	else if ($tweets[$i]['truth_rule'] == 0) echo '<div title="false" class="btn btn-xs btn-warning" style="margin-left:10px;"><span class="fa fa-times"></span></div>';
					        echo "</td>";
					        echo "<td>";
					        	echo '<span style="font-size: 0px;">'.$tweets[$i]['truth_naive'].'</span>';
					        	if ($tweets[$i]['truth_naive'] == 1) echo '<div title="true" class="btn btn-xs btn-success" style="margin-left:10px;"><span class="fa fa-check"></span></div>';
					        	else if ($tweets[$i]['truth_naive'] == 0) echo '<div title="false" class="btn btn-xs btn-warning" style="margin-left:10px;"><span class="fa fa-times"></span></div>';
					        echo "</td>";
					        echo "<td>";
				   				echo form_open('admin/report');
				   					echo form_hidden('film_id', $film_id);
				   					echo form_hidden('id', $tweets[$i]['id']);
				   					echo form_hidden('status', $tweets[$i]['status']);
				   					echo form_hidden('truth_rule', $tweets[$i]['truth_rule']);
				   					echo form_hidden('truth_naive', $tweets[$i]['truth_naive']);
				   					?>
				   					<button type="submit" name="negate1" value="negate1" title="Negate value of Positive/Negative Status" class="btn btn-xs btn-default"><span class="fa fa-edit"></span></button>
				   					<button type="submit" name="negate2" value="negate2" title="Negate value of Yes Review" class="btn btn-xs btn-primary"><span class="fa fa-edit"></span></button>
				   					<button type="submit" name="negate3" value="negate3" title="Negate value of Yes Positive" class="btn btn-xs btn-info"><span class="fa fa-edit"></span></button>
				   					<button type="submit" name="delete" value="Delete" title="Delete" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></button>
				   					<?php
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
			"columnDefs": [{ "width": "18%", "targets": 5 }]
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