<?php
	$database = "if20_marilii_sa_1";

	function storePhotoData($filename, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["userid"], $filename, $alttext, $privacy);
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

	function storeNewsPhotoData($filename, $alttext){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES (?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
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

	function readNewestPublicPhoto() {
		$photohtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE vpphotos_id = (SELECT MAX(vpphotos_id) FROM vpphotos WHERE privacy = 3 AND deleted IS NULL);");
		echo $conn->error;
		$stmt->bind_result($filenamefromdb, $alttextfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			if ($stmt->fetch()) {
				$temphtml .= '<img src="' .$GLOBALS["photouploaddir_normal"] .$filenamefromdb .'" alt="' .$alttextfromdb .'">' ."\n";
			}
			if(!empty($temphtml)) {
				$photohtml = "<div> \n" .$temphtml ."\n</div>\n";
			}
			else {
				$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p>";
			}
		}
		else {
			$photohtml = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $photohtml;
	}

	function readPublicPhotoThumbs($privacy) {
		$photohtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext from vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenamefromdb, $alttextfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			while($stmt->fetch()) {
				//<img src="" alt="">
				$temphtml .= '<img src="' .$GLOBALS["photouploaddir_thumb"] .$filenamefromdb .'" alt="' .$alttextfromdb .'">' ."\n";
			}
			if(!empty($temphtml)) {
				$photohtml = "<div> \n" .$temphtml ."\n</div>\n";
			}
			else {
				$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p>";
			}
		}
		else {
			$photohtml = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $photohtml;
	}

	function readPublicPhotoThumbsPage($privacy, $limit, $page = 1) {
		$photohtml = null;
		$skip = ($page - 1) * $limit;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		// $stmt = $conn->prepare("SELECT filename, alttext from vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?");
		// $stmt = $conn->prepare("SELECT vpphotos_id, filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?, ?");
		$stmt = $conn->prepare("SELECT vpphotos.vpphotos_id, vpusers.firstname, vpusers.lastname, vpphotos.filename, vpphotos.alttext, AVG(vpphotoratings.rating) as AvgValue FROM vpphotos JOIN vpusers ON vpphotos.userid = vpusers.vpusers_id LEFT JOIN vpphotoratings ON vpphotoratings.photoid = vpphotos.vpphotos_id WHERE vpphotos.privacy >= ? AND deleted IS NULL GROUP BY vpphotos.vpphotos_id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($idfromdb, $firstnamefromdb, $lastnamefromdb, $filenamefromdb, $alttextfromdb, $avgfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			while($stmt->fetch()) {
				//<img src="" alt="">
				$temphtml .= "\t\t" .'<div class="thumbgallery">' ."\n";
				$temphtml .= "\t\t\t" .'<img src="' .$GLOBALS["photouploaddir_thumb"] .$filenamefromdb .'" alt="' .$alttextfromdb .'" class="thumbs" data-fn="' .$filenamefromdb .'" data-id="' .$idfromdb .'">' ."\n";
				$temphtml .= "\t\t\t<p>" .$firstnamefromdb ." " .$lastnamefromdb ."</p>\n";
				$temphtml .= "\t\t\t" .'<p id="score' .$idfromdb .'">';
				if($avgfromdb == 0) {
					$temphtml .= "Pole hinnatud";
				}
				else {
					$temphtml .= "Hinne: " .round($avgfromdb, 2);
				}
				$temphtml .= "</p>\n";
				$temphtml .= "\t\t</div>\n";
			}
			if(!empty($temphtml)) {
				$photohtml = '<div class="galleryarea" id="galleryarea">' ."\n" .$temphtml ."\t</div>\n";
			}
			else {
				$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p>";
			}
		}
		else {
			$photohtml = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $photohtml;
	}

	function readPrivatePhotoThumbsPage($privacymin, $privacymax, $limit, $page = 1) {
		$userid = $_SESSION["userid"];
		$photohtml = null;
		$skip = ($page - 1) * $limit;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE userid = ? AND privacy >= ? AND privacy <= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("iiiii", $userid, $privacymin, $privacymax, $skip, $limit);
		$stmt->bind_result($filenamefromdb, $alttextfromdb);
		if($stmt->execute()) {
			$temphtml = null;
			while($stmt->fetch()) {
				$temphtml .= "\t\t" .'<div class="thumbgallery">' ."\n";
				$temphtml .= "\t\t\t" .'<img src="' .$GLOBALS["photouploaddir_thumb"] .$filenamefromdb .'" alt="' .$alttextfromdb .'" class="thumbs">' ."\n";
				$temphtml .= "\t\t</div>\n";
			}
			if(!empty($temphtml)) {
				$photohtml = '<div class="galleryarea">' ."\n" .$temphtml ."\t</div>\n";
			}
			else {
				$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p>";
			}
		}
		else {
			$photohtml = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $photohtml;
	}

	function countPublicPhotos($privacy) {
		$photocount = 0;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(vpphotos_id) FROM vpphotos WHERE privacy >= ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($result);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$photocount = $result;
			}
		}
		else {
			$photocount = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $photocount;
	}

	function countPrivatePhotos($privacymin, $privacymax) {
		$userid = $_SESSION["userid"];
		$photocount = 0;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(vpphotos_id) FROM vpphotos WHERE userid = ? AND privacy >= ? AND privacy <= ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("iii", $userid, $privacymin, $privacymax);
		$stmt->bind_result($result);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$photocount = $result;
			}
		}
		else {
			$photocount = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $photocount;
	}