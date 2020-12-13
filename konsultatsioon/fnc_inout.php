<?php
	$database = "if20_marilii_sa_1";

	function storeIn($regnum, $weight){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpcars (regnum, inweight) VALUES (?, ?)");
		echo $conn->error;
		$stmt->bind_param("sd", $regnum, $weight);
		if($stmt->execute()){
			$notice = 1;
		} else {
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function storeOut($carid, $weight){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("UPDATE vpcars SET outweight = ? WHERE vpcars_id = ?");
		echo $conn->error;
		$stmt->bind_param("di", $weight, $carid);
		if($stmt->execute()){
			$notice = 1;
		} else {
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function checkinweight($regnum, $weight) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpcars_id FROM vpcars WHERE regnum = ? AND inweight IS NOT NULL AND outweight IS NULL");
		echo $conn->error;
		$stmt->bind_param("s", $regnum);
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

	function readtoselect($selected) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpcars_id, regnum FROM vpcars WHERE outweight IS NULL");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_result($idfromdb, $regnum);
		$stmt->execute();
		$cars = "";
		  while($stmt->fetch()) {
			  $cars .= '<label for="regnumout">Vali auto</label>' ."\n";
			  $cars .= '<option value ="' .$idfromdb .'"';
			  if($idfromdb == $selected) {
				  $cars .= " selected";
			  }
			  $cars .= ">" .$regnum ."</option> \n";
		  }
		  if(!empty($cars)) {
			  $notice = '<select name="regnumout">' ."\n";
			  $notice .= '<option value="" selected disabled>Vali auto</option>' ."\n";
			  $notice .= $cars;
			  $notice .= "</select> \n";
		  }
		  else {
			  $notice = null;
		  }
		$stmt->close();
		$conn->close();
		return $notice;
	  }

	  function readalltoselect($selected) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpcars_id, regnum FROM vpcars");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_result($idfromdb, $regnum);
		$stmt->execute();
		$cars = "";
		  while($stmt->fetch()) {
			  $cars .= '<label for="regnum">Vali auto</label>' ."\n";
			  $cars .= '<option value ="' .$idfromdb .'"';
			  if($idfromdb == $selected) {
				  $cars .= " selected";
			  }
			  $cars .= ">" .$regnum ."</option> \n";
		  }
		  if(!empty($cars)) {
			  $notice = '<select name="regnum">' ."\n";
			  $notice .= '<option value="" selected disabled>Vali auto</option>' ."\n";
			  $notice .= '<option value="%">Kõik autod</option>' ."\n";
			  $notice .= $cars;
			  $notice .= "</select> \n";
		  }
		  else {
			  $notice = null;
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
		$SQLsentence = "SELECT vpcars_id, regnum, inweight, outweight, (inweight - outweight) AS weight FROM vpcars WHERE vpcars_id LIKE ?";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}
		if($sortby == 1) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY regnum DESC"); 
		  }
		  else {
			  $stmt = $conn->prepare($SQLsentence ." ORDER BY regnum"); 
		  }
		}
		if($sortby == 2) {
		  if($sortorder == 2) {
		    $stmt = $conn->prepare($SQLsentence ." ORDER BY weight DESC"); 
		  }
		  else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY weight"); 
		  }
		}
		if($sortby == 3) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY inweight DESC"); 
		  }
		  else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY inweight"); 
		  }
		}
		if($sortby == 4) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY outweight DESC"); 
		  }
		  else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY outweight"); 
		  }
		}
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_param("s", $selected);
		$stmt->bind_result($idfromdb, $regnum, $inweight, $outweight, $weight);
		$stmt->execute();
		$cars = "";
		  while($stmt->fetch()) {
			  $cars .= "<tr><td>" .$regnum ."</td><td> ";
			  if ($outweight != null) {
				  $cars .= $weight ."</td><td>";
			  }
			  else {
				  $cars .= "- </td><td>";
			  }
			  $cars .= $inweight ."</td><td>" .$outweight ."</tr>";
		  }
		  if(!empty($cars)) {
			  $notice .= '<table><tr><th>Auto number &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=1&sortorder=2">&darr;</a></th>';
			  $notice .= '<th>Veetud vilja kogumass &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>';
			  $notice .= '<th>Sisenemismass &nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=3&sortorder=2">&darr;</a></th>';
			  $notice .= '<th>Väljumismass &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th></tr>';
			  $notice .= $cars;
			  $notice .= "</table>";
		  }
		  else {
			  $notice = "<p>Kahjuks autosid ei leitud</p>";
		  }
		$stmt->close();
		$conn->close();
		return $notice;
	  }