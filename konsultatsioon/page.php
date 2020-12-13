<?php
  // Käivitan sessiooni
  // session_start();
  require("classes/SessionManager.class.php");
  SessionManager::sessionStart("vp", 0, "/~marisaa/", "greeny.cs.tlu.ee");
  require("../../../config.php");
  require("fnc_user.php");
  require("fnc_common.php");
  
  $email = null;
  $formerror = null;
  $emailerror = null;
  $pwderror = null;
  
  if(isset($_POST["usersubmit"])) {
	  if(empty($_POST["emailinput"])) {
        $emailerror = "Kasutajatunnus on sisestamata!";
      }
    else {
		$email = filter_var($_POST["emailinput"], FILTER_SANITIZE_EMAIL);
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		} else {
		  $emailerror = "Palun sisesta õige kujuga e-postiaadress!";
		}
    }
	  
	  if(empty($_POST["passwordinput"])) {
        $pwderror = "Salasõna on sisestamata!";
      }
      if(!empty($_POST["passwordinput"]) and strlen($_POST["passwordinput"]) < 8) {
        $pwderror = "Liiga lühike salasõna! (" . strlen($_POST["passwordinput"]) . " märki 8 asemel)";
      }

    if(empty($emailerror) and empty($pwderror)) {
      $result = signin($email, $_POST["passwordinput"]);
        $formerror = $result;
   }
  }
  
  require("header.php");
  
?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Veebiprogrammeerimine 2020</h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <hr />
  <h1>Logi sisse:</h1>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="emailinput">Kasutajatunnus (email): </label><br />
	<input type="email" name="emailinput" id="emailinput" placeholder="Email" value="<?php echo $email; ?>"><span><?php echo $emailerror . "<br />"; ?></span>
	<label for="passwordinput">Salasõna: </label><br />
	<input type="password" name="passwordinput" id="passwordinput" placeholder="***"><span><?php echo $pwderror . "<br />"; ?></span>
	<span><?php echo $formerror . "<br />"; ?></span>
	<input type="submit" name="usersubmit" value="Logi sisse">
  </form>
  <br />
  Või <a href="adduser.php">loo kasutaja</a>
</body>
</html>