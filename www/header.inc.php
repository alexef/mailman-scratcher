<html>
<head>
  <title><?php echo the_title(); ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2"/> 
  <style type="text/css" media="all"> 
    @import url("res/style.css");  
  </style>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
  <script type="text/javascript" src="res/script.js"></script>
  <script type="text/javascript" src="res/jquery.corner.js"></script>
  <script type="text/javascript"> $("#container").corner();  $("#logo").corner("top");</script>
</head>

<body bgcolor="#a7a7a7">
  <div id='container'>
    <div id='logo'>
	<?php echo the_banner(); ?>
    </div>
  	<?php if ($action != 'page' and $action != '') : ?>
        <a class="list-label" href="./?list=<?php echo $list['name']; ?>&action=index"><span id='list-label'></span></a>
	<?php endif; ?>
  
