<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="author" content="Tirien.com">
		<title>iPhone Service</title>
		<meta name="keywords" content="iphone service, apple, ipod, gmate, beograd, srbija">
		<meta name="description" content="Servis za iPhone i ostale Apple uredaje. Prodaja opreme za Apple proizvode.">
		<link rel="stylesheet" href="<?php echo Path::css('general.css'); ?>" type="text/css">
		
		<link rel="stylesheet" href="<?php echo Path::css('nivo-slider.css'); ?>" type="text/css" media="screen" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo Path::script('jquery.nivo.slider.js'); ?>" type="text/javascript"></script>
		<script type="text/javascript">
			$(window).load(function() {
			$('#slider').nivoSlider({
			directionNav: false,
			controlNav: false
			});
			});
		</script>

		<link rel="Shortcut Icon" href="<?php echo Path::image("favicon.ico"); ?>" type="image/x-icon">
		
	</head>
	<body>
	
		<div class="wrap">
			<a href="<?php echo Path::urlRoot(); ?>"><img src="<?php echo Path::image('logo.png'); ?>" class="logo" /></a>
			<ul class="menu">
				<li><a href="<?php echo Path::urlRoot(); ?>">Početna</a></li>
				<li><a href="<?php echo Path::urlRoot(); ?>/o-nama">O nama</a></li>
				<li><a href="<?php echo Path::urlRoot(); ?>/iphone">iPhone</a></li>
				<li><a href="<?php echo Path::urlRoot(); ?>/ipad">iPad</a></li>
				<li><a href="<?php echo Path::urlRoot(); ?>/gmate">Gmate</a></li>
				<li><a href="<?php echo Path::urlRoot(); ?>/exclusive">Exclusive</a></li>
			</ul>
			<div class="content">
			
				<?php
					$this->viewContent();
				?>
			<div style="clear:both;"></div>
			</div>
			<div class="footer">
				Copyright &copy; 2012 SERVIS + | Balkanska 3, 11000, Beograd. <a href="http://www.tirien.rs/" target="_blank">Web sajt by Tirien.rs</a>.
				<br/>
				+381 11 2688 193 | +381 66 169 179
			</div>

		</div>
		
	</body>
</html>