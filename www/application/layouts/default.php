<!DOCTYPE html>
<html>
<head>

	<title><?php echo @$this->view->head['title'] ?></title>
	<base href="<?php echo Path::urlBase(); ?>/"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?php echo @$this->view->head['description'] ?>">
	<meta name="keywords" content="<?php echo @$this->view->head['keywords'] ?>">

	<meta property="og:title" content="<?php echo empty($this->view->head['og_title']) ? @$this->view->head['title'] : $this->view->head['og_title'] ?>">
	<meta property="og:description" content="<?php echo empty($this->view->head['og_description']) ? @$this->view->head['description'] : $this->view->head['og_description'] ?>">
	<meta property="og:image" content="<?php echo empty($this->view->head['og_image']) ? $this->view->head['image'] : $this->view->head['og_image'] ?>">
	<meta property="og:type" content="website"> 
	<meta property="og:url" content="<?php echo Path::pageUrl() ?>"> 

	<meta name="format-detection" content="telephone=no">
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="<?php echo Path::urlBase(); ?>/">
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/global.min.css">
	
	<script src="scripts/jquery-1.11.1.min.js"></script>
	<script src="scripts/global.js"></script>
	
	<?php if($this->detect->isMobile()) { ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php } ?>

</head>
<body>
<!-- VIEW -->
<?php
	$this->viewContent();
?>		
<!-- VIEW END -->	
</body>
</html>