<?php
    require("usesession.php");
    require("../../../config.php");
    require("../../../photo_config.php");

    $database = "if20_marilii_sa_1";

    $type = "image/png";
    $output= "../img/wrong.png";

    $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
    $stmt = $conn->prepare("SELECT filename, userid, privacy FROM vpphotos WHERE vpphotos_id = ? AND deleted IS NULL");
    $stmt->bind_param("i", $_REQUEST["photo"]);
    $stmt->bind_result($filenamefromdb, $userfromdb, $privacyfromdb);
    if($stmt->execute()) {
        if ($stmt->fetch()) {
            if ($userfromdb == $_SESSION["userid"] or $privacyfromdb >= 2) {
                $output = $photouploaddir_normal .$filenamefromdb;
                $check = getimagesize($output);
                if($check["mime"] == "image/jpeg" or $check["mime"] == "image/png" or $check["mime"] == "image/gif"){
                    $type = $check["mime"];
                }
            }
            else {
                $type = "image/png";
                $output= "../img/abort.png";
            }
        }
    }
    $stmt->close();
    $conn->close();
    header("Content-type: " .$type);
    readfile($output);