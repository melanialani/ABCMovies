
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Edit Password</h1><hr/>
		<div class="span3"></div>
		<div class="span6" align="center">
			<div class="thumbnail">
				<?php echo form_open('user/updateProfilePassword', "role='form'"); ?>
					<fieldset style="margin-top: 20px;"> 
						<div class="form-group">
							<table cellpadding="10px">
								<tr>
									<td><b>Email</b></td>
									<td><input type="email" id="email" name="email" value="<?= $this->input->cookie('abcmovies'); ?>" required readonly="true" class="form-control"/></td>
								</tr>
								<tr>
									<td><b>Password</b></td>
									<td><input type="password" id="password" name="password" placeholder="password *" required class="form-control" maxlength="32"/></td>
								</tr>
								<tr>
									<td><b>Ulangi Password</b></td>
									<td><input type="password" id="repassword" name="repassword" placeholder="password *" required class="form-control" maxlength="32"/></td>
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
	