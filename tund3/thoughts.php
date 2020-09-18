<?php

// var_dump($_POST);
  require("../../../config.php");
  $database = "if20_marilii_sa_1";
  
  // loen lehele kõik olemasolevad mõtted
  $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
  $stmt = $conn->prepare("SELECT idea FROM myideas");
  echo $conn->error;
  // seome tulemuse muutujaga
  $stmt->bind_result($ideafromdb);
  $stmt->execute();
  $ideahtml = "";
  while($stmt->fetch()) {
	  $ideahtml .= "<p>" . $ideafromdb . "</p>";
  }
  $stmt->close();
  $conn->close();

  require("header.php");
  
  ?>

  <?php echo $ideahtml; ?>

  <hr>
    <a href="home.php">Tagasi</a>

</body>
</html>