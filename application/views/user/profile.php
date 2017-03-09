
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Profile</h1><hr/>
		<div class="span3"></div>
		<div class="span6" align="center">
			<div class="thumbnail">
				<?php echo form_open('user/profile', "role='form'"); ?>
					<fieldset style="margin-top: 20px;"> 
						<div class="form-group">
							<table cellpadding="10px">
								<tr>
									<td colspan="2" align="center"><img src="<?= base_url($picture); ?>" width="100px;" height="100px;"/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><?php echo form_submit(['id'=>'update_picture','name'=>'update_picture','value'=>'Update Profile Picture','class'=>'btn btn-primary']); ?></td>
								</tr>
								<tr>
									<td><b>Email</b></td>
									<td><?= $this->input->cookie('abcmovies'); ?></td>
								</tr>
								<tr>
									<td><b>Nama lengkap</b></td>
									<td><?= $name; ?></td>
								</tr>
								<tr>
									<td><b>Tanggal lahir</b></td>
									<td><?= date('l jS F Y', strtotime($birthdate)); ?></td>
								</tr>
								<tr>
									<td align="center"><?php echo form_submit(['id'=>'update','name'=>'update','value'=>'Update Profile','class'=>'btn btn-primary']); ?></td>
									<td align="center"><?php echo form_submit(['id'=>'update_password','name'=>'update_password','value'=>'Update Password','class'=>'btn btn-primary']); ?></td>
								</tr>
							</table>
		                </div>
		            </fieldset> 
				<?php echo form_close(); ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
</div><!-- /.container -->
</div><!-- /.mainBody -->
	