<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_common.php");
  require("fnc_filminfo.php");
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  
  $studionotice = "";
  $personnotice = "";
  $filmgenrenotice = "";

  $firstname = "";
  $lastname = "";
  $birthday = null;
  $birthmonth = null;
  $birthyear = null;
  $birthdate = null;

  $filmgenre = "";
  $genredescription = "";

  $studioname = "";
  $studioaddress = "";

  $studioerror = "";
  $firstnameerror = "";
  $lastnameerror = "";
  $birthdayerror = null;
  $birthmontherror = null;
  $birthyearerror = null;
  $birthdateerror = null;
  $filmgenreerror = "";

  // Kui klikiti isiku submit, siis ...
  if(isset($_POST["personsubmit"])) {
    if(empty($_POST["firstnameinput"])) {
        $firstnameerror = "Eesnimi on sisestamata!";
    }
    else {
      $firstname = test_input($_POST["firstnameinput"]);
    }

    if(empty($_POST["lastnameinput"])) {
      $lastnameerror = "Perekonnanimi on sisestamata!";
    }
    else {
      $lastname = test_input($_POST["lastnameinput"]);
    }
  
    if(!empty($_POST["birthdayinput"])) {
      $birthday = intval($_POST["birthdayinput"]);
    }
    else {
      $birthdayerror = "Palun vali sünnikuupäev!";
    }
    
    if(!empty($_POST["birthmonthinput"])) {
      $birthmonth = intval($_POST["birthmonthinput"]);
    }
    else {
      $birthmontherror = "Palun vali sünnikuu!";
    }
    
    if(!empty($_POST["birthyearinput"])) {
      $birthyear = intval($_POST["birthyearinput"]);
    }
    else {
      $birthyearerror = "Palun vali sünniaasta!";
    }
    
    // Kontrollime kuupäeva kehtivust
    if(!empty($birthday) and !empty($birthmonth) and !empty($birthyear)) {
      if(checkdate($birthmonth, $birthday, $birthyear)) {
        $tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
        $birthdate = $tempdate->format("Y-m-d");
      }
      else {
        $birthdateerror = "Kuupäev ei ole reaalne!";
      }
    }

    if(empty($firstnameerror) and empty($lastnameerror) and empty($birthdayerror) and empty($birthmontherror) and empty($birthyearerror) and empty($birthdateerror)) {
      $result = saveperson($firstname, $lastname, $birthdate);
      if($result == "OK") {
        $personnotice = "Isik salvestatud!";
        $firstname = "";
        $lastname = "";
        $birthday = null;
        $birthmonth = null;
        $birthyear = null;
        $birthdate = null;
      }
      else {
        $personnotice = $result;
      }    
    }
  }

  // Kui klikiti genre submit, siis ...
  if(isset($_POST["filmgenresubmit"]))  {
    if(empty($_POST["filmgenreinput"])){
        $filmgenreerror = "Filmižanr sisestamata!";
    }
    else {
        $filmgenre = test_input($_POST["filmgenreinput"]);
        $genredescription = test_input($_POST["genredescriptioninput"]);
    }
    if(empty($filmgenreerror)){
        $result = savefilmgenre($filmgenre, $genredescription);
        if($result == "OK") {
          $filmgenrenotice = "Žanr salvestatud!";
          $filmgenre = "";
          $genredescription = "";
        }
        else {
          $filmgenrenotice = $result;
        }
    }
  }

  // Kui klikiti stuudio submit, siis ...
  if(isset($_POST["studiosubmit"])) {
    if(empty($_POST["studionameinput"])) {
      $studioerror = "Stuudio nimi on sisestamata!";
    }
    else {
      $studioname = test_input($_POST["studionameinput"]);
      $studioaddress = test_input($_POST["studioaddressinput"]);
    }

    if(empty($studioerror)) {
      $result = savestudio($studioname, $studioaddress);
      if($result == "OK") {
        $studionotice = "Stuudio salvestatud!";
        $studioname = "";
        $studioaddress = "";
      }
      else {
        $studionotice = $result;
      }
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
	<li><a href="home.php">Esilehele</a></li>
	<li><a href="form.php">Lisa oma mõte</a></li>
	<li><a href="thoughts.php">Loe varasemaid mõtteid</a></li>
	<li><a href="listfilms.php">Loe filmiinfot</a></li>
	<li><a href="addfilms.php">Filmiinfo lisamine</a></li>
	<li><a href="movieconnect.php">Seoste lisamine</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
  </ul>
  <hr />
  <h2>Lisa isik</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="firstnameinput">Isiku eesnimi</label>
	<input type="text" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>">
  <span><?php echo $firstnameerror; ?></span>
	<br /><br />
  <label for="lastnameinput">Isiku perkonnanimi</label>
	<input type="text" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>">
  <span><?php echo $lastnameerror; ?></span>
	<br /><br />
  <label for="birthdayinput">Sünnipäev: </label>
  <?php
  echo '<select name="birthdayinput" id="birthdayinput">' ."\n";
  echo "\t\t" .'<option value="" selected disabled>päev</option>' ."\n";
  for ($i = 1; $i < 32; $i ++){
    echo "\t\t" .'<option value="' .$i .'"';
    if ($i == $birthday){
      echo " selected ";
    }
    echo ">" .$i ."</option> \n";
  }
  echo "\t </select> \n";
  ?>
  <label for="birthmonthinput">Sünnikuu: </label>
  <?php
    echo '<select name="birthmonthinput" id="birthmonthinput">' ."\n";
  echo "\t\t" .'<option value="" selected disabled>kuu</option>' ."\n";
  for ($i = 1; $i < 13; $i ++){
    echo "\t\t" .'<option value="' .$i .'"';
    if ($i == $birthmonth){
      echo " selected ";
    }
    echo ">" .$monthnameset[$i - 1] ."</option> \n";
  }
  echo "\t </select> \n";
  ?>
  <label for="birthyearinput">Sünniaasta: </label>
  <?php
    echo '<select name="birthyearinput" id="birthyearinput">' ."\n";
  echo "\t\t" .'<option value="" selected disabled>aasta</option>' ."\n";
  for ($i = date("Y"); $i >= date("Y") - 200; $i --){
    echo "\t\t" .'<option value="' .$i .'"';
    if ($i == $birthyear){
      echo " selected ";
    }
    echo ">" .$i ."</option> \n";
  }
  echo "\t </select> \n";
  ?>
  <br />
  <span><?php echo $birthdateerror ." " .$birthdayerror ." " .$birthmontherror ." " .$birthyearerror; ?></span>
	<br />
	<input type="submit" name="personsubmit" value="Salvesta isiku info">
  </form>
  <p><?php echo $personnotice; ?></p>

  <h2>Lisa film</h2>
  Carolyn

  <h2>Lisa žanr</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="filmgenreinput">Žanr</label>
    <br />
    <input type="text" name="filmgenreinput" id="filmgenreinput" placeholder="Filmižanr">
    <span><?php echo $filmgenreerror; ?></span>
    <br />
    <label for="genredescriptioninput">Žanri lühitutvustus</label>
    <br />
    <textarea rows="5" cols="30" name="genredescriptioninput" id="genredescriptioninput" placeholder="Žanri lühitutvustus"></textarea>
    <br />
    <input type="submit" name="filmgenresubmit" value="Salvesta filmižanri info">
  </form>
  <p><?php echo $filmgenrenotice; ?></p>

  <h2>Lisa filmistuudio</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="studionameinput">Stuudio nimi</label>
	<br />
	<input type="text" name="studionameinput" id="studionameinput" placeholder="Stuudio nimi">
	<br />
	<label for="studioaddressinput">Stuudio aadress</label>
	<br />
	<textarea rows="5" cols="20" name="studioaddressinput" id="studioaddressinput" placeholder="Aadress: "><?php echo $studioaddress; ?></textarea>
	<br />
	<input type="submit" name="studiosubmit" value="Salvesta filmistuudio info">
  </form>
  <p><?php echo $studionotice; ?></p>
</body>
</html>