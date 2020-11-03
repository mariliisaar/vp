<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_readfilminfo.php");
  
  // $filmhtml = readfilms();
  // $username = "Marilii Saar";
  
  $sortby = 0;
  $sortorder = 0;

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="home.php">Esilehele</a></li>
  <li><a href="oldlinks.php">Vanad Failid</a></li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  <?php 
    if(isset($_GET["sortby"]) and isset($_GET["sortorder"])) {
	  if($_GET["sortby"] >= 1 and $_GET["sortby"] <= 4) {
		  $sortby = $_GET["sortby"];
	  }
	  if($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2) {
		  $sortorder = $_GET["sortorder"];
	  }
    }
	echo readpersonsinfilm($sortby, $sortorder); 
  ?>

</body>
</html>