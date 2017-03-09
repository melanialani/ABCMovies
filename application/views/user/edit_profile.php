
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Update Profile</h1><hr/>
		<div class="span3"></div>
		<div class="span6" align="center">
			<div class="thumbnail">
				<?php echo form_open('user/updateProfile', "role='form'"); ?>
					<fieldset style="margin-top: 20px;"> 
						<div class="form-group">
							<table cellpadding="10px">
								<tr>
									<td><b>Email</b></td>
									<td><input type="email" id="email" name="email" value="<?= $this->input->cookie('abcmovies'); ?>" required readonly="true" class="form-control"/></td>
								</tr>
								<tr>
									<td><b>Nama lengkap</b></td>
									<td><input type="text" id="name" name="name" value="<?= $name; ?>" class="form-control"/></td>
								</tr>
								<tr>
									<td><b>Tanggal lahir</b></td>
									<td><input type="date" value="<?php echo date('Y-m-d',strtotime($birthdate)); ?>" id="birthdate" name="birthdate" class="form-control"/> mm/dd/yyyy</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><?php echo form_submit(['id'=>'save','name'=>'save','value'=>'Simpan','class'=>'btn btn-primary']); ?></td>
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
	