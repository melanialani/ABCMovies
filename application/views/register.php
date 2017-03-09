
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Register</h1><hr/>
		<div class="span3"></div>
		<div class="span6" align="center">
			<div class="thumbnail">
				<?php echo form_open('user/register', "role='form'"); ?>
					<fieldset style="margin-top: 20px;"> 
						<div class="form-group">
							<label><strong>Email</strong></label>
							<input type="email" class="form-control" placeholder="email *" id="email" name="email" autofocus="" required data-validation-required-message="Masukan alamat email">
		                   	<p class="help-block text-danger"></p>
		                </div>
		                <div class="form-group">
		                	<label><strong>Password</strong></label>
		                	<input type="password" class="form-control" placeholder="password *" id="password" name="password" maxlength="32" required data-validation-required-message="Masukan password">
		                	<p class="help-block text-danger"></p>
		                </div>
		                <div class="form-group">
		                	<label><strong>Ulangi Password</strong></label>
		                	<input type="password" class="form-control" placeholder="password *" id="repassword" name="repassword" maxlength="32" required data-validation-required-message="Ulangi password">
		                	<p class="help-block text-danger"></p>
		                </div>
		                <div class="form-group">
		                	<label><strong>Nama Lengkap</strong></label>
		                	<input type="text" class="form-control" placeholder="Nama Lengkap" id="name" name="name" required data-validation-required-message="Masukan Nama Lengkap">
		                	<p class="help-block text-danger"></p>
		                </div>
		                <div class="form-group">
		                	<label><strong>Tanggal lahir</strong></label>
		                	<input type="date" class="form-control" id="birthdate" name="birthdate">
		                </div>
		                <hr/>
		                <?php echo form_submit(['id'=>'register','name'=>'register','value'=>'Register','class'=>'btn btn-primary']); ?>
		            </fieldset> 
				<?php echo form_close(); ?>
				<?php echo $message; ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
</div><!-- /.container -->
</div><!-- /.mainBody -->
	