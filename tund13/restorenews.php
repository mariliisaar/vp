<?php
    require("usesession.php");
    require("../../../config.php");
    require("../../../photo_config.php");

    $database = "if20_marilii_sa_1";

    $newsid = $_GET["id"];
    $edit = $_GET["e"];

    $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
    $stmt = $conn->prepare("UPDATE vpnews SET deleted = NULL, deleted_by = NULL WHERE vpnews_id = ?");
    echo $conn->error;
    $stmt->bind_param("i", $newsid);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    if ($edit == "true") {
        $location = 'Location: editnews.php?id=' .$newsid;
        header($location);
    }
    else {
        header("Location: allnews.php");
    }