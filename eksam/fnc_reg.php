<?php
	$database = "if20_marilii_sa_1";

	function register($firstname, $lastname, $code){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpreg (firstname, lastname, studentcode) VALUES (?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("ssi", $firstname, $lastname, $code);
		if($stmt->execute()){
			$notice = 1;
		} else {
			echo $stmt->error;
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function check($code) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpreg_id FROM vpreg WHERE studentcode = ?");
		echo $conn->error;
		$stmt->bind_param("i", $code);
		$stmt->bind_result($idfromdb);
		if($stmt->execute()){
			if($stmt->fetch()) {
				$notice = $idfromdb;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function checkreg($code) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpreg_id FROM vpreg WHERE studentcode = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $code);
		$stmt->bind_result($idfromdb);
		if($stmt->execute()){
			if($stmt->fetch()) {
				$notice = $idfromdb;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function checkdelete($code) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpreg_id FROM vpreg WHERE studentcode = ? AND deleted IS NOT NULL");
		echo $conn->error;
		$stmt->bind_param("i", $code);
		$stmt->bind_result($idfromdb);
		if($stmt->execute()){
			if($stmt->fetch()) {
				$notice = $idfromdb;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function readall($selected, $sortby = 0, $sortorder = 0) {
		if ($selected == null) {
			$selected = "%";
		}
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$SQLsentence = "SELECT vpreg_id, firstname, lastname, studentcode, paid FROM vpreg WHERE vpreg_id LIKE ? AND deleted IS NULL";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}
		if($sortby == 1) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY firstname DESC"); 
		  }
		  else {
			  $stmt = $conn->prepare($SQLsentence ." ORDER BY firstname"); 
		  }
		}
		if($sortby == 2) {
		  if($sortorder == 2) {
		    $stmt = $conn->prepare($SQLsentence ." ORDER BY lastname DESC"); 
		  }
		  else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY lastname"); 
		  }
		}
		if($sortby == 3) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY studentcode DESC"); 
		  }
		  else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY studentcode"); 
		  }
		}
		if($sortby == 4) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY paid DESC"); 
		  }
		  else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY paid"); 
		  }
		}
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_param("s", $selected);
		$stmt->bind_result($idfromdb, $firstname, $lastname, $code, $paid);
		$stmt->execute();
		$result = "";
		  while($stmt->fetch()) {
			  $result .= "\t<tr>\n\t\t<td>" .$firstname ."</td>\n\t\t<td> " .$lastname ."</td>\n\t\t<td>" .$code ."</td>\n\t\t<td>";
			  if ($paid != null) {
				  $result .= $paid;
			  } 
			  else {
				  $result .= '<a href="paid.php?code=' .$code .'">Märgi makstuks</a>';
			  }
			  $result .= "</td>\n\t</tr>\n";
		  }
		  if(!empty($result)) {
			  $notice .= '<table>'."\n\t<tr>\n\t\t<th>Eesnimi &nbsp;" .'<a href="?sortby=1&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=1&sortorder=2">&darr;</a></th>';
			  $notice .= "\n\t\t<th>Perekonnanimi &nbsp;" .'<a href="?sortby=2&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>';
			  $notice .= "\n\t\t<th>Üliõpilasnumber &nbsp;" .'<a href="?sortby=3&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=3&sortorder=2">&darr;</a></th>';
			  $notice .= "\n\t\t<th>Makstud &nbsp;" .'<a href="?sortby=4&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n\t</tr>\n";
			  $notice .= $result;
			  $notice .= "  </table>\n";
		  }
		  else {
			  $notice = "<p>Kahjuks pole keegi veel registreerunud!</p>";
		  }
		$stmt->close();
		$conn->close();
		return $notice;
	  }

	  function readdeleted() {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpreg_id, firstname, lastname, studentcode, paid FROM vpreg WHERE deleted IS NOT NULL");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_result($idfromdb, $firstname, $lastname, $code, $paid);
		$stmt->execute();
		$result = "";
		  while($stmt->fetch()) {
			  $result .= "\t<tr>\n\t\t<td>" .$firstname ."</td>\n\t\t<td> " .$lastname ."</td>\n\t\t<td>" .$code ."</td>\n\t\t<td>";
			  if ($paid == null) {
				  $result .= " - ";
			  } 
			  else {
				  $result .= '<a href="deletepaid.php?code=' .$code .'">Tühista makse</a>';
			  }
			  $result .= "</td>\n\t\t<td>" .'<a href="restore.php?code=' .$code .'">Taasta</a></td>' ."\n\t</tr>\n";
		  }
		  if(!empty($result)) {
			  $notice .= "<table>\n\t<tr>\n\t\t<th>Eesnimi</th>\n\t\t<th>Perekonnanimi</th>\n\t\t<th>Üliõpilasnumber</th>\n\t\t<th>Makstud</th>\n\t\t<th>Taasta broneering</th>\n\t</tr>\n\t" .$result ."  </table>";
		  }
		  else {
			  $notice = "<p>Keegi pole oma broneeringut tühistanud :)</p>";
		  }
		$stmt->close();
		$conn->close();
		return $notice;
	  }

	  function countreg() {
		$notice = array("count" => null, "paid" => null);
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(vpreg_id) FROM vpreg WHERE deleted IS NULL");
		echo $conn->error;
		$stmt->bind_result($count);
		if($stmt->execute()){
			if($stmt->fetch()) {				
				$notice["count"] = $count;
			}
		}
		$stmt->close();
		$stmt = $conn->prepare("SELECT COUNT(vpreg_id) FROM vpreg WHERE paid IS NOT NULL AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_result($paid);
		if($stmt->execute()){
			if($stmt->fetch()) {				
				$notice["paid"] = $paid;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function cancel($code) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("UPDATE vpreg SET deleted = now() WHERE studentcode = ?");
		echo $conn->error;
		$stmt->bind_param("i", $code);
		if($stmt->execute()){
			$notice = 1;
		} else {
			echo $stmt->error;
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function readalltoselect($selected) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpreg_id, firstname, lastname FROM vpreg WHERE deleted IS NULL");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_result($idfromdb, $firstname, $lastname);
		$stmt->execute();
		$regs = "";
			while($stmt->fetch()) {
				$regs .= "\t\t" .'<option value ="' .$idfromdb .'"';
				if($idfromdb == $selected) {
					$regs .= " selected";
				}
				$regs .= ">" .$firstname ." " .$lastname ."</option> \n";
			}
			if(!empty($regs)) {
				$notice = "\t" .'<select name="id">' ."\n";
				$notice .= "\t\t" .'<option value="" selected disabled>Vali õpilane</option>' ."\n";
				$notice .= "\t\t" .'<option value="%">Kõik õpilased</option>' ."\n";
				$notice .= $regs;
				$notice .= "\t</select>\n";
			}
			else {
				$notice = null;
			}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	  