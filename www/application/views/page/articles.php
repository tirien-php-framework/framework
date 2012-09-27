<style>
	body{
		background:none;
	}
	
	.content{
		box-shadow:none;
	}
</style>

<div class="articleMenu">

	<h2 style="margin-top:0;">Kategorije</h2>
	<ul class="categories">
		<li><a href="<?php echo Path::urlRoot(); ?>/iphone">iPhone</a></li>
		<li><a href="<?php echo Path::urlRoot(); ?>/ipad">iPad</a></li>
	</ul>
	
	<h2>iPhone</h2>
	<ul>
		<li><a href="<?php echo Path::urlRoot(); ?>/iphone?c=maske">Maske</a></li>
		<li><a href="<?php echo Path::urlRoot(); ?>/iphone?c=slusalice">Slušalice</a></li>
		<li><a href="<?php echo Path::urlRoot(); ?>/iphone?c=kablovi">Kablovi</a></li>
	</ul>

	<h2>iPad</h2>
	<ul>
	</ul>
	
</div>

<div class="articleList">
<?php  if( Router::$param=="iphone" ) { 
			
			$maske = '';
			$slusalice = '';
			$kablovi = '';
			
			for($i=1; $i<=38; $i++){
				$maske .= '<div class="item borderBox"><div class="image"><img src="'.Path::image('articles/maske/'.$i.'.jpg').'"/></div></div>';
			}
			
			for($i=1; $i<=23; $i++){
				$slusalice .= '<div class="item borderBox"><div class="image"><img src="'.Path::image('articles/slusalice/'.$i.'.jpg').'"/></div></div>';
			}

			for($i=1; $i<=4; $i++){
				$kablovi .= '<div class="item borderBox"><div class="image"><img src="'.Path::image('articles/kablovi/'.$i.'.jpg').'"/></div></div>';
			}


					
			if( !empty($_GET['c']) ) {
			
				$c = $_GET['c'];
				
				if( $c=="maske" ) { echo $maske; }

				if( $c=="slusalice" ) { echo $slusalice; }

				if( $c=="kablovi" ) { echo $kablovi; }
			}
			else {
				echo  $maske. $slusalice. $kablovi;
			}
			
		} 
		else
		{
			echo "Trenutno nema artikala u ovoj kategoriji.";
		}
?>	

	
</div>