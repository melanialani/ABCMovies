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
		    	<td colspan="2"><h3 align="center">TEST STRING</h3></td>
		    </tr>
		    <tr>
		    	<?= form_open('admin/report'); ?>
		   		<td colspan="2">
		   			<input type="text" id="input" name="input" placeholder="wow filmnya bagus banget gan gue kagum" required class="form-control" style="width:92%; margin-top: 1%;"/>
		   			<?= form_submit('test','Test!','class="btn btn-info"'); ?></td>
		   		<?= form_close(); ?>
		   	</tr>
		   	<?php 
		   	if ($result != NULL){
		   		echo '<tr><td><p style="text-align: right; font-weight: bold;">Input</p></td><td>'.$result['input'].'</td></tr>';
		   		echo '<tr><td><p style="text-align: right; font-weight: bold;">Feature reduction</p></td><td>'.$result['regex'].'</td></tr>';
		   		//echo '<tr><td><p style="text-align: right; font-weight: bold;">Mapping</p></td><td>'.$result['replaced'].'</td></tr>';
		   		//echo '<tr><td><p style="text-align: right; font-weight: bold;">Rule-based Lexicon</p></td><td>'.$result['lexicon'].'</td></tr>';
		   		//echo '<tr><td><p style="text-align: right; font-weight: bold;">Rule-based Common words</p></td><td>'.$result['common'].'</td></tr>';
		   		echo '<tr><td><p style="text-align: right; font-weight: bold;">Final text</p></td><td>'.$result['text'].'</td></tr>';
		   		
		   		echo '<tr><td width="35%"><p style="text-align: right; font-weight: bold;">Hasil test</p></td>';
		   		echo '<td><b style="margin-left:5%;">Is Review : </b>';
			        if ($result['is_review'] == 1) echo '<span title="Review" class="fa fa-check"></span>';
			        else if ($result['is_review'] == 0) echo '<span title="Bukan review" class="fa fa-minus"></span>';

				    echo "<span style='margin-left:30%;'><b>Is Positive : </b></span> ";
				    if ($result['is_positive'] == 1) echo '<span title="Positive review" class="fa fa-check"></span>';
				    else if ($result['is_positive'] == 0) echo '<span title="Negative review" class="fa fa-minus"></span>';
				echo '</td></tr>';
				
				//echo '<tr><td>Details</td><td>Score: '.$result['score'].' | Pos: '.round($result['persen_pos'],2).'% | Neg: '.round($result['persen_neg'],2).'%</td><tr>';
			}
		   	?>
		</table>
        <table class="table table-striped table-bordered display">
		    <tr>
		    	<td colspan="2"><h4 align="center">POSITIVE / NEGATIVE</h4></td>
		    	<td colspan="2"><h4 align="center">REVIEW / NON-REVIEW</h4></td>
		    </tr>
		    <tr>
		   		<td><p style="text-align: right;">Accuracy</p></td>
		   		<td width="25%"><?= round($accuracy,2).'%'; ?></td>
		   		<td><p style="text-align: right;">Accuracy</p></td>
		   		<td width="25%"><?= round($review_accuracy,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td><p style="text-align: right;">Recall</p></td>
		   		<td><?= round($recall,2).'%'; ?></p></td>
		   		<td><p style="text-align: right;">Recall</p></td>
		   		<td><?= round($review_recall,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td><p style="text-align: right;">Precision</p></td>
		   		<td><?= round($precision,2).'%'; ?></td>
		   		<td><p style="text-align: right;">Precision</p></td>
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