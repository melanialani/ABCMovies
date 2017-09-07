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
		            <th>Teks</th>
		            <th>Status</th>
		            <!--th>Point</th-->
		            <th>Tanggal</th>
		            <th>Aksi</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php
		   		for($i=0; $i<sizeof($dataset); $i++) {
				    echo "<tr>";
					echo "<td>" . $dataset[$i]['text'] . "</td>";
					echo "<td>" . $dataset[$i]['status'] . "</td>";
					//echo "<td>" . $dataset[$i]['score'] . "</td>";
					echo "<td>" . $dataset[$i]['date'] . "</td>";
					echo "<td>";
					    echo form_open('admin/dataset');
					    echo form_hidden('id', $dataset[$i]['id']);
				?>
				   	<button type="submit" name="delete" value="Delete" title="Delete dataset" class="btn btn-xs btn-danger" style="margin-top: 5px;"><span class="fa fa-trash"></span></button>
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
			"order": [[ 2, "desc" ]]
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