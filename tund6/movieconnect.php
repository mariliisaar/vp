<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_films.php");

  $inputerror = "";
  if(isset($_POST["connectsubmit"])) {
    if(empty($_POST["personinput"]) or empty($_POST["movieinput"]) or empty($_POST["positioninput"])) {
      $inputerror .= "Osa infot on sisestamata! ";
    }
    if(empty($inputerror)) {
      $inputerror = saveconnection($_POST["personinput"], $_POST["movieinput"], $_POST["positioninput"]);
    }
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
    <label for="personinput">Isik: </label>
    <select name="personinput" id="personinput">
      <option value="" selected disabled>Vali Isik</option>
      <?php echo personfromdb(); ?>
    </select>
    <label for="movieinput">Film: </label>
    <select name="movieinput" id="movieinput">
      <option value="" selected disabled>Vali Film</option>
      <?php echo moviefromdb(); ?>
    </select>
    <label for="positioninput">Amet: </label>
    <select name="positioninput" id="positioninput">
      <option value="" selected disabled>Vali Amet</option>
      <?php echo positionfromdb(); ?>
    </select>
	  <input type="submit" name="connectsubmit" value="Salvesta seos">
  </form>
  <p><?php echo $inputerror; ?></p>
</body>
</html>