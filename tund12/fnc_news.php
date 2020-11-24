<?php
	$database = "if20_marilii_sa_1";

	function storeNews($newstitle, $news, $expire){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("isss", $_SESSION["userid"], $newstitle, $news, $expire);
		if($stmt->execute()){
			$notice = 1;
		} else {
			//echo $stmt->error;
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function readLatestNews($limit = 5) {
		$photohtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT firstname, lastname, title, content FROM vpnews JOIN vpusers ON vpnews.userid = vpusers.vpusers_id WHERE expire >= CURDATE() AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("i", $limit);
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $titlefromdb, $contentfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			if ($stmt->fetch()) {
				$temphtml .= "<h4>" .$titlefromdb ."</h4>\n";
				$temphtml .= "<p>" .htmlspecialchars_decode($contentfromdb) ."</p>\n";
				$temphtml .= "<p>Autor: " .$firstnamefromdb ." " .$lastnamefromdb ."</p>\n";
			}
			if(!empty($temphtml)) {
				$newshtml = "<div> \n" .$temphtml ."\n</div>\n";
			}
			else {
				$newshtml = "<p>Kahjuks uudiseid ei leitud!</p>";
			}
		}
		else {
			$newshtml = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $newshtml;
	}