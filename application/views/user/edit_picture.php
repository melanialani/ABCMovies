
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Profile</h1><hr/>
		<div class="span3"></div>
		<div class="span6" align="center">
			<div class="thumbnail">
				<?php echo form_open_multipart('user/updateProfilePicture', "role='form'"); ?>
					<fieldset style="margin-top: 20px;"> 
						<div class="form-group">
							<table cellpadding="10px">
								<tr>
									<td colspan="2" align="center"><img src="<?= base_url($picture); ?>" width="100px;" height="100px;"/></td>
								</tr>
								<tr>
									<td><b>Email</b></td>
									<td><input type="email" id="email" name="email" value="<?= $this->input->cookie('abcmovies'); ?>" required readonly="true" class="form-control"/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><input type="file" name="profilePicture" required class="form-control"/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><?php echo form_submit(['id'=>'save','name'=>'save','value'=>'Simpan','class'=>'btn btn-primary']); ?></td>
								</tr>
							</table>
		                </div>
		            </fieldset> 
				<?php echo form_close(); ?>
				<?php echo $message; ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
</div><!-- /.container -->
</div><!-- /.mainBody -->
	