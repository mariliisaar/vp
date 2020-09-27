<?php

  require("../../../config.php");
  // require("fnc_saveuser.php");
  
  $firstname = "";
  $lastname = "";
  $email = "";
  $gender = 0;
  $firstnameerror = "";
  $lastnameerror = "";
  $emailerror = "";
  $gendererror = "";
  $passworderror = "";

  // on submit
  if(isset($_POST["usersubmit"])) {
      if(empty($_POST["firstnameinput"])) {
          $firstnameerror = "Eesnimi on sisestamata!";
      }

      if(empty($_POST["lastnameinput"])) {
        $lastnameerror = "Perekonnanimi on sisestamata!";
      }

      if(empty($_POST["genderinput"])) {
        $gendererror = "Sugu on valimata!";
      }

      if(empty($_POST["emailinput"])) {
        $emailerror = "E-maili aadress on sisestamata!";
      }

      if(strlen($_POST["passwordinput"]) < 8 or strlen($_POST["passwordsecondaryinput"]) < 8) {
        $passworderror = "Salasõna peab olema vähemalt 8 tähemärki pikk!";
      }

      if(empty($_POST["passwordsecondaryinput"])) {
        $passworderror = "Palun sisesta salasõna uuesti!";
      }

      if(empty($_POST["passwordinput"])) {
        $passworderror = "Palun sisesta salasõna!";
      }

      if($_POST["passwordsecondaryinput"] !== $_POST["passwordinput"]) {
        $passworderror = "Salasõnad ei ole ühesugused!";
      }

      if(!empty($_POST["firstnameinput"]) and empty($firstnameerror)) {
          $firstname = $_POST["firstnameinput"];
      }

      if(!empty($_POST["lastnameinput"]) and empty($lastnameerror)) {
        $lastname = $_POST["lastnameinput"];
      }

      if(!empty($_POST["genderinput"]) and empty($gendererror)) {
        $gender = $_POST["genderinput"];
      }

      if(!empty($_POST["emailinput"]) and empty($emailerror)) {
        $email = $_POST["emailinput"];
      }

      if(empty($firstnameerror) and empty($lastnameerror) and empty($emailerror) and empty($gendererror) and empty($passworderror)) {
          echo "Success!";
        //   saveuser($_POST["firstnameinput"], $_POST["lastnameinput"], $_POST["genderinput"], $_POST["emailinput"], $_POST["passwordinput"]);
      }
  }
  
  $username = "Marilii Saar";

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $username; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="home.php">Avaleht</a></li>
  </ul>
  <hr />
  <form method="POST">
	<label for="firstnameinput">Eesnimi</label>
	<input type="text" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>">
    <span><?php echo $firstnameerror; ?></span>
	<br />
    <label for="lastnameinput">Perekonnanimi</label>
	<input type="text" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>">
    <span><?php echo $lastnameerror; ?></span>
	<br />
    <label for="genderinput">Sugu</label><br />
	<input type="radio" name="genderinput" id="gendermale" value="1" <?php if($gender == "1"){echo " checked";}?>>
    <label for="gendermale">Mees</label>
	<br />
    <input type="radio" name="genderinput" id="genderfemale" value="2" <?php if($gender == "2"){echo " checked";}?>>
    <label for="genderfemale">Naine</label>
	<br />
    <span><?php echo $gendererror . "<br />"; ?></span>
    <label for="emailinput">E-posti aadress</label>
	<input type="email" name="emailinput" id="emailinput" placeholder="Email" value="<?php echo $email; ?>">
    <span><?php echo $emailerror; ?></span>
	<br />
    <label for="passwordinput">Salasõna</label>
	<input type="password" name="passwordinput" id="passwordinput" placeholder="***">
	<br />
    <label for="passwordsecondaryinput">Salasõna uuesti</label>
	<input type="password" name="passwordsecondaryinput" id="passwordsecondaryinput" placeholder="***">
	<br />
    <span><?php echo $passworderror . "<br />"; ?></span>
	<input type="submit" name="usersubmit" value="Loo kasutaja">
  </form>
</body>
</html>