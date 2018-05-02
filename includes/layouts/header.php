<?php 
if (!isset($layout_context)){
	$layout_context = "public";
}
?>
<html lang="en">
<head>
	<title>Webster IT Club <?php if($layout_context == "admin"){ echo "Admin"; }?></title>
	<link href = "stylesheets/public.css" media = "all" rel = "stylesheet" type = "text/css" />
</head>
<body>
	<div id = "header">

		<h1><img src = "images/logo.png" width="60" height="60"  /> Webster IT Club <?php if($layout_context == "admin"){ echo "Admin"; }?></h1>
	</div>
	