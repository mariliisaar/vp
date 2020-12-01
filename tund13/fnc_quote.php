<?php
  $database = "if20_marilii_sa_1";
  
  function readroletoselect($selectedrole) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT person_in_movie_id, first_name, last_name, title, role FROM person_in_movie JOIN person ON person_in_movie.person_id = person.person_id JOIN movie ON person_in_movie.movie_id = movie.movie_id ORDER BY title, role");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($idfromdb, $firstnamefromdb, $lastnamefromdb, $titlefromdb, $rolefromdb);
	$stmt->execute();
	$roles = "";
	  while($stmt->fetch()) {
		  if(!empty($rolefromdb)) {
			$roles .= '<option value ="' .$idfromdb .'"';
			if($idfromdb == $selectedrole) {
				$roles .= " selected";
			}
			$roles .= ">" .$titlefromdb ." - " .$rolefromdb ." (" .$firstnamefromdb ." " .$lastnamefromdb .")" ."</option> \n";
		  }
	  }
	  if(!empty($roles)) {
		  $notice = '<select name="roleinput">' ."\n";
		  $notice .= '<option value="" selected disabled>Vali roll filmis</option>' ."\n";
		  $notice .= $roles;
		  $notice .= "</select> \n";
	  }
	$stmt->close();
	$conn->close();
	return $notice;
  } // readroletoselect lõpeb

  function storenewquote($selectedrole, $quote) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT quote_id FROM quote WHERE quote_text = ? AND person_in_movie_id = ?");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("si", $quote, $selectedrole);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()) {
		$notice = "Selline tsitaat on juba olemas!";
	}
	else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO quote (quote_text, person_in_movie_id) VALUES(?, ?)");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_param("si", $quote, $selectedrole);
		if($stmt->execute()) {
			$notice = "Salvestatud!";
		}
		else {
			$notice = "Tsitaadi salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
	return $notice;
  } // storenewquote lõpeb
