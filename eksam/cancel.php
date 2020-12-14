<?php
  require("../../../config.php");
  require("fnc_reg.php");
  require("fnc_common.php");

  $code = null;
  $codeerror = "";
  $existsdelete = "";
  $notice = "";
    
    
  if (isset($_POST["cancelsubmit"])){
    if(empty($_POST["codeinput"])) {
      $codeerror = "Üliõpilaskood on sisestamata!";
    }
    if (strlen(($_POST["codeinput"])) != 6) {
      $codeerror = "Üliõpilaskood peab olema 6 numbrit pikk!";
    }
    if (!is_numeric($_POST["codeinput"])) {
      $codeerror .= " Üliõpilaskood võib sisaldada ainult numbreid!";
    }
    if(empty($codeerror)) {
      $code = intval($_POST["codeinput"]);
    }

    if (empty($codeerror)) {
      $exists = check($code);
    }

    if (empty($codeerror) and empty($exists)) {
      $notice .= " Sellist broneeringut kahjuks ei leitud!";
    }

    if (empty($codeerror)) {
        $existsdelete = checkdelete($code);
    }

    if (empty($codeerror) and !empty($existsdelete)) {
      $notice .= " Olete juba oma registreerumise tühistanud!";
    }

    if(empty($notice) and !empty($code)) {
      $notice = cancel($code);
      if ($notice == 1) {
        $notice = "Registreerumine tühistatud!";
      }
      else {
        $notice = "Registreerumise tühistamisel tekkis tõrge - palun proovige uuesti!";
      }
    }
    
  }

  require("header.php");
?>
  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Marilii Saar</h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise eksamil aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="reg.php">Registreeru peole</a></li>
    <li><a href="admin.php">Administraatori vaade</a></li>
  </ul>
  
  <hr>
  <h2>Tühista registreerumine</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="codeinput">Üliõpilaskood (6 numbrit)</label>
    <input type="number" name="codeinput" id="codeinput" placeholder="Üliõpilaskood" value="<?php echo $code; ?>" required>
    <span><?php echo $codeerror; ?></span>
    <br />
    <input type="submit" name="cancelsubmit" value="Tühista">
  </form>
  <span><?php echo $notice; ?></span>
  
</body>
</html>