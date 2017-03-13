
<div id="carouselBlk" style="margin-top: -20px;">
	<div id="myCarousel" class="carousel slide">
		<div class="carousel-inner">
			<div class="item active">
				<div class="container">
					<img src="<?= base_url($banners[0]['picture']); ?>" title="<?= $banners[0]['name']; ?>" width="1170" height="480" style="max-width: 1170px; max-height: 480px;"/></a>
				</div>
			</div>
			<!--div class="item">
				<div class="container">
					<a href="register.html"><img src="<?= base_url('assets/themes/images/carousel/2.png'); ?>" alt="special offers"/></a>
				</div>
			</div-->
			<?php
			for ($i=1; $i<sizeof($banners); $i++){
				echo '
				<div class="item">
					<div class="container">
						<img src="'.base_url($banners[$i]['picture']).'" title="'.$banners[$i]['name'].'" width="1170" height="480" style="max-width: 1170px; max-height: 480px;"/>
					</div>
				</div>
				';
			}
			?>
		</div>
		
		<!-- navigation < and > -->
		<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
		<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
	</div>
</div>

<div id="mainBody">
<div class="container">
	<div class="row">
		<div class="span12">
			<h1 align="center">Now Playing</h1><hr/>
			<ul class="thumbnails">
				<?php 
		   			for($i=0; $i<sizeof($movies); $i++) {
				    	echo '
				    	<li class="span3">
							<div class="thumbnail">
								'.form_open('film/index').'
		   						'.form_hidden('id', $movies[$i]['id']).'
								<img src="'.$movies[$i]['poster'].'" width="260" height="350" style="max-width: 260px; max-height: 350px;"/>
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