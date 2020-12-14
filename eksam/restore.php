<?php
    require("../../../config.php");

    $database = "if20_marilii_sa_1";

    $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
    $stmt = $conn->prepare("UPDATE vpreg SET deleted = NULL WHERE studentcode = ?");
    $stmt->bind_param("i", $_REQUEST["code"]);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    header("Location: admin.php");