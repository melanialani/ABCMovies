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
        <table class="table table-striped table-bordered display">
		    <tr>
		    	<td colspan="2"><h5>Test string</h5></td>
		    </tr>
		    <tr>
		    	<?= form_open('admin/report'); ?>
		   		<td><input type="text" id="input" name="input" placeholder="ini film kok agak ga jelas ya sob" value="<?= $input; ?>" required class="form-control" style="width:80%"/></td>
		   		<td><?= form_submit('test','Test!','class="btn btn-info"'); ?></td>
		   		<?= form_close(); ?>
		   	</tr>
		    <tr>
		   		<td><b>Hasil test: </b></td>
		   		<td>
		   			<?php 
		   				echo '<b>Review: </b>';
			        	if ($result_is_review == 1) echo '<span title="Review" class="fa fa-check"></span>';
			        	else if ($result_is_review == 0) echo '<span title="Bukan review" class="fa fa-minus"></span>';

			        	echo " <span style='margin-left:50px;'><b>Positive: </b></span> ";
			        	if ($result_is_positive == 1) echo '<span title="Positive review" class="fa fa-check"></span>';
			        	else if ($result_is_positive == 0) echo '<span title="Negative review" class="fa fa-minus"></span>'
		   			?>
		   		</td>
		   	</tr>
		   	<tr>
		   		<td><b>Persentase hasil test: </b></td>
		   		<td><b>Positive: </b><? $result_psrsen_pos;  ?>
		   			<span style='margin-left:50px;'><b>Negative: </b></span><? $result_psrsen_neg; ?>
		   		</td>
		   	</tr>
		</table>
        <br /><br />
        <table class="table table-striped table-bordered display">
		    <tr>
		    	<td colspan="2"><h5>Review positif/negatif</h5></td>
		    </tr>
		    <tr>
		   		<td>Accuracy</td>
		   		<td><?= round($accuracy,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari data positif yang benar positif (recall)</td>
		   		<td><?= round($recall,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi positif yang benar (precision)</td>
		   		<td><?= round($precision,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		    	<td colspan="2"><h5>Benar merupakan review/bukan</h5></td>
		    </tr>
		    <tr>
		   		<td>Berapa persen prediksi benar (accuracy)</td>
		   		<td><?= round($review_accuracy,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari data review yang benar review (recall)</td>
		   		<td><?= round($review_recall,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi review yang benar (precision)</td>
		   		<td><?= round($review_precision,2).'%'; ?></td>
		   	</tr>
		</table>
        <br /><br />
        <table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Title</th>
		            <th>Tweet</th>
		            <th><?= 'Is '.$type; ?></th>
		            <th><?= 'Yes '.$type; ?></th>
		            <th>Action</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php 
		   			for($i=0; $i<sizeof($tweets); $i++) {
				    	echo "<tr>";
					        echo "<td>" . $tweets[$i]['title'] . "</td>";
					        echo "<td>" . $tweets[$i]['text'] . "</td>";
					        echo "<td>";
					        	//echo '<span style="font-size: 0px;">'.$tweets[$i]['is_true'].'</span>';
					        	if ($tweets[$i]['is_true'] == 0) echo '<div title="Bukan '.$type.'" class="btn btn-xs btn-warning" style="margin-left:10px;"><span class="fa fa-minus"></span></div>';
					        	else echo '<div title="'.$type.'" class="btn btn-xs btn-success" style="margin-left:10px;"><span class="fa fa-plus"></span></div>';
					        echo "</td>";
					        echo "<td>";
					        	//echo '<span style="font-size: 0px;">'.$tweets[$i]['yes_true'].'</span>';
					        	if ($tweets[$i]['yes_true'] == 1) echo '<div title="true" class="btn btn-xs btn-success" style="margin-left:10px;"><span class="fa fa-check"></span></div>';
					        	else if ($tweets[$i]['yes_true'] == 0) echo '<div title="false" class="btn btn-xs btn-warning" style="margin-left:10px;"><span class="fa fa-times"></span></div>';
					        echo "</td>";
					        echo "<td>";
				   				echo form_open('admin/report');
				   					echo form_hidden('id', $tweets[$i]['id']);
				   					echo form_hidden('ori_id', $tweets[$i]['ori_id']);
				   					echo form_hidden('yes_true', $tweets[$i]['yes_true']);
				   					?>
				   					<button type="submit" name="update" value="Update" title="Negate Sentiment" class="btn btn-xs btn-info"><span class="fa fa-edit"></span></button>
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
			"columnDefs": [{ "width": "10%", "targets": 4 }]
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