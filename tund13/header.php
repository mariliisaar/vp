<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">

  <title>Veebiprogrammeerimine 2020</title>
  <?php
	if(isset($tolink)) {
		echo $tolink;
	}
  ?>
  <style>
	<?php
		echo "body { \n";
		if(isset($_SESSION["userbgcolor"])) {
			echo "\t background-color: " .$_SESSION["userbgcolor"] .";\n";
		} 
		else {
			echo "\t background-color: #ffffff;\n";
		}
		if(isset($_SESSION["usertxtcolor"])) {
			echo "\t color: " .$_SESSION["usertxtcolor"] .";\n";
		} 
		else {
			echo "\t color: #000000;\n";
		}
		echo "\t}\n";
	?>
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style/style.css">
  <script src="javascript/js.js"></script>

</head>
<body>