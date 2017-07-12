<div id="mainBody">
 <div class="container">
  <div class="row">
   <div class="span12">
   		<h4><?= $title; ?></h4>
        <div class="tooltip-demo">
        	<?= form_open('admin/reportv2'); ?>
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
		    	<td colspan="2"><h5>Review positif/negatif</h5></td>
		    </tr>
		    <tr>
		   		<td>Berapa persen prediksi benar (accuracy)</td>
		   		<td><?= round($accuracy,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari data positif yang benar positif (recall)</td>
		   		<td><?= round($recall_pos,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari data negatif yang benar negatif (recall)</td>
		   		<td><?= round($recall_neg,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi positif yang benar (precision)</td>
		   		<td><?= round($precision_pos,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi negatif yang benar (precision)</td>
		   		<td><?= round($precision_neg,2).'%'; ?></td>
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
		   		<td><?= round($review_recall_pos,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi non-review yang benar (recall)</td>
		   		<td><?= round($review_recall_neg,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi review yang benar (precision)</td>
		   		<td><?= round($review_precision_pos,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari data non-review yang benar non-review (precision)</td>
		   		<td><?= round($review_precision_neg,2).'%'; ?></td>
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
		$('#datatable').DataTable( {} );
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