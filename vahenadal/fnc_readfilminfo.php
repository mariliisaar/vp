<?php
  $database = "if20_marilii_sa_1";
  
  function readpersonsinfilm($sortby, $sortorder) {
	  $notice = "<p>Kahjuks filmitegelasi seoses filmidega ei leitud!</p> \n";
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $SQLsentence = "SELECT first_name, last_name, position_name, role, title, production_year, company_name FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id JOIN position ON position.position_id = person_in_movie.position_id JOIN movie_by_production_company ON movie_by_production_company.movie_movie_id = movie.movie_id JOIN production_company ON movie_by_production_company.production_company_id = production_company.production_company_id";

	  if($sortby == 0 and $sortorder == 0) {
		  $stmt = $conn->prepare($SQLsentence);
	  }
	  if($sortby == 1) {
		if($sortorder == 2) {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC"); 
		}
		else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name"); 
		}
	  }
	  if($sortby == 2) {
		if($sortorder == 2) {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY position_name DESC"); 
		}
		else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY position_name"); 
		}
	  }
	  if($sortby == 3) {
		if($sortorder == 2) {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); 
		}
		else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY title"); 
		}
	  }
	  if($sortby == 4) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY production_year DESC"); 
		  }
		  else {
			  $stmt = $conn->prepare($SQLsentence ." ORDER BY production_year"); 
		  }
	  }
	  if($sortby == 5) {
		if($sortorder == 2) {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY company_name DESC"); 
		}
		else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY company_name"); 
		}
	  }
	  
	  echo $conn->error; // <-- ainult õppimise jaoks!
	  $stmt->bind_result($firstnamefromdb, $lastnamefromdb, $positionfromdb, $rolefromdb, $titlefromdb, $yearfromdb, $companyfromdb);
	  $stmt->execute();
	  $lines = "";
	  while($stmt->fetch()) {
		  $lines .= "\t<tr>\n\t\t\t<td>" .$firstnamefromdb . " " .$lastnamefromdb ."</td>\n";
		  if(!empty($rolefromdb)) {
			  $lines .= "\t\t\t<td>" .$positionfromdb ." (" .$rolefromdb .")" ."</td>\n";
		  }
		  else {
			  $lines .= "\t\t\t<td>" .$positionfromdb ."</td>\n";
		  }
		  $lines .= "\t\t\t<td>" .$titlefromdb ."</td>\n";
		  $lines .= "\t\t\t<td>" .$yearfromdb ."</td>\n";
		  if(!empty($companyfromdb)) {
			$lines .= "\t\t\t<td>" .$companyfromdb ."</td>\n\t\t</tr>\n\t";
		  }
		  else {
			$lines .= "\t\t\t<td> </td>\n\t\t</tr>\n\t";
		  }
		  
	  }
	  if(!empty($lines)) {
		  $notice = "<table>\n\t\t<tr>\n\t\t\t" .'<th>Isiku Nimi &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=1&sortorder=2">&darr;</a></th>';
		  $notice .= "\n\t\t\t" .'<th>Amet (Roll) &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>';
		  $notice .= "\n\t\t\t" .'<th>Filmi Pealkiri &nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=3&sortorder=2">&darr;</a></th>';
		  $notice .= "\n\t\t\t" .'<th>Filmi tootmisaasta &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th>';
		  $notice .= "\n\t\t\t" .'<th>Filmi tootja &nbsp;<a href="?sortby=5&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=5&sortorder=2">&darr;</a></th>' ."\n\t";
		  $notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	  }
	  
	  $stmt->close();
	  $conn->close();
	  return $notice;
  } // readpersonsinfilm lõpeb

  function readquotes($sortby, $sortorder) {
	$notice = "<p>Kahjuks tsitaate ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT first_name, last_name, role, title, quote_text FROM quote JOIN person_in_movie ON quote.person_in_movie_id = person_in_movie.person_in_movie_id JOIN person ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 1) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY role"); 
	  }
	}
	if($sortby == 2) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY last_name"); 
	  }
	}
	if($sortby == 3) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY title"); 
	  }
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb, $quotefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "\t<tr>\n\t\t\t<td>" .'"' .$quotefromdb .'"' ."</td>\n";
		$lines .= "\t\t\t<td>" .$rolefromdb ."</td>\n";
		$lines .= "\t\t\t<td>" .$firstnamefromdb . " " .$lastnamefromdb ."</td>\n";
		$lines .= "\t\t\t<td>" .$titlefromdb ."</td>\n\t\t</tr>\n\t";		
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t\t<tr>\n\t\t\t<th>Tsitaat</th>";
		$notice .= "\n\t\t\t" .'<th>Roll &nbsp;<a href="?quotesortby=1&quotesortorder=1">&uarr;</a>&nbsp;<a href="?quotesortby=1&quotesortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t" .'<th>Näitleja &nbsp;<a href="?quotesortby=2&quotesortorder=1">&uarr;</a>&nbsp;<a href="?quotesortby=2&quotesortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t" .'<th>Film &nbsp;<a href="?quotesortby=3&quotesortorder=1">&uarr;</a>&nbsp;<a href="?quotesortby=3&quotesortorder=2">&darr;</a></th>' ."\n\t";
		$notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
} // readquotes lõpeb

  function readpersons($sortby, $sortorder) {
	$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$notice = "<p>Kahjuks isikuid ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT first_name, last_name, birth_date FROM person";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 1) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY first_name"); 
	  }
	}
	if($sortby == 2) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY last_name"); 
	  }
	}
	if($sortby == 3) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY birth_date DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY birth_date"); 
	  }
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $birthfromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$birthdate = substr($birthfromdb, 8, 2);
		$birthmonth = substr($birthfromdb, 5, 2);
		$birthyear = substr($birthfromdb, 0, 4);
		$lines .= "\t<tr>\n\t\t\t<td>" .$firstnamefromdb ."</td>\n";
		$lines .= "\t\t\t<td>" .$lastnamefromdb ."</td>\n";
		$lines .= "\t\t\t<td>" .$birthdate .". " .$monthnameset[$birthmonth - 1] ." " .$birthyear ."</td>\n\t\t</tr>\n\t";	
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t\t<tr>\n\t\t\t" .'<th>Eesnimi &nbsp;<a href="?personsortby=1&personsortorder=1">&uarr;</a>&nbsp;<a href="?personsortby=1&personsortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t" .'<th>Perekonnanimi &nbsp;<a href="?personsortby=2&personsortorder=1">&uarr;</a>&nbsp;<a href="?personsortby=2&personsortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t" .'<th>Sünnikuupäev &nbsp;<a href="?personsortby=3&personsortorder=1">&uarr;</a>&nbsp;<a href="?personsortby=3&personsortorder=2">&darr;</a></th>' ."\n\t";
		$notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
} // readpersons lõpeb

function readfilms($sortby, $sortorder) {
	$notice = "<p>Kahjuks filme ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT title, production_year, duration, description FROM movie";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 1) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY title"); 
	  }
	}
	if($sortby == 2) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY production_year DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY production_year"); 
	  }
	}
	if($sortby == 3) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY duration DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY duration"); 
	  }
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($titlefromdb, $yearfromdb, $durationfromdb, $descfromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "\t<tr>\n\t\t\t<td>" .$titlefromdb ."</td>\n";
		$lines .= "\t\t\t<td>" .$yearfromdb ."</td>\n";
		$lines .= "\t\t\t<td>" .$durationfromdb ."min</td>\n";
		if(!empty($descfromdb)) {
			$lines .= "\t\t\t<td>" .$descfromdb ."</td>\n\t\t</tr>\n\t";
		}
		else {
			$lines .= "\t\t\t<td> </td>\n\t\t</tr>\n\t";
		}	
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t\t<tr>\n\t\t\t" .'<th>Pealkiri &nbsp;<a href="?filmsortby=1&filmsortorder=1">&uarr;</a>&nbsp;<a href="?filmsortby=1&filmsortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t" .'<th>Tootmisaasta &nbsp;<a href="?filmsortby=2&filmsortorder=1">&uarr;</a>&nbsp;<a href="?filmsortby=2&filmsortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t" .'<th>Kestus &nbsp;<a href="?filmsortby=3&filmsortorder=1">&uarr;</a>&nbsp;<a href="?filmsortby=3&filmsortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t<th>Lühikirjeldus</th>\n\t";
		$notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
} // readfilms lõpeb

function readgenres($sortby, $sortorder) {
	$notice = "<p>Kahjuks žanreid ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT genre_name, description FROM genre";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 1) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY genre_name DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY genre_name"); 
	  }
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($namefromdb, $descfromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "\t<tr>\n\t\t\t<td>" .$namefromdb ."</td>\n";
		if(!empty($descfromdb)) {
			$lines .= "\t\t\t<td>" .$descfromdb ."</td>\n\t\t</tr>\n\t";
		}
		else {
			$lines .= "\t\t\t<td> </td>\n\t\t</tr>\n\t";
		}	
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t\t<tr>\n\t\t\t" .'<th>Žanri nimi &nbsp;<a href="?genresortby=1&genresortorder=1">&uarr;</a>&nbsp;<a href="?genresortby=1&genresortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t<th>Lühikirjeldus</th>\n\t";
		$notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
} // readgenres lõpeb

function readstudios($sortby, $sortorder) {
	$notice = "<p>Kahjuks filmitootjaid ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT company_name, company_address FROM production_company";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 1) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY company_name DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY company_name"); 
	  }
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($namefromdb, $addressfromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "\t<tr>\n\t\t\t<td>" .$namefromdb ."</td>\n";
		if(!empty($addressfromdb)) {
			$lines .= "\t\t\t<td>" .$addressfromdb ."</td>\n\t\t</tr>\n\t";
		}
		else {
			$lines .= "\t\t\t<td> </td>\n\t\t</tr>\n\t";
		}	
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t\t<tr>\n\t\t\t" .'<th>Filmitootja nimi &nbsp;<a href="?studiosortby=1&studiosortorder=1">&uarr;</a>&nbsp;<a href="?studiosortby=1&studiosortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t<th>Aadress</th>\n\t";
		$notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
} // readstudios lõpeb

function readpositions($sortby, $sortorder) {
	$notice = "<p>Kahjuks ametikohti ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT position_name, description FROM position";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 1) {
	  if($sortorder == 2) {
		$stmt = $conn->prepare($SQLsentence ." ORDER BY position_name DESC"); 
	  }
	  else {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY position_name"); 
	  }
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($namefromdb, $descfromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "\t<tr>\n\t\t\t<td>" .$namefromdb ."</td>\n";
		if(!empty($descfromdb)) {
			$lines .= "\t\t\t<td>" .$descfromdb ."</td>\n\t\t</tr>\n\t";
		}
		else {
			$lines .= "\t\t\t<td> </td>\n\t\t</tr>\n\t";
		}	
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t\t<tr>\n\t\t\t" .'<th>Ametikoht &nbsp;<a href="?positionsortby=1&positionsortorder=1">&uarr;</a>&nbsp;<a href="?positionsortby=1&positionsortorder=2">&darr;</a></th>';
		$notice .= "\n\t\t\t<th>Lühikirjeldus</th>\n\t";
		$notice .= "\t</tr>\n\t" .$lines ."</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
} // readpositions lõpeb

