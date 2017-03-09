
<div id="mainBody">
<div class="container">
	<div class="row">
		<div class="span12">
			<h1 align="center">Not Playing Anymore</h1><hr/>
			<ul class="thumbnails">
				<?php 
		   			for($i=0; $i<sizeof($movies); $i++) {
				    	echo '
				    	<li class="span3">
							<div class="thumbnail">
								'.form_open('film/old').'
		   						'.form_hidden('id', $movies[$i]['id']).'
								<img src="http://www.21cineplex.com/data/gallery/pictures/'.$movies[$i]['poster'].'_452x647.jpg" style="width:100%"/>
								<div class="caption">
									<h5>'.$movies[$i]['title'].'</h5>
									<h4 style="text-align:center; margin-bottom: -30px;">'.form_submit('detail','Detail','class="btn btn-primary" style="width:100%;"').'</h4>
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