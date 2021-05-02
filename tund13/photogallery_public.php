<?php
  require("usesession.php");

  require("../../../config.php");
  require("../../../photo_config.php");
  require("fnc_photo.php");

  $tolink = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
  $tolink .= '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
  $tolink .= '<script src="javascript/modal.js" defer></script>' ."\n";

  $gallerypagelimit = 10;
  $page = 1;
  $photocount = countPublicPhotos(2);
  if(!isset($_GET["page"]) or $_GET["page"] < 1) {
    $page = 1;
  }
  else if(round($_GET["page"] - 1) * $gallerypagelimit >= $photocount) {
    $page = ceil($photocount / $gallerypagelimit);
  }
  else {
    $page = $_GET["page"];
  }


  // $publicphotothumbshtml = readPublicPhotoThumbs(2);
  $publicphotothumbshtml = readPublicPhotoThumbsPage(2, $gallerypagelimit, $page);
    
  $notice = null;
    

  require("header.php");
?>
<main>
  <div class="container">
    <div class="banner">
      <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
    </div>
    <div class="name">
      <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
    </div>
    <div class="disclaimer">
      <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
      <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    </div>
    <div class="mobile_nav">
      <div class="mobile_links" id="mobile_links">
        <a href="page.php">Esilehele</a>
        <a href="filminfo.php">Loe filmiinfot</a>
        <a href="addfilminfo.php">Filmiinfo lisamine</a>
        <a href="movieconnect.php">Seoste lisamine</a>
        <a href="addquote.php">Tsitaadi lisamine</a>
        <a href="oldlinks.php">Vanad Failid</a>
        <a href="userprofile.php">Minu kasutajaprofiil</a>
        <a href="photoupload.php">Galeriipiltide üleslaadimine</a>
        <a href="photogallery_public.php">Avalike fotode galerii</a>
        <a href="photogallery_private.php">Minu isiklike fotode galerii</a>
        <a href="photogallery_userpublic.php">Minu avalike fotode galerii</a>
        <a href="addnews.php">Uudise lisamine</a>
        <a href="allnews.php">Uudise muutmine / kustutamine</a>
        <a href="../konsultatsioon/home.php">Viljaveo kokkuvõte</a>
        <a href="../konsultatsioon/insert.php">Viljaveo sisestamine</a>
        <a href="?logout=1">Logi välja!</a>
      </div>
      <a href="javascript:void(0);" class="icon" onclick="toggle_links()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <nav id="navbar">
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
        <li><a href="addnews.php">Uudise lisamine</a></li>
        <li><a href="allnews.php">Uudise muutmine / kustutamine</a></li>
        <li><a href="../konsultatsioon/home.php">Viljaveo kokkuvõte</a></li>
        <li><a href="../konsultatsioon/insert.php">Viljaveo sisestamine</a></li>
        <li><a href="?logout=1">Logi välja!</a></li>
      </ul>
    </nav>

    <!-- Modaalaken fotogalerii jaoks -->
    <div id="modalarea" class="modalarea">
      <!-- sulgemisnupp -->
      <span id="modalclose" class="modalclose">&times;</span>
      <!-- pildikoht -->
      <div class="modalhorizontal">
        <div class="modalvertical">
          <p id="modalcaption"></p>
          <img id="modalimg" src="../img/empty.png" alt="Galeriipilt">
          <br>
          <div id="rating" class="modalRating">
            <label><input id="rate1" name="rating" type="radio" value="1">1</label>
            <label><input id="rate2" name="rating" type="radio" value="2">2</label>
            <label><input id="rate3" name="rating" type="radio" value="3">3</label>
            <label><input id="rate4" name="rating" type="radio" value="4">4</label>
            <label><input id="rate5" name="rating" type="radio" value="5">5</label>
            <button id="storeRating">Salvesta hinnang!</button>
            <br>
            <p id="avgRating"></p>
          </div>
        </div>
      </div>
    </div>
    
    <hr id="nav_hr" class="nav_hr">
    <div class="main_title">
      <h2>Fotogalerii</h2>
    </div>
    <div class="main_section">
      <div class="gallery_nav">
        <p>
          <?php
            if($page > 1) {
              echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span>' ."\n";
            }
            else {
              echo "<span>Eelmine leht</span>\n";
            }
          ?>
        </p>
        <p>
          <?php
            if($page * $gallerypagelimit < $photocount) {
              echo "\t" .'<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
            }
            else {
              echo "\t<span>Järgmine leht</span>\n";
            }
          ?>
        </p>
      </div>
      <div class="gallery">
        <?php
          echo $publicphotothumbshtml;
        ?>
      </div>
    </div>
  </div>
</main>
  
</body>
</html>