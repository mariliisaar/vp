<?php

// var_dump($_POST);
  require("../../../config.php");
  $database = "if20_marilii_sa_1";
  
  // kui on idee sisestatud ja nuppu vajutatud, salvestame selle andmebaasi
  if(isset($_POST["ideasubmit"]) and !empty($_POST["ideainput"])) {
	  $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
	  // valmistan ette SQL käsu
	  $stmt = $conn->prepare("INSERT INTO myideas(idea) VALUES(?)");
	  echo $conn->error;
	  // seome käsuga päris andmed
	  // i - integer, d - decimal, s - string
	  $stmt->bind_param("s", $_POST["ideainput"]);
	  $stmt->execute();
	  echo $stmt->error;
	  $stmt->close();
	  $conn->close();
  }

  require("header.php");
  
  ?>
  <form method="POST">
    <label>Sisesta oma pähe tulnud mõte!</label>
	<input type="text" name="ideainput" placeholder="Kirjuta siia mõte!">
	<input type="submit" name ="ideasubmit" value="Saada mõte ära!">
  </form>

  <hr>
  <a href="home.php">Tagasi</a>

  </body>
</html>