<?php
    require("usesession.php");
    require("../../../config.php");
    require("../../../photo_config.php");

    $database = "if20_marilii_sa_1";

    $newsid = $_GET["id"];

    $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
    $stmt = $conn->prepare("UPDATE vpnews SET deleted = now(), deleted_by = ? WHERE vpnews_id = ?");
    echo $conn->error;
    $stmt->bind_param("ii", $_SESSION["userid"], $newsid);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: allnews.php");