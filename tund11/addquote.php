<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_common.php");
  require("fnc_quote.php");

  $notice = "";
  
  $selectedrole = "";
  $quote = "";
  
  if(isset($_POST["quotesubmit"])) {
	  if(!empty($_POST["quoteinput"])){
		$quote = test_input($_POST["quoteinput"]);
	} else {
		$notice .= " Sisesta tsitaat!";
	}
	if(!empty($_POST["roleinput"])){
		$selectedrole = intval($_POST["roleinput"]);
	} else {
		$notice .= " Vali roll filmis!";
	}
	if(!empty($quote) and !empty($selectedrole)){
		$notice = storenewquote($selectedrole, $quote);
	}
  }
  
  $roleselect = readroletoselect($selectedrole);

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="home.php">Esilehele</a></li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  <h2>Lisame tsitaadi</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="filmdescriptioninput">Tsitaat</label>
  <br />
  <textarea rows="1" cols="39" name="quoteinput" id="quoteinput" placeholder="Tsitaat..."><?php echo $quote; ?></textarea>
  <br />
	<?php
		echo $roleselect;
	?>  
  <br />
  <span><?php echo $notice; ?></span>
  <br />
  <input type="submit" name="quotesubmit" value="Salvesta tsitaat">
  </form>
</body>
</html>