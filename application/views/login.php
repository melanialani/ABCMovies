
<div id="mainBody">
<div class="container">
	<div class="row">
		<h1 align="center">Login</h1><hr/>
		<div class="span3"></div>
		<div class="span6" align="center">
			<div class="thumbnail">
				<?php echo form_open('user/login', "role='form'"); ?>
					<fieldset style="margin-top: 20px;"> 
						<div class="form-group">
							<label><strong>Email</strong></label>
							<input type="email" class="form-control" placeholder="email *" id="email" name="email" autofocus="" required data-validation-required-message="Masukan alamat email">
		                   	<p class="help-block text-danger"></p>
		                </div>
		                <div class="form-group">
		                	<label><strong>Password</strong></label>
		                	<input type="password" class="form-control" placeholder="password *" id="password" name="password" required data-validation-required-message="Masukan password">
		                	<p class="help-block text-danger"></p>
		                </div>
		                <!--div class="form-group">
		                	<input name="rememberMe" type="checkbox" value="1"> Remember Me
		                </div-->
		                <hr/>
		         		<?php echo form_submit(['id'=>'login','name'=>'login','value'=>'Login','class'=>'btn btn-primary']); ?>
		            </fieldset> 
				<?php echo form_close(); ?>
				<?php echo $message; ?>
			</div>
		</div><!-- /.span12 -->
	</div><!-- /.row -->	
</div><!-- /.container -->
</div><!-- /.mainBody -->
	