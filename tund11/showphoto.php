<?php
    require("usesession.php");
    // require("../../../config.php");
    require("../../../photo_config.php");

    // $check = 
    // header("Content-type: " .$check["mime"]);
    header("Content-type: image/jpeg");
    readfile($photouploaddir_normal .$_REQUEST["photo"]);