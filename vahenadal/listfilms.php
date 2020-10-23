<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_films.php");
  
  // $filmhtml = readfilms();
  // $username = "Marilii Saar";

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p><a href="?logout=1">Logi välja!</a></p>
  <ul>
	<li><a href="home.php">Esilehele</a></li>
	<li><a href="form.php">Lisa oma mõte</a></li>
	<li><a href="thoughts.php">Loe varasemaid mõtteid</a></li>
	<li><a href="listfilms.php">Loe filmiinfot</a></li>
	<li><a href="addfilms.php">Filmiinfo lisamine</a></li>
	<li><a href="movieconnect.php">Seoste lisamine</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
  </ul>
  <hr />
  <?php echo readfilms(); ?>

</body>
</html>