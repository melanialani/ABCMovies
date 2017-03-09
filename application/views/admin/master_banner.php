<div id="mainBody">
 <div class="container">
 	
 	<div class="row">
 		<div class="span3"></div>
		<div class="span6">
			<div class="thumbnail">
				<h4 align="center" style="margin-left: 10px;">Insert banner baru</h4>
				<?php echo form_open_multipart('film/masterBanner', "role='form'"); ?>
					<div class="form-group">
						<table width="100%" cellpadding="5px">
							<tr>
								<td align="right" width="25%" style="margin-right: 15px;"><b>Nama banner</b></td>
								<td><input type="text" id="banner_name" name="banner_name" required class="form-control" style="width: 80%;"/></td>
							</tr>
							<tr>
								<td align="right" width="25%" style="margin-right: 15px;"><b>Status</b></td>
								<td><fieldset>
									<input type="radio" name="banner_status" value="0" class="form-control" checked style="margin-left: 10px;"> Not active
								    <input type="radio" name="banner_status" value="1" class="form-control" style="margin-left: 50px;"> Active
								</fieldset></td>
							</tr>
							<tr>
								<td align="right" width="25%" style="margin-right: 15px;"><b>File</b></td>
								<td><input type="file" name="picture" required class="form-control" style="margin-left: 5px;"/></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><p>Lebar dari gambar harus lebih besar dari 480 pixel</p></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><?php echo form_submit(['id'=>'insert','name'=>'insert','value'=>'Insert banner baru','class'=>'btn btn-primary']); ?></td>
							</tr>
						</table>
		            </div>
				<?php echo form_close(); ?>
				<?php echo $message; ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
	
	<hr/>
 	
  <div class="row">
   <div class="span12">
		<table id="datatable" class="table table-striped table-bordered display">
		    <thead>
		        <tr>
		            <th>Name</th>
		            <th>Picture</th>
		            <th>Status</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		   	<tbody>
		   		<?php 
		   			for($i=0; $i<sizeof($banners); $i++) {
				    	echo "<tr>";
					        echo "<td>" . $banners[$i]['name'] . "</td>";
					        echo "<td><img src='".base_url($banners[$i]['picture'])."' width='150px' height='100px'/><br/></td>";
					        echo "<td>";
					        	if ($banners[$i]['status'] == 1) echo "active";
					        	else echo "deactivated";
					        echo "</td>";
					        echo "<td>";
				   				echo form_open('film/masterBanner');
				   					echo form_hidden('id', $banners[$i]['id']);
				   					echo form_submit('deactivate','Deactivate','class="btn btn-primary"');
				   					echo form_submit('activate','Activate','class="btn btn-primary"');
				   					echo form_submit('delete','Delete','class="btn btn-primary"');
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
  	