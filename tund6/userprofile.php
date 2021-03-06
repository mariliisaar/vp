<?php

  require("usesession.php");
  require("../../../config.php");
  require("fnc_user.php");
  require("fnc_common.php");
  
  $notice = "";
  $userdescription = readuserdescription();
  // Kui klikiti submit, siis ...
  if(isset($_POST["profilesubmit"])) {
	$userdescription = test_input($_POST["descriptioninput"]);
	
	$notice = storeuserprofile($userdescription, $_POST["bgcolorinput"], $_POST["txtcolorinput"]);
	$_SESSION["userbgcolor"] = $_POST["bgcolorinput"];
	$_SESSION["usertxtcolor"] = $_POST["txtcolorinput"];
  }

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p><a href="?logout=1">Logi välja!</a></p>
  <ul>
	<li><a href="page.php">Esilehele</a></li>
	<li><a href="form.php">Lisa oma mõte</a></li>
	<li><a href="thoughts.php">Loe varasemaid mõtteid</a></li>
	<li><a href="listfilms.php">Loe filmiinfot</a></li>
	<li><a href="addfilms.php">Filmiinfo lisamine</a></li>
	<li><a href="movieconnect.php">Seoste lisamine</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
  </ul>
  <hr />
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="descriptioninput">Minu lühikirjeldus</label>
	<br/>
	<textarea rows="10" cols="80" name="descriptioninput" id="descriptioninput" placeholder="Minu lühikirjeldus..."><?php echo $userdescription; ?></textarea>
	<br />
	<label for="bgcolorinput">Minu valitud taustavärv</label>
	<input type="color" name="bgcolorinput" id="bgcolorinput" value="<?php echo $_SESSION["userbgcolor"]; ?>">
	<br />
	<label for="txtcolorinput">Minu valitud tekstivärv</label>
	<input type="color" name="txtcolorinput" id="txtcolorinput" value="<?php echo $_SESSION["usertxtcolor"]; ?>">
	<br />
	<input type="submit" name="profilesubmit" value="Salvesta profiil">
  </form>
  <p><?php echo $notice; ?></p>
</body>
</html>