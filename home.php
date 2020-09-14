<?php
  $username = "Marilii Saar";
  $fulltimenow = date("d.m.Y H:i:s");
  $hournow = date("H");
  $partofday = "vaba aeg";
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
  if($semesterpassed == 0) {
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
  
  // Kodutöö - terve päev + semester (pole alanud, õppetöö käib, õppetöö läbi + % õppetööst)
?>
<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> programmeerib veebi</title>

</head>
<body>
  <h1><?php echo $username; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p>Lehe avamise hetk: <?php echo $fulltimenow; ?>.</p>
  <p><?php echo "Praegu on " . $partofday . "."; ?></p>
  <p><?php echo "2020 sügissemester on " . $semesterdurationdays . " päeva pikk."; ?></p>
  <p><?php echo $semesterstatus ?><br />
  <?php echo "(Läbitud on " . number_format($semesterpercent, 2) . "% semestrist)"; ?></p>

</body>
</html>