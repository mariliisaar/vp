<?php
  $database = "if20_marilii_sa_1";
//   $database = "if20_inga_filmibaas_E";
  
  // Funktsioon, mis loeb kõikide filmide info
  function readfilms() {
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  // $stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
	  $stmt = $conn->prepare("SELECT * FROM film");
	  echo $conn->error; // <-- ainult enda jaoks õppimise ajal
	  // seome tulemuse muutujaga
	  $stmt->bind_result($titlefromdb, $yearfromdb, $durationfromdb, $genrefromdb, $studiofromdb, $directorfromdb);
	  $stmt->execute();
	  $filmhtml = "\t<ol>\n";
	  while($stmt->fetch()) {
		  $filmhtml .= 
		  "\t\t<li>" . $titlefromdb . "
			<ul>
				<li>Valmimisaasta: " . $yearfromdb . "</li>
				<li>Kestus minutites: " . $durationfromdb . " minutit</li>
				<li>Žanr(id): " . $genrefromdb . "</li>
				<li>Tootja / stuudio: " . $studiofromdb . "</li>
				<li>Lavastaja: " . $directorfromdb . "</li>
			</ul>
		</li>\n";
	  }
	  $filmhtml .= "\t</ol>\n";
	  $stmt->close();
	  $conn->close();
	  return $filmhtml;
  } //readfilms lõpeb
  
  function savefilm($titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?, ?, ?, ?, ?, ?)");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("siisss", $titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput);
	$stmt->execute();
	$stmt->close();
	$conn->close();
  } // savefilm lõpeb

  function personfromdb() {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT person_id, first_name, last_name FROM person");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($personidfromdb, $personfirstnamefromdb, $personlastnamefromdb);
	$stmt->execute();
	$personhtml = "";
	  while($stmt->fetch()) {
		  $personhtml .= "<option value='" .$personidfromdb ."'>" .$personfirstnamefromdb ." " .$personlastnamefromdb ."</option>\n";
	  }
	$stmt->close();
	$conn->close();
	return $personhtml;
  } // personfromdb lõpeb

  function moviefromdb() {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_id, title FROM movie");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($movieidfromdb, $titlefromdb);
	$stmt->execute();
	$moviehtml = "";
	  while($stmt->fetch()) {
		  $moviehtml .= "<option value='" .$movieidfromdb ."'>" .$titlefromdb ."</option>\n";
	  }
	$stmt->close();
	$conn->close();
	return $moviehtml;
  } // moviefromdb lõpeb

  function positionfromdb() {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT position_id, position_name FROM position");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($positionidfromdb, $positionnamefromdb);
	$stmt->execute();
	$positionhtml = "";
	  while($stmt->fetch()) {
		  $positionhtml .= "<option value='" .$positionidfromdb ."'>" .$positionnamefromdb ."</option>\n";
	  }
	$stmt->close();
	$conn->close();
	return $positionhtml;
  } // positionfromdb lõpeb

  function saveconnection($personinput, $movieinput, $positioninput) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id) VALUES(?, ?, ?)");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("iii", $personinput, $movieinput, $positioninput);
	if($stmt->execute()) {
		$notice = "Salvestatud!";
	}
	else {
		$notice = $stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $notice;
  } // savefilm lõpeb