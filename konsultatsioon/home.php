<?php
  require("usesession.php");

  require("../../../config.php");
  require("fnc_inout.php");
  require("fnc_common.php");
    
  $inputerror = "";
  $selected = null;
  $result = readall($selected);
  $sortby = 0;
  $sortorder = 0;
    
  //kui klikiti submit, siis ...
  if (isset($_POST["selectsubmit"])){
    if (strlen($_POST["regnum"]) == 0) {
      $inputerror = "Auto registrinumber on puudu!";
    }
    else {
      $selected = test_input($_POST["regnum"]);
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

  $carselect = readalltoselect($selected);
  if ($carselect == null) {
    $carselect = "<p>Autosid ei leitud</p>";
  }
  else {
    $carselect .= "<input type=" .'"submit" id="selectsubmit" name="selectsubmit" value="Filtreeri">' ."\n";
  }

  require("header.php");
?>
  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja!</a></li>
    <li><a href="insert.php">Autode sisenemine / väljumine</a></li>
    <li><a href="../tund13/home.php">Pealeht</a></li>
  </ul>
  
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <?php
    echo $carselect;
    echo $inputerror;
  ?>
  </form>
  <?php
    echo $result;
  ?>
  </p>
  
</body>
</html>