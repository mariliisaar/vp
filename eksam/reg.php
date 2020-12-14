<?php
  require("../../../config.php");
  require("fnc_reg.php");
  require("fnc_common.php");

  $firstname = "";
  $lastname = "";
  $code = null;

  $firstnameerror = "";
  $lastnameerror = "";
  $codeerror = "";

  $exists = "";
  $notice = "";

  $data = countreg();
  $registered = $data["count"];
  $paid = $data["paid"];
    
    
  if (isset($_POST["regsubmit"])){
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


    $exists = checkreg($code);

    if (empty($firstnameerror) and empty($lastnameerror) and empty($codeerror) and !empty($exists)) {
      $notice .= " Olete juba registreerunud!";
    }

    if(empty($notice) and !empty($firstname) and !empty($lastname) and !empty($code)) {
      $notice = register($firstname, $lastname, $code);
      if ($notice == 1) {
        $notice = "Registreerumine õnnestus!";
      }
      else {
        $notice = "Registreerumisel tekkis tõrge - palun proovige uuesti!";
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
    <li><a href="admin.php">Administraatori vaade</a></li>
  </ul>
  
  <hr>
  <h2>Peole registreerumine</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="firstnameinput">Eesnimi</label>
    <input type="text" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>" required>
    <span><?php echo $firstnameerror; ?></span>
    <br />
    <label for="lastnameinput">Perekonnanimi</label>
    <input type="text" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>" required>
    <span><?php echo $lastnameerror; ?></span>
    <br />
    <label for="codeinput">Üliõpilaskood (6 numbrit)</label>
    <input type="number" name="codeinput" id="codeinput" placeholder="Üliõpilaskood" value="<?php echo $code; ?>" required>
    <span><?php echo $codeerror; ?></span>
    <br />
    <input type="submit" name="regsubmit" value="Registreeru">
  </form>
  <span><?php echo $notice; ?></span>
  <h2>Peole registreerunute kokkuvõte</h2>
  <p>Peole on praeguseks registreerunud <?php echo $registered; ?> inimest, kellest <?php echo $paid; ?> on kindlasti tulemas (maksnud).</p>
  <p><a href="cancel.php">Tühista enda registreerumine</a></p>
  
</body>
</html>