
<div id="mainBody">
<div class="container">
	<div class="row">
		<div class="span12">
			<h1 align="center"><?= $title; ?></h1><hr/>
			<ul class="thumbnails">
				<?php 
		   			for($i=0; $i<sizeof($movies); $i++) {
				    	echo '
				    	<li class="span3">
							<div class="thumbnail">
								'.form_open('film/catalog').'
		   						'.form_hidden('id', $movies[$i]['id']).'
								<img src="'.$movies[$i]['poster'].'" width="260" height="350" style="max-width: 260px; max-height: 350px;"/>
								<div class="caption">';
						if (strlen($movies[$i]['title']) < 28) echo '<h5>'.$movies[$i]['title'].'<br/><br/></h5>';
							else echo '<h5>'.$movies[$i]['title'].'</h5>';
						echo '
									<!--h4 style="text-align:center; margin-bottom: -30px;">'.form_submit('detail','Detail','class="btn btn-primary" ').'</h4-->
									<h4 style="text-align:center; margin-bottom: -30px;" ><a href="'.site_url('film/detail/'.$movies[$i]['id']).'" class="btn btn-primary" style="width:90%;">Detail</a></h4>
									
								</div>
							</div>
						</li>';
				   		echo form_close();
				    }
		   		?>
			</ul>
		</div>
	</div>
</div>
	
</div>
<!-- End of main body ==================================== -->