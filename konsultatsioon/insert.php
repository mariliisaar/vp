<?php
  require("usesession.php");

  require("../../../config.php");
  require("fnc_inout.php");
  require("fnc_common.php");
    
  $ininputerror = "";
  $outinputerror = "";
  $innotice = null;
  $outnotice = null;
  $regnumin = null;
  $regnumout = null;
  $inweight = null;
  $outweight = null;
  $selected = "";
    
  //kui klikiti insubmit, siis ...
  if (isset($_POST["insubmit"])){
    if (strlen($_POST["regnumin"]) == 0) {
      $ininputerror = "Auto registrinumber on puudu!";
    }
    else {
      $regnumin = test_input($_POST["regnumin"]);
      $regnumout = $regnumin;
    }

    if (strlen($_POST["inweight"]) == 0) {
      $ininputerror .= " Sisenemismass on puudu!";
    }
    else {
      if (is_numeric($_POST["inweight"])) {
        $inweight = $_POST["inweight"];
      }
      else {
        $ininputerror .= " Sisenemismass peab olema number!";
      }
    }

    $exists = checkinweight($regnumin, $inweight);

    if (empty($ininputerror) and !empty($exists)) {
      $ininputerror .= " See auto on juba sisenenud!";
    }
    
    if (empty($ininputerror)) {
      // sisenemine salvestada
      $result = storeIn($regnumin, $inweight);
      if ($result == 1){
        $innotice .= " Sisenemismass salvestatud!";
        $regnumin = null;
        $inweight = null;
      } else {
        $ininputerror .= " Sisenemismassi salvestamisel tekkis tõrge!";
      }
    } else {
      $ininputerror .= " Tekkinud vigade tõttu sisenemismassi ei salvestatud!";
    }
  }  

  //kui klikiti outsubmit, siis ...
  if (isset($_POST["outsubmit"])){
    if (!empty($_POST["regnumout"])) {
      $regnumout = intval($_POST["regnumout"]);
    }
    else {
      $outinputerror = "Vali auto registrinumber!";
    }

    if (strlen($_POST["outweight"]) == 0) {
      $outinputerror .= " Väljumismass on puudu!";
    }
    else {
      if (is_numeric($_POST["outweight"])) {
        $outweight = $_POST["outweight"];
      }
      else {
        $outinputerror .= " Väljumismass peab olema number!";
      }
    }
    
    if (empty($outinputerror)) {
      // sisenemine salvestada
      $result = storeOut($regnumout, $outweight);
      if ($result == 1){
        $outnotice .= " Väljumismass salvestatud!";
        $regnumout = null;
        $outweight = null;
      } else {
        $outinputerror .= " Väljumismassi salvestamisel tekkis tõrge!";
      }
    } else {
      $outinputerror .= " Tekkinud vigade tõttu väljumismassi ei salvestatud!";
    }
  }

  $carselect = readtoselect($selected);
  if ($carselect == null) {
    $carselect = "<p>Väljuvaid autosid ei leitud</p>";
  }
  else {
    $carselect .= "<br />\n
    <label for=" .'"outweight">Auto väljumismass (t)</label>' ."\n
    <input id=" .'"outweight" name="outweight" type="number" step="0.01" value="<?php echo $outweight; ?>" required>' ."\n
    <br />
    <input type=" .'"submit" id="outsubmit" name="outsubmit" value="Salvesta väljumismass">' ."\n";
  }

  require("header.php");
?>
  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja!</a></li>
    <li><a href="home.php">Kokkuvõte</a></li>
    <li><a href="../tund13/home.php">Pealeht</a></li>
  </ul>
  
  <hr>
  <h2>Auto sisenemine</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="regnumin">Auto registrinumber</label>
    <input id="regnumin" name="regnumin" type="text" value="<?php echo $regnumin; ?>" required>
    <br />
    <label for="inweight">Auto sisenemismass (t)</label>
    <input id="inweight" name="inweight" type="number" step="0.001" value="<?php echo $inweight; ?>" required>
    <br />
    <input type="submit" id="insubmit" name="insubmit" value="Salvesta sisenemismass">
  </form>
  <p id="innotice">
  <?php
    echo $ininputerror;
    echo $innotice;
  ?>
  </p>
  <h2>Auto väljumine</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php echo $carselect; ?>
  </form>
  <p id="outnotice">
  <?php
    echo $outinputerror;
    echo $outnotice;
  ?>
  </p>
  
</body>
</html>