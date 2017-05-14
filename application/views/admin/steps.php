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
        <!--table class="table table-striped table-bordered display">
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
		   		<td>Berapa persen dari prediksi positif yang benar(precision)</td>
		   		<td><?= round($precision_pos,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi negatif yang benar(precision)</td>
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
		   		<td>Berapa persen dari prediksi non-review yang benar(recall)</td>
		   		<td><?= round($review_recall_neg,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari prediksi review yang benar(precision)</td>
		   		<td><?= round($review_precision_pos,2).'%'; ?></td>
		   	</tr>
		   	<tr>
		   		<td>Berapa persen dari data non-review yang benar non-review (precision)</td>
		   		<td><?= round($review_precision_neg,2).'%'; ?></td>
		   	</tr>
		</table>
        <br /><br /-->
        <table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Title</th>
		            <th>ID Twitter</th>
		            <th>Langkah-langkah</th>
		            <th>Keterangan</th>
		            <th>Yes Review</th>
		            <th>Yes Positive</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php
		   			for($i=0; $i<sizeof($tweets); $i++) {
				    	echo "<tr>";
					        echo "<td rowspan='7'>" . $tweets[$i]['title'] . "</td>";
					        echo "<td rowspan='7'>" . $tweets[$i]['ori_id'] . "</td>";
					        echo "<td>1. " . $tweets[$i]['text'] . "</td>";
					        echo "<td></td>";
					        echo "<td rowspan='7'>" . $tweets[$i]['yes_review'] . "</td>";
					        echo "<td rowspan='7'>" . $tweets[$i]['yes_positive'] . "</td>";
					    echo "</tr>";
					    echo "<tr>";
					        echo "<td>2. " . $tweets[$i]['alay_text'] . "</td>";
					        echo "<td>" . $tweets[$i]['alay_intersect'] . "</td>";
					    echo "</tr>";
					    echo "<tr>";
					        echo "<td>3. " . $tweets[$i]['stop_text'] . "</td>";
					        echo "<td>" . $tweets[$i]['stop_intersect'] . "</td>";
					    echo "</tr>";
					    echo "<tr>";
					        echo "<td>4. " . $tweets[$i]['regex'] . "</td>";
					        echo "<td></td>";
					    echo "</tr>";
					    echo "<tr>";
					    	echo "<td>5. " . $tweets[$i]['final_text'] . "</td>";
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
				   				<span>Review/Non-review:</span>
				   				<button type="submit" name="review1" value="negate1" title="Merupakan review" class="btn btn-xs btn-info"><span class="fa fa-plus"></span></button>
				   				<button type="submit" name="review0" value="negate2" title="Bukan review" class="btn btn-xs btn-warning"><span class="fa fa-minus"></span></button>
				   				<span style='margin-left:50px;'>Positive/Negative:</span>
				   				<button type="submit" name="positive1" value="negate3" title="Review Positive" class="btn btn-xs btn-success"><span class="fa fa-plus"></span></button>
				   				<button type="submit" name="positive0" value="Delete" title="Review Negative" class="btn btn-xs btn-danger"><span class="fa fa-minus"></span></button>
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
	<!-- Footer ================================================================== -->
	<div  id="footerSection">
	<div class="container" style="margin-bottom: -2%;">
		<p class="pull-right" style="color: white;">&copy; ABCMovies</p>
	</div><!-- Container End -->
	</div>

</body>
</html>