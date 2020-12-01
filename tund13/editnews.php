<?php
  require("usesession.php");

  require("../../../config.php");
  require("../../../photo_config.php");
  require("fnc_news.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");

  $tolink = "\t" .'<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>' ."\n";
  $tolink .= "\t" .'<script>tinymce.init({selector:"textarea#newsinput", plugins: "link", menubar: "edit",});</script>' ."\n";

  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  
  $id = $_GET["id"];
  $newsdata = readNews($id);
  $inputerror = "";
  $notice = null;
  $news = $newsdata["content"];
  $newstitle = $newsdata["title"];
  $year = substr($newsdata["expire"], 0, 4);
  $month = substr($newsdata["expire"], 5, 2);
  $day = substr($newsdata["expire"], 8, 2);
  $filename = null;
  $alttext = $newsdata["alttext"];
  $newsimg = ""; //TODO!!
                    
  //kui klikiti submit, siis ...
  if(isset($_POST["newssubmit"])){
    if(strlen($_POST["newstitleinput"]) == 0) {
      $inputerror = "Uudise pealkiri on puudu!";
    }
    else {
      $newstitle = test_input($_POST["newstitleinput"]);
    }

    if(strlen($_POST["newsinput"]) == 0) {
      $inputerror .= " Uudise sisu on puudu!";
    }
    else {
      $news = test_inputNoFilter($_POST["newsinput"]);
    }

    if(!empty($_POST["dayinput"])) {
      $day = intval($_POST["dayinput"]);
    }

    if(!empty($_POST["monthinput"])) {
      $month = intval($_POST["monthinput"]);
    }

    if(!empty($_POST["yearinput"])) {
      $year = intval($_POST["yearinput"]);
    }

    if(checkdate($month, $day, $year)) {
      $tempdate = new DateTime($year ."-" .$month ."-" .$day);
      $expire = $tempdate->format("Y-m-d");
    }
    else {
      $inputerror .= " Kuupäev ei ole reaalne!";
    }

    if(!empty($_FILES["photoinput"]["name"])){
      $alttext = test_input($_POST["altinput"]);
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
    }
    
    if(empty($inputerror)) {
      if(!empty($_FILES["photoinput"]["name"])){
        // teeme pildi väiksemaks
        $myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
        // lisame vesimärgi
        $myphoto->addWatermark($watermark);
        // salvestame vähendatud pildi
        $result = $myphoto->saveimage($photouploaddir_news .$filename);
        if($result == 1){
          $notice .= " Pildi salvestamine õnnestus! ";
        } else {
          $inputerror .= " Pildi salvestamisel tekkis tõrge! ";
        }
        unset($myphoto);
      }

      if(empty($inputerror) and !empty($_FILES["photoinput"]["name"])){
        $result = storeNewsPhotoData($filename, $alttext);
        if($result == 1){
          $notice .= " Pildi info lisati andmebaasi!";
          $alttext = null;
        } else {
          $inputerror .= " Pildi info andmebaasi salvestamisel tekkis tõrge!";
          $filename = null;
        }
      }

      // uudis salvestada
      if(empty($inputerror)){
        $result = updateNews($id, $newstitle, $news, $expire, $filename);
        if($result == 1){
          $notice .= " Uudis uuendatud!";
        } else {
          $inputerror .= " Uudise uuendamisel tekkis tõrge!";
        }
      } else {
        $inputerror .= " Tekkinud vigade tõttu uudist ei uuendatud!";
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
    <li><a href="allnews.php">Tagasi uudiste juurde</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=" .$id;?>" enctype="multipart/form-data">
    <label for="newstitleinput">Uudise pealkiri</label>
    <input id="newstitleinput" name="newstitleinput" type="text" value="<?php echo $newstitle; ?>" required>
    <br>
    <label>Uudise aegumiskuupäev:</label>
    <br />
    <label for="dayinput">Päev: </label>
    <?php
      echo '<select name="dayinput" id="dayinput">' ."\n";
      echo "\t\t" .'<option value="" selected disabled>päev</option>' ."\n";
      for ($i = 1; $i < 32; $i ++){
        echo "\t\t" .'<option value="' .$i .'"';
        if ($i == $day){
          echo " selected ";
        }
        echo ">" .$i ."</option> \n";
      }
      echo "\t </select> \n";
    ?>
    <label for="monthinput">Kuu: </label>
    <?php
      echo '<select name="monthinput" id="monthinput">' ."\n";
      echo "\t\t" .'<option value="" selected disabled>kuu</option>' ."\n";
      for ($i = 1; $i < 13; $i ++){
        echo "\t\t" .'<option value="' .$i .'"';
        if ($i == $month){
          echo " selected ";
        }
        echo ">" .$monthnameset[$i - 1] ."</option> \n";
      }
      echo "\t </select> \n";
    ?>
    <label for="yearinput">Aasta: </label>
    <?php
      echo '<select name="yearinput" id="yearinput">' ."\n";
      echo "\t\t" .'<option value="" selected disabled>aasta</option>' ."\n";
      for ($i = date("Y"); $i <= date("Y") + 20; $i ++){
        echo "\t\t" .'<option value="' .$i .'"';
        if ($i == $year){
          echo " selected ";
        }
        echo ">" .$i ."</option> \n";
      }
      echo "\t </select> \n";
    ?>
    <br /><br />
    <label for="newsinput">Uudise sisu</label>
    <textarea id="newsinput" name="newsinput"><?php echo $news; ?></textarea>
    <br>
    <label for="photoinput">Uudisele lisatud pilt:</label>
    <?php echo $newsimg ?>
    <label for="photoinput">Muuda pilti:</label>
    <input id="photoinput" name="photoinput" type="file">
    <br>
    <label for="altinput">Pildi lühikirjeldus (alternatiivtekst)</label>
    <input id="altinput" name="altinput" type="text" value="<?php echo $alttext; ?>">
    <br>
    <input type="submit" id="newssubmit" name="newssubmit" value="Uuenda uudist">
  </form>
  <p id="notice">
  <?php
    echo $inputerror;
    echo $notice;
  ?>
  </p>
  
</body>
</html>