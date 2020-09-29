<?php
  $database = "if20_marilii_sa_1";

  function signup($firstname, $lastname, $gender, $birthdate, $email, $password) {
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $stmt = $conn->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
	  echo $conn->error;
	  
	  // Krüpteerime salasõna
	  $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	  $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	  
	  $stmt->bind_param("sssiss", $firstname, $lastname, $birthdate, $gender, $email, $pwdhash);
	  
	  if($stmt->execute()) {
		  $notice = "OK";
	  }
	  else {
		  $notice = $stmt->error;
	  }
	  
	  $stmt->close();
	  $conn->close();
	  return $notice;
  }
  
  function signin($email, $password) {
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $stmt = $conn->prepare("SELECT password FROM vpusers WHERE email = ?");
	  echo $conn->error;
	  
	  $stmt->bind_param("s", $email);
	  $stmt->bind_result($passwordfromdb);
	  
	  if($stmt->execute) {
		  // Kui tehniliselt korras
		  if($stmt->fetch()) {
			  // Kasutaja leiti
			  if(password_verify($password, $passwordfromdb)) {
				  // Parool õige
				  $stmt->close();
				  $conn->close();
				  header("Location: home.php");
				  exit();
			  }
			  else {
				  $notice = "Vale salasõna!";
			  }
		  }
		  else {
			  $notice = "Sellist kasutajat (" .$email .") ei leitud!";
		  }
	  }
	  else {
		  // Tehniline viga
		  $notice = $stmt->error;
	  }
	  $stmt->close();
	  $conn->close();
	  return $notice;
  }