<?php 
	$url = 'http://images.apple.com/main/rss/hotnews/hotnews.rss';
	$rss = simplexml_load_string( file_get_contents($url) );
?>

<div class="slider-wrapper">
	<div id="slider" class="nivoSlider">
		<img src="<?php echo Path::image('iphone_01.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_02.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_03.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_04.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_05.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_06.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_07.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_08.jpg'); ?>"/>
		<img src="<?php echo Path::image('iphone_09.jpg'); ?>"/>
	</div>
</div>	

<div class="contentBox grid2col shadow">
	<a href="http://www.apple.com/itunes/" target="_blank">
		<img src="<?php echo Path::image('itunes.jpg'); ?>" style="width: 125px; padding: 10px; float:left;" />
	</a>
	<h1>Skinite iTunes</h1>
	<p class="last">With iTunes in the Cloud, the music, apps, and books you purchase automatically appear on all your devices. Or you can download only the stuff you want — including movies and TV shows. It’s all part of iCloud and iTunes</p>
</div>

<div class="contentBox grid2col shadow last" style="float:right;">
	<iframe width="440" height="248" src="http://www.youtube.com/embed/u5X5cV-4LRo?showinfo=0" frameborder="0" allowfullscreen style="margin: 10px 20px 7px;"></iframe>
</div>

<div class="contentBox grid2col shadow last news">
	<h1>Apple News</h1>
	<p class="last">
	<?php 
		$i = 1;
		foreach($rss->channel->item as $news){ 
			if( $i > 5) break;
			echo '<a href="'.$news->link.'" target="_blank">'.$news->title.'</a>';
			$i++;
		}
	?>
	</p>
</div>