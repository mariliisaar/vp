<?php
  require("../../../config.php");
  require("fnc_reg.php");
  require("fnc_common.php");
    
  $inputerror = "";
  $selected = null;
  $result = readall($selected);
  $cancelled = readdeleted();
  $sortby = 0;
  $sortorder = 0;
    
  if (isset($_POST["selectsubmit"])){
    if (strlen($_POST["id"]) == 0) {
      $inputerror = "Õpilane pole valitud!";
    }
    else {
      $selected = test_input($_POST["id"]);
    }    
  }

  if(isset($_GET["sortby"]) and isset($_GET["sortorder"])) {
    if($_GET["sortby"] >= 1 and $_GET["sortby"] <= 4) {
      $sortby = $_GET["sortby"];
    }
    if($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2) {
      $sortorder = $_GET["sortorder"];
    }
  }
  if (empty($inputerror)) {
    $result = readall($selected, $sortby, $sortorder);
  }

  $studentselect = readalltoselect($selected);
  if ($studentselect == null) {
    $studentselect = "<p>Registreerituid ei leitud</p>";
  }
  else {
    $studentselect .= "\t<input type=" .'"submit" id="selectsubmit" name="selectsubmit" value="Filtreeri">' ."\n";
  }

  require("header.php");
?>
  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Marilii Saar</h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="reg.php">Registreerumine</a></li>
  </ul>
  
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <?php
    echo $studentselect;
    echo $inputerror;
  ?>
  </form>
  <h2>Aktiivsed broneeringud:</h2>
  <?php
    echo $result;
  ?>
  <h2>Tühistatud broneeringud:</h2>
  <?php
    echo $cancelled;
  ?>
  
</body>
</html>