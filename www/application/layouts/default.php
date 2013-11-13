<!DOCTYPE html>
<html>
<head>

	<title><?php echo $this->view->head['title'] ?></title>
	<base href="<?php echo Path::urlBase(); ?>/">
	<meta http-equiv="Content-Type" content="text/html;" charset="utf-8">
	<meta name="description" content="<?php echo $this->view->head['description'] ?>">

	<meta property="og:title" content="<?php echo empty($this->view->head['og_title']) ? $this->view->head['title'] : $this->view->head['og_title'] ?>"/>
	<meta property="og:description" content="<?php echo empty($this->view->head['og_description']) ? $this->view->head['description'] : $this->view->head['og_description'] ?>"/>
	<meta property="og:image" content="<?php echo $this->view->head['og_image'] ?>"/>

	
	<link rel="stylesheet" href="css/reset.css" type="text/css">
	<link rel="stylesheet" href="css/global.css" type="text/css">
	
	<script src="//code.jquery.com/jquery-latest.min.js"></script>
	<script src="scripts/global.js"></script>
	
</head>
<body>
<!-- VIEW -->
<?php
	$this->viewContent();
?>		
<!-- VIEW END -->	
</body>
</html>