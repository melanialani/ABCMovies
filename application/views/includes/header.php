<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>ABC Movies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<!-- Bootstrap style -->
	<link rel="stylesheet" media="screen" href="<?= base_url('assets/themes/css/base.css'); ?>" />
	<link id="callCss" rel="stylesheet" media="screen" href="<?= base_url('assets/themes/bootshop/bootstrap.min.css'); ?>" />

	<!-- Bootstrap style responsive -->
	<link rel="stylesheet" href="<?= base_url('assets/themes/css/bootstrap-responsive.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/themes/css/font-awesome.css'); ?>" />

	<!-- Google-code-prettify -->
	<link rel="stylesheet" href="<?= base_url('assets/themes/js/google-code-prettify/prettify.css'); ?>" />
	
	<!-- DataTables -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/datatable/media/css/jquery.dataTables.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/datatable/examples/resources/syntax/shCore.css'); ?>">

	<style type="text/css" id="enject"></style>
	<style type="text/css" class="init"></style>
	
	<script type="text/javascript" language="javascript" src="<?= base_url('assets/datatable/media/jquery-1.12.4.js'); ?>"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url('assets/datatable/media/js/jquery.dataTables.js'); ?>"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url('assets/datatable/examples/resources/syntax/shCore.js'); ?>"></script>
	<script type="text/javascript" language="javascript" src="<?= base_url('assets/datatable/examples/resources/demo.js'); ?>"></script>
	
	<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		$('#datatable').DataTable( {
			"order": [[ 3, "desc" ]]
		} );
	} );
	</script>
	
	<style type="text/css" id="enject"></style>
</head>

<body>
<div id="header">
	<div class="container">
		<!-- Navbar ================================================== -->
		<div id="logoArea" class="navbar">
			<?php if($name!=null) echo "<p align='right' style='margin-right: 10px; margin-bottom: -30px;'> Hello, ".$name ."</p>"; ?>
			<div class="navbar-inner" style="margin-top: 20px;">
				<a class="brand" href="<?php echo site_url('film/index'); ?>"><img src="<?= base_url('assets/themes/images/logo.png'); ?>"/></a>
				<!--form class="form-inline navbar-search" method="post" action="products.html" >
				<input id="srchFld" class="srchTxt" type="text" />
				<button type="submit" id="submitButton" class="btn btn-primary">Go</button>
				</form-->
				
				<ul id="topMenu" class="nav pull-right">
					<li><a href="<?php echo site_url('film/now'); ?>" style="color: white;">Now Playing</a></li>
					<li><a href="<?php echo site_url('film/soon'); ?>" style="color: white;">Coming Soon</a></li>
					<li><a href="<?php echo site_url('film/old'); ?>" style="color: white;">Old Movies</a></li>
					<?php
					// if admin, show master film
					if ($this->input->cookie('abcmovies') == 'admin@abcmovies.co.id'){
						echo '<li><a href="'.site_url('admin/masterFilm').'" style="color: white;">Master Film</a></li>';
						echo '<li><a href="'.site_url('admin/masterBanner').'" style="color: white;">Master Banner</a></li>';
					}
					// show login-register or profile-logout
					if ($this->input->cookie('abcmovies')){
						echo '<a href="'.site_url('user/profile').'" role="button" style="padding-right:0;"><span class="btn btn-large btn-success" style="margin-top: 12px;">Profile</span></a>';
						echo '<a href="'.site_url('user/logout').'" role="button" style="padding-right:0;"><span class="btn btn-large btn-success" style="margin-top: 12px;">Logout</span></a>';
					} else {
						echo '<a href="'.site_url('user/login').'" role="button" style="padding-right:0;"><span class="btn btn-large btn-success" style="margin-top: 12px;">Login</span></a>';
						echo '<a href="'.site_url('user/register').'" role="button" style="padding-right:0"><span class="btn btn-large btn-success" style="margin-top: 12px;">Register</span></a>';
					}
					?>
				</ul>

			</div>
		</div>
	</div>
</div> 
<!-- Header End====================================================================== -->

