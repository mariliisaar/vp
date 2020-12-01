<?php
	$database = "if20_marilii_sa_1";

	function storeNews($newstitle, $news, $expire, $filename){
		$notice = null;
		$photoid = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		if ($filename != null) {
			$stmt = $conn->prepare("SELECT vpnewsphotos_id FROM vpnewsphotos WHERE filename = ? AND deleted IS NULL");
			echo $conn->error; // <-- ainult õppimise jaoks!
			$stmt->bind_param("s", $filename);
			$stmt->bind_result($idfromdb);
			$stmt->execute();
			if($stmt->fetch()) {
				$photoid = $idfromdb;
			}
			else {
				$notice = 0;
			}
			$stmt->close();
		}
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire, photoid) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("isssi", $_SESSION["userid"], $newstitle, $news, $expire, $photoid);
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

	function updateNews($newsid, $newstitle, $news, $expire, $filename){
		$notice = null;
		$photoid = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		// if ($filename != null) {
		// 	$stmt = $conn->prepare("SELECT vpnewsphotos_id FROM vpnewsphotos WHERE filename = ? AND deleted IS NULL");
		// 	echo $conn->error; // <-- ainult õppimise jaoks!
		// 	$stmt->bind_param("s", $filename);
		// 	$stmt->bind_result($idfromdb);
		// 	$stmt->execute();
		// 	if($stmt->fetch()) {
		// 		$photoid = $idfromdb;
		// 	}
		// 	else {
		// 		$notice = 0;
		// 	}
		// 	$stmt->close();
		// }
		// $stmt = $conn->prepare("UPDATE vpnews SET title = ?, content = ?, expire = ?, photoid = ?, modified = ?, modified_by = ? WHERE vpnews_id = ?");
		$stmt = $conn->prepare("UPDATE vpnews SET title = ?, content = ?, expire = ?, modified = now(), modified_by = ? WHERE vpnews_id = ?");
		echo $conn->error;
		// $stmt->bind_param("sssisii", $newstitle, $news, $expire, $photoid, $modified, $_SESSION["userid"], $newsid);
		$stmt->bind_param("sssii", $newstitle, $news, $expire, $_SESSION["userid"], $newsid);
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
		$stmt = $conn->prepare("SELECT firstname, lastname, title, content, vpnewsphotos_id, alttext FROM vpnews JOIN vpusers ON vpnews.userid = vpusers.vpusers_id LEFT JOIN vpnewsphotos ON vpnews.photoid = vpnewsphotos.vpnewsphotos_id WHERE expire >= CURDATE() AND vpnews.deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("i", $limit);
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $titlefromdb, $contentfromdb, $photoidfromdb, $alttextfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			while ($stmt->fetch()) {
				$temphtml .= "<h4>" .$titlefromdb ."</h4>\n";
				if ($photoidfromdb != null) {
					$temphtml .= '<img src="' ."shownewsphoto.php?photo=" .$photoidfromdb .'" alt="' .$alttextfromdb .'">' ."\n";
				}
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

	function readNewsEdit() {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpnews.vpnews_id, firstname, lastname, title, content, vpnewsphotos_id, alttext, expire, vpnews.added, modified FROM vpnews JOIN vpusers ON vpnews.userid = vpusers.vpusers_id LEFT JOIN vpnewsphotos ON vpnews.photoid = vpnewsphotos.vpnewsphotos_id WHERE expire >= CURDATE() AND vpnews.deleted IS NULL ORDER BY vpnews_id ASC");
		echo $conn->error;
		$stmt->bind_result($newsidfromdb, $firstnamefromdb, $lastnamefromdb, $titlefromdb, $contentfromdb, $photoidfromdb, $alttextfromdb, $expirefromdb, $addedfromdb, $modifiedfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			while ($stmt->fetch()) {
				$temphtml .= "\t" .'<div class="newscontainer">' ."\n";
				if ($photoidfromdb != null) {
					$temphtml .= "\t\t" .'<div class="newsimg">' ."\n";
					$temphtml .= "\t\t\t" .'<img src="' ."shownewsphoto.php?photo=" .$photoidfromdb .'" alt="' .$alttextfromdb .'">' ."\n";
					$temphtml .="\t\t</div>\n";
				}
				$temphtml .= "\t\t" .'<div class="newscontent">' ."\n";
				$temphtml .= "\t\t\t" .'<div class="newsmain">' ."\n";
				$temphtml .= "\t\t\t\t" .'<h3 class="newstitle">' .$titlefromdb ."</h3>\n";
				$temphtml .= "\t\t\t\t" .'<p class="created">(Lisatud: ' .$addedfromdb;
				if(!empty($modifiedfromdb)) {
					$temphtml .= ", Viimati muudetud: " .$modifiedfromdb;
				}				
				$temphtml .= ")</p>\n";
				$temphtml .= "\t\t\t\t" .htmlspecialchars_decode($contentfromdb) ."\n";
				$temphtml .="\t\t\t</div>\n";
				$temphtml .= "\t\t\t" .'<div class="newsmeta">' ."\n";
				$temphtml .= "\t\t\t\t<p>Uudis aegub: " .$expirefromdb ."</p>\n";
				$temphtml .= "\t\t\t\t<p>Autor: " .$firstnamefromdb ." " .$lastnamefromdb ."</p>\n";
				$temphtml .="\t\t\t</div>\n";
				$temphtml .="\t\t\t" .'<a href="editnews.php?id=' .$newsidfromdb .'">' ."Muuda uudist</a>\n";
				$temphtml .="\t\t</div>\n";
				$temphtml .="\t</div>\n";
			}
			if(!empty($temphtml)) {
				$newshtml = "<div>\n" .$temphtml ."\n</div>\n";
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

	function readNews($id) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT firstname, lastname, title, content, vpnewsphotos_id, alttext, expire, vpnews.added, modified FROM vpnews JOIN vpusers ON vpnews.userid = vpusers.vpusers_id LEFT JOIN vpnewsphotos ON vpnews.photoid = vpnewsphotos.vpnewsphotos_id WHERE vpnews.vpnews_id = ? AND vpnews.deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $titlefromdb, $contentfromdb, $photoidfromdb, $alttextfromdb, $expirefromdb, $addedfromdb, $modifiedfromdb);
		if($stmt->execute()) {
			$datafromdb = null;
			if ($stmt->fetch()) {
				$datafromdb = array("firstname"=>$firstnamefromdb, "lastname"=>$lastnamefromdb, "title"=>$titlefromdb, "content"=>$contentfromdb, "photoid"=>$photoidfromdb, "alttext"=>$alttextfromdb, "expire"=>$expirefromdb, "added"=>$addedfromdb, "modified"=>$modifiedfromdb);
			}
			if(!empty($datafromdb)) {
				$return = $datafromdb;
			}
			else {
				$return = "<p>Kahjuks uudiseid ei leitud!</p>";
			}
		}
		else {
			$return = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $return;
	}