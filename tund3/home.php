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

  $username = "Marilii Saar";
  $fulltimenow = date("d.m.Y H:i:s");
  $hournow = date("H");
  $partofday = "vaba aeg";
  $weekdaynameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  // var_dump($weekdaynameset);
  $weekdaynow = date("N");

  
  if($hournow >= 22 and $hournow < 6) {
      $partofday = "uneaeg";
  } // 22 - 6
  if($hournow >= 6 and $hournow < 7.5) {
    $partofday = "hommikuste ettevalmistuste aeg";
  } // 6 - 7.30
  if($hournow >= 7.5 and $hournow < 8) {
    $partofday = "ühistranspordi aeg";
  } // 7.30 - 8
  if($hournow >= 8 and $hournow <= 16) {
	  $partofday = "ülikoolis õppimise aeg";
  } // 8 - 16
  if($hournow >= 16 and $hournow < 16.5) {
    $partofday = "ühistranspordi aeg";
  } // 16 - 16.30

  
  // vaatame semestri kulgemist
  $semesterstart = new DateTime("2020-8-31");
  $semesterend = new DateTime("2020-12-13");
  // $semestertest = new DateTime("2020-12-14"); // Katsetamiseks - üks päev peale semestri lõppu
  // $semestertest2 = new DateTime("2020-8-30"); // Katsetamiseks - üks päev enne semestri algust
  $semesterduration = $semesterstart->diff($semesterend);
  $semesterdurationdays = $semesterduration->format("%r%a");
  $today = new DateTime("now");
  $semesterpassed = $semesterstart->diff($today)->format("%r%a");
  $todayday = $semesterpassed + 1;
  $semesterpercent = $semesterpassed * 100 / $semesterdurationdays;
  if($semesterpercent < 0) {
    $semesterpercent = 0;
  }
  if($semesterpercent > 100) {
    $semesterpercent = 100;
  }
  $semesterstatus = "Õppetöö käib, semestri algusest on möödunud " . $semesterpassed . " päeva - täna on semestri " . $todayday . ". päev.";
  if($semesterpassed == 0) { // === ei toimi!
    $semesterstatus = "Õppetöö käib, täna on semestri esimene päev.";
  }
  if($semesterpassed < 0) {
    $semesterstatus = "Semester pole veel alanud.";
  }
  if($semesterpassed == $semesterdurationdays) {
    $semesterstatus = "Õppetöö käib, semestri algusest on möödunud " . $semesterpassed . " päeva - täna on semestri viimane päev.";
  }
  if($semesterpassed > $semesterdurationdays) {
    $semesterstatus = "Semester on läbi!";
  }
  
  // annan ette lubatud pildivormingute loendi
  $picfiletypes = ["image/jpeg", "image/png"];
  // loeme piltide kataloogi sisu ja näitame pilte
  $allfiles = array_slice(scandir("../vp_pics/"), 2);
  
  $picfiles = [];
  
  foreach($allfiles as $file) {
	$fileinfo = getImagesize("../vp_pics/" . $file);
	
	if(in_array($fileinfo["mime"], $picfiletypes)) {
		array_push($picfiles, $file);
	}
  }
  
  //paneme kõik pildid ekraanile
  $piccount = count($picfiles);
  $imghtml = "";
  for($i = 0; $i < $piccount; $i++) {
	$imghtml .= '<img src="../vp_pics/' . $picfiles[$i] . '" alt="Tallinna Ülikool">';
  }
  
  require("header.php");
  
?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $username; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p>Lehe avamise hetk: <?php echo $weekdaynameset[$weekdaynow - 1] .", " . $fulltimenow; ?>.</p>
  <p><?php echo "Praegu on " . $partofday . "."; ?></p>
  <p><?php echo "2020 sügissemester on " . $semesterdurationdays . " päeva pikk."; ?></p>
  <p><?php echo $semesterstatus ?><br />
  <?php echo "(Läbitud on " . number_format($semesterpercent, 2) . "% semestrist)"; ?></p>
  <hr />
  <?php echo $imghtml; ?>
  <hr />
  <form method="POST">
    <label>Sisesta oma pähe tulnud mõte!</label>
	<input type="text" name="ideainput" placeholder="Kirjuta siia mõte!">
	<input type="submit" name ="ideasubmit" value="Saada mõte ära!">
  </form>
  <hr />
  <?php echo $ideahtml; ?>

</body>
</html>