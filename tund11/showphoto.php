<?php
    require("usesession.php");
    require("../../../config.php");
    require("../../../photo_config.php");

    $database = "if20_marilii_sa_1";

    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT filename, userid, privacy FROM vpphotos WHERE vpphotos_id = ?");
    echo $conn->error;
    $stmt->bind_param("i", $_REQUEST["photo"]);
    $stmt->bind_result($filenamefromdb, $userfromdb, $privacyfromdb);
    if($stmt->execute()) {
        if ($stmt->fetch()) {
            if ($privacyfromdb <= 1 and $userfromdb != $_SESSION["userid"]) {
                return;
            }
            else {
                $check = getimagesize($photouploaddir_normal .$filenamefromdb);
                if($check["mime"] == "image/jpeg" or $check["mime"] == "image/png" or $check["mime"] == "image/gif"){
                    header("Content-type: " .$check["mime"]);
                    readfile($photouploaddir_normal .$filenamefromdb);
                }
            }
        }
    }