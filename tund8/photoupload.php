<?php

  require("usesession.php");
  require("../../../config.php");
  
  $inputerror = "";
  $notice = "";
  $filetype = null;
  $filesizelimit = 1572864; // 1048576
  $photouploaddir_orig = "../photoupload_orig/";
  $photouploaddir_normal = "../photoupload_normal/";
  $photouploaddir_thumb = "../photoupload_thumb/";
  $filenameprefix = "vp_";
  $filename = null;
  $photomaxwidth = 600;
  $photomaxheight = 400;
  $thumb = 100;
  
  // Kui klikiti submit, siis ...
  if(isset($_POST["photosubmit"])) {
	// var_dump($_POST);
	// var_dump($_FILES);
	
	// Kas on pilt ja mis tüüpi
	$check = getimagesize($_FILES["photoinput"]["tmp_name"]);
	if($check !== false) {
		// var_dump($check);
		if($check["mime"] == "image/jpeg") {
			$filetype = "jpg";
		}
		if($check["mime"] == "image/png") {
			$filetype = "png";
		}
		if($check["mime"] == "image/gif") {
			$filetype = "gif";
		}
	}
	else {
		$inputerror = "Valitud fail ei ole pilt! ";
	}
	
	// Kas on sobiva failisuurusega
	if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit) {
		$inputerror = "Liiga suur fail!";
	}
	
	// Loome uue failinime
	$timestamp = microtime(1) * 10000;
	$filename = $filenameprefix .$timestamp ."." .$filetype;
	
	
	// Ega fail äkki olemas ei ole
	if(file_exists($photouploaddir_orig .$filename)) {
		$inputerror = "Selle nimega fail on juba olemas!";
	}
	
	// Kui vigu pole ...
	if(empty($inputerror)) {
		$target = $photouploaddir_normal .$filename;
		$target_thumb = $photouploaddir_thumb .$filename;
		// Muudame suurust
		// Loome pikslikogumi, pildi objekti
		if($filetype == "jpg") {
			$mytempimage = imagecreatefromjpeg($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "png") {
			$mytempimage = imagecreatefrompng($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "gif") {
			$mytempimage = imagecreatefromgif($_FILES["photoinput"]["tmp_name"]);
		}
		// Teeme kindlaks originaalsuuruse
		$imagew = imagesx($mytempimage);
		$imageh = imagesy($mytempimage);

		// Thumbnaili alguspunktid ja mõõdud
		$thumbx = 0;
		$thumby = 0;
		$thumbw = $imagew;
		$thumbh = $imageh;

		if ($imagew > $imageh) {
			$thumbw = $imageh;
			$thumbx = round(($imagew - $thumbw) / 2);
		}
		else {
			$thumbh = $imagew;
			$thumby = round(($imageh - $thumbh) / 2);
		}	
		

		if($imagew > $photomaxwidth or $imageh > $photomaxheight) {
			if($imagew / $photomaxwidth > $imageh / $photomaxheight) {
				$photosizeratio = $imagew / $photomaxwidth;
			}
			else {
				$photosizeratio = $imageh / $photomaxheight;
			}
			// Arvutame uued mõõdud
			$neww = round($imagew / $photosizeratio);
			$newh = round($imageh / $photosizeratio);
			
			
			// Teeme uue pikslikogumi
			$mynewtempimage = imagecreatetruecolor($neww, $newh);
			$mynewthumbimage = imagecreatetruecolor($thumb, $thumb);
			// Kirjutame järelejäävad pikslid uuele pildile
			imagecopyresampled($mynewtempimage, $mytempimage, 0, 0, 0, 0, $neww, $newh, $imagew, $imageh);
			// Thumbnail
			imagecopyresampled($mynewthumbimage, $mytempimage, 0, 0, $thumbx, $thumby, $thumb, $thumb, $thumbw, $thumbh);
			// Salvestame faili
			$notice = saveimage($mynewtempimage, $filetype, $target, $target_thumb, $mynewthumbimage);
			imagedestroy($mynewtempimage);
			imagedestroy($mynewthumbimage);
		}
		else {
			// Kui pole suurust vaja muuta
			$mynewthumbimage = imagecreatetruecolor($thumb, $thumb);
			imagecopyresampled($mynewthumbimage, $mytempimage, 0, 0, $thumbx, $thumby, $thumb, $thumb, $thumbw, $thumbh);
			$notice = saveimage($mytempimage, $filetype, $target, $target_thumb, $mynewthumbimage);
		}
		imagedestroy($mytempimage);
		
		if(move_uploaded_file($_FILES["photoinput"]["tmp_name"], $photouploaddir_orig .$filename)) {
			$notice .= "Originaalpildi salvestamine õnnestus!";
		}
		else {
			$notice .= "Originaalpildi salvestamisel tekkis tõrge!";
		}
	}	
  }
  
  function saveimage($mynewtempimage, $filetype, $target, $target_thumb, $mynewthumbimage) {
	$notice = null;
	if($filetype == "jpg") {
		if(imagejpeg($mynewtempimage, $target, 90)) {
			$notice = "Vähendatud pildi salvestamine õnnestus! ";
			if(imagejpeg($mynewthumbimage, $target_thumb, 90)) {
				$notice .= "Thumbnaili salvestamine õnnestus! ";
			}
			else {
				$notice .= "Thumbnaili salvestamisel tekkis tõrge! ";
			}
		}
		else {
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge! ";
		}
	}
	if($filetype == "png") {
		if(imagepng($mynewtempimage, $target, 6)) {
			$notice = "Vähendatud pildi salvestamine õnnestus! ";
			if(imagejpeg($mynewthumbimage, $target_thumb, 6)) {
				$notice .= "Thumbnaili salvestamine õnnestus! ";
			}
			else {
				$notice .= "Thumbnaili salvestamisel tekkis tõrge! ";
			}
		}
		else {
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge! ";
		}
	}
	if($filetype == "gif") {
		if(imagegif($mynewtempimage, $target)) {
			$notice = "Vähendatud pildi salvestamine õnnestus! ";
			if(imagejpeg($mynewthumbimage, $target_thumb)) {
				$notice .= "Thumbnaili salvestamine õnnestus! ";
			}
			else {
				$notice .= "Thumbnaili salvestamisel tekkis tõrge! ";
			}
		}
		else {
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge! ";
		}
	}
	// imagedestroy($mynewtempimage);
	return $notice;
  }

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="home.php">Esilehele</a></li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<label for="photoinput">Vali pildifail!</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br />
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text">
	<br />
	<label>Privaatsustase</label>
	<br />
	<input id="privinput1" name="privinput" type="radio" value="1">
	<label for="privinput1">Privaatne (ainult ise näen)</label>
	<input id="privinput2" name="privinput" type="radio" value="2">
	<label for="privinput2">Klubi liikmetele (sisseloginud kasutajad näevad)</label>
	<input id="privinput3" name="privinput" type="radio" value="3">
	<label for="privinput3">Avalik (kõik näevad)</label>
	
	
	<br />
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