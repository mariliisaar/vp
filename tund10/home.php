<?php
  require("usesession.php");
  
  // Klassi testimine
  // require("classes/First_class.php");
  // $myclassobject = new First(10);
  // echo "Salajane arv on: " .$myclassobject->mybusiness;
  // echo " Avalik arv on: " .$myclassobject->everybodysbusiness;
  // $myclassobject->tellMe();
  // unset($myclassobject);

  // Tegelen küpsistega - cookies
  // setcookie funktsioon peab olema ENNE <html> elementi! (antud hetkel enne require(header))
  // küpsise nimi, väärtus, aegumistähtaeg (0 <- hävineb, kui brauser suletakse; 8600 - sekundeid ööpäevas), 
  // failitee (domeeni piires) kus küpsis kehtib, domeen kus küpsis kehtib, https kasutamine, ainult üle veebiliikluse?
  setcookie("vpvisitorname", $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"], time() + (86400 * 8), "/~marisaa/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
  $lastvisitor = null;
  if(isset($_COOKIE["vpvisitorname"])) {
    $lastvisitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitorname"] .".</p> \n";
  }
  else {
    $lastvisitor = "<p>Küpsiseid ei leitud, viimane külastaja pole teada </p> \n";
  }
  // küpsise kustutamine
  // kustutamiseks tuleb sama küpsis kirjutada minevikus aegumistähtajaga, näiteks time() - 3600
  
  require("header.php");
  
?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="page.php">Esilehele</a></li>
  <li><a href="filminfo.php">Loe filmiinfot</a></li>
	<li><a href="addfilminfo.php">Filmiinfo lisamine</a></li>
	<li><a href="movieconnect.php">Seoste lisamine</a></li>
	<li><a href="addquote.php">Tsitaadi lisamine</a></li>
	<li><a href="oldlinks.php">Vanad Failid</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
	<li><a href="photoupload.php">Galeriipiltide üleslaadimine</a></li>
  <li><a href="photogallery_public.php">Avalike fotode galerii</a></li>
  <li><a href="photogallery_private.php">Minu isiklike fotode galerii</a></li>
  <li><a href="photogallery_userpublic.php">Minu avalike fotode galerii</a></li>
  <li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  <h3>Viimane külastaja sellest arvutist: </h3>
  <?php 
    if(count($_COOKIE) > 0) {
      echo "<p>Küpsised on lubatud! Leiti: " .count($_COOKIE) ." küpsist</p>\n";
    }
    echo $lastvisitor; 
  ?>

</body>
</html>