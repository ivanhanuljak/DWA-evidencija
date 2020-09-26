<?php

$hasParameters = true;
$success = false;

if (empty($_GET["selected"]) || empty($_GET["username"]) || empty($_GET["password"])) {
	$hasParameters = false;
}
if (empty($_GET["selected"])){
	header('Location: index.php');
}
$selected = htmlspecialchars($_GET["selected"]);
$displayText = "Error 104";
if($selected == "1"){
	$displayText = "Programsko inženjerstvo";
}
else if($selected == "2"){
	$displayText = "Dinamičke web aplikacije";
}

$message = "";
$messageColor = "#ff0000";
$todayDate = date("Y-m-d");
if($hasParameters == true){




$un = htmlspecialchars($_GET["username"]);
$pw = md5(htmlspecialchars($_GET["password"]));
$orgPass = htmlspecialchars($_GET["password"]);
$accExists = false;
$accLoginToday = false;
$uid = 0;

if(strlen($un) > 3 && strlen($pw) > 3 ){
	//MYSQL CON
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "evidencija";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}
	
	
	$sql = "SELECT id, un, pw, class01, class02, reg_date FROM users";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		if($row["un"] == $un){
			if($row["pw"] == $pw){
				$uid = $row["id"];
				if($selected == 1){
					if($row["class01"] == 1){
						$message = "Uspješno ste se prijavili!";
						$success = true;
						$messageColor = "#00ff00";
						$uid = $row["id"];
						$accExists = true;
					}
					else{
						$message = "Niste registrirani na ovaj kolegij!";
						$messageColor = "#ff0000";
						$uid = $row["id"];
					}
				}
				if($selected == 2){
					if($row["class02"] == 1){
						$message = "Uspješno ste se prijavili!";
						$success = true;
						$messageColor = "#00ff00";
						$uid = $row["id"];
						$accExists = true;
					}
					else{
						$message = "Niste registrirani na ovaj kolegij!";
						$messageColor = "#ff0000";
						$uid = $row["id"];
					}
				}
			}
			else{
				$message = "Pogrešna lozinka!";
				$messageColor = "#ff0000";
			}
			break;
		}
		else {
		  $message = "Vaš račun ne postoji!";
		  $messageColor = "#ff0000";
		  $accExists = false;
		}
		
	  }
	} else {
	  $message = "Vaš račun ne postoji!";
	  $messageColor = "#ff0000";
	  $accExists = false;
	}

	if($accExists == true){
		if ($selected==1) {
		$sql = "SELECT login_id, uid, class_id, checkin_date FROM activity1";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
			if($row["uid"] == $uid){
				if($row["class_id"] == $selected){
					if($row["checkin_date"] == $todayDate){
						$message = "Već ste se prijavili!";
						$messageColor = "#00ff00";
						$accLoginToday = true;
					}
				}
			}
		  }
		}
		if($accLoginToday == false){
			
			$sql = "INSERT INTO activity1 (uid, class_id, checkin_date)
			VALUES ('$uid', '$selected', '$todayDate')";

			if ($conn->query($sql) === TRUE) {
			  //echo "New record created successfully";
			} else {
			  //echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}else {
		$sql = "SELECT login_id, uid, class_id, checkin_date FROM activity2";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
			if($row["uid"] == $uid){
				if($row["class_id"] == $selected){
					if($row["checkin_date"] == $todayDate){
						$message = "Već ste se prijavili!";
						$messageColor = "#00ff00";
						$accLoginToday = true;
					}
				}
			}
		  }
		}
		if($accLoginToday == false){
			
			$sql = "INSERT INTO activity2 (uid, class_id, checkin_date)
			VALUES ('$uid', '$selected', '$todayDate')";

			if ($conn->query($sql) === TRUE) {
			  //echo "New record created successfully";
			} else {
			  //echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}
	}
	$conn->close();
}
}
?>

<html>
	<head>
	<title>Prijavi se!</title>
		<link rel="stylesheet" href="css/layout.css">
	</head>
	<body>
		<h3 class="titleText">
			Upis na kolegij: <?php echo $displayText; ?>
		</h3>
		<div class="loginInput">
			<form action="login.php">
			  <input type="hidden" name="selected" id="selected" value="<?php echo $selected; ?>"><br><br>
			  <label class="inputLabel" for="username">Korisničko ime:</label>
			  <input type="text" placeholder="ovdje upišite svoje korisničko ime..." id="username" name="username"><br><br>
			  <label class="inputLabel" for="password">Lozinka:</label>
			  <input type="password" placeholder="ovdje upišite svoju lozinku..." id="password" name="password"><br><br>
			  <input type="submit" value="Prijavi se">
			</form>
			<b style="color:<?php echo $messageColor; ?>"><?php echo $message; ?></b><br><br>
			<?php if($success == true){?>
			<a href="overview.php?selected=<?php echo $selected; ?>&uid=<?php echo $uid; ?>&year=<?php echo date("Y"); ?>&month=<?php echo date("m"); ?>&day=<?php echo date("d"); ?>"<b style="color:#000000">Pogledaj evidenciju</b></a>
			<?php } ?>
		</div>
		<div class="footer">
			<b>Nisi registriran na ovaj kolegij? <a href="register.php?selected=<?php echo $selected; ?>">Klikni ovdje</a> i registriraj se!</b>
			<p><b>Za povratak na izbor kolegija <a href="index.php?selected=<?php echo $selected; ?>">klikni ovdje!</a></b></p>
		</div>
	</body>
</html>