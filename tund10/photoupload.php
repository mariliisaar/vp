<?php
  require("usesession.php");

  require("../../../config.php");
  require("../../../photo_config.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");
    
  $inputerror = "";
  $notice = null;
  $filename = null;
  $privacy = 1;
  $alttext = null;
    
  //kui klikiti submit, siis ...
  if(isset($_POST["photosubmit"])){
	$privacy = intval($_POST["privinput"]);
	$alttext = test_input($_POST["altinput"]);
	//var_dump($_POST);
	//var_dump($_FILES);
	
	// võtame kasutusele klassi
	$myphoto = new Photoupload($_FILES["photoinput"]);
	
	// Kas on pilt
	if($myphoto->imageType($photoFileTypes) == 0){
		$inputerror = "Valitud fail ei ole pilt! ";
	}
	
	//kas on sobiva failisuurusega
	if(empty($inputerror) and $myphoto->getSize($filesizelimit) == 0){
		$inputerror = "Liiga suur fail!";
	}
	
	//anname failile nime
	$filename = $myphoto->setFilename();
	
	//ega fail äkki olemas pole
	if($myphoto->file_exists($photouploaddir_orig, $filename)){
		$inputerror = "Sellise nimega fail on juba olemas!";
	}
	
	//kui vigu pole ...
	if(empty($inputerror)){		
		// teeme pildi väiksemaks
		$myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
		// lisame vesimärgi
		$myphoto->addWatermark($watermark);
		// salvestame vähendatud pildi
		$result = $myphoto->saveimage($photouploaddir_normal .$filename);
		if($result == 1){
			$notice .= "Vähendatud pildi salvestamine õnnestus! ";
		} else {
			$inputerror .= "Vähendatud pildi salvestamisel tekkis tõrge! ";
		}
		
		//teeme pisipildi
		$myphoto->resizePhoto($thumbsize, $thumbsize);			
		$result = $myphoto->saveimage($photouploaddir_thumb .$filename);
		if($result == 1){
			$notice .= "Pisipildi salvestamine õnnestus! ";
		} else {
			$inputerror .= "Pisipildi salvestamisel tekkis tõrge! ";
		}
		
		//salvestame originaalpildi
		if(empty($inputerror)){
			$result = $myphoto->saveOriginal($photouploaddir_orig .$filename);
			if($result == 1){
				$notice .= "Originaalfaili üleslaadimine õnnestus! ";
			} else {
				$inputerror .= "Originaalfaili üleslaadimisel tekkis tõrge! ";
			}
		}
		// eemaldan klassi
		unset($myphoto);
		
		if(empty($inputerror)){
			$result = storePhotoData($filename, $alttext, $privacy);
			if($result == 1){
				$notice .= "Pildi info lisati andmebaasi!";
				$privacy = 1;
				$alttext = null;
			} else {
				$inputerror .= "Pildi info andmebaasi salvestamisel tekkis tõrge!";
			}
		} else {
			$inputerror .= "Tekkinud vigade tõttu pildi andmeid ei salvestatud!";
		}
		
	}
  }  

  require("header.php");
?>
  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="photoinput">Vali pildifail!</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br>
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text" value="<?php echo $alttext; ?>">
	<br>
	<label>Privaatsustase</label>
	<br>
	<input id="privinput1" name="privinput" type="radio" value="1" <?php if($privacy == 1){echo " checked";} ?>>
	<label for="privinput1">Privaatne (ainult ise näen)</label>
	<input id="privinput2" name="privinput" type="radio" value="2" <?php if($privacy == 2){echo " checked";} ?>>
	<label for="privinput2">Klubi liikmetele (sisseloginud kasutajad näevad)</label>
	<input id="privinput3" name="privinput" type="radio" value="3" <?php if($privacy == 3){echo " checked";} ?>>
	<label for="privinput3">Avalik (kõik näevad)</label>
	<br>	
	<input type="submit" name="photosubmit" value="Lae foto üles">
  </form>
  <p>
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </p>
  
</body>
</html>